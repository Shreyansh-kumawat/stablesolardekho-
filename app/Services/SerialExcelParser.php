<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSerial;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SerialExcelParser
 *
 * Parses invoice-style Excel where the "Item & Description" column contains:
 *   PRODUCT NAME (may be split across multiple rows)
 *   SR.NO / SR NO / SERIAL NO (header)
 *   SERIAL-1
 *   SERIAL-2
 *   ...
 *   (blank line)
 *   IGNORED FOOTER TEXT (warranty etc.) - kept as `skipped_lines` warning
 *
 * Returns array of product blocks with product_name, serials, skipped_lines.
 * Multiple product blocks in one file are all returned.
 */
class SerialExcelParser
{
    protected array $serialHeaderPatterns = [
        '/^s[a-z\s\.\)]*(no|number)[\.\s]*$/i',
        '/^sr[\.\s]*(no|number)?[\.\s]*$/i',
        '/^srno[\.\s]*$/i',
        '/^serial[\.\s]*(no|number)?[s]?[\.\s]*$/i',
    ];

    public function parse(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        // Normalize
        $rows = array_map(function ($r) {
            return array_map(fn($v) => is_string($v) ? trim($v) : $v, $r);
        }, $rows);

        // Locate the "Item & Description" column
        $itemColIdx = $this->findItemColumn($rows);
        if ($itemColIdx === null) {
            // Fallback: assume column with most text lines is item column
            $itemColIdx = $this->guessItemColumn($rows);
        }

        // Extract linear list of cells from that column
        $lines = [];
        foreach ($rows as $rowIdx => $row) {
            $val = isset($row[$itemColIdx]) ? trim((string) $row[$itemColIdx]) : '';
            $lines[] = ['row' => $rowIdx, 'text' => $val];
        }

        // State machine:
        // MODE_HEADER — collecting product name lines
        // MODE_SERIAL — collecting serial numbers (until first blank)
        // MODE_SKIPPED — after blank, collecting skipped lines as warning until next header
        $products = [];
        $current = null;
        $mode = 'header'; // header | serial | skipped

        foreach ($lines as $line) {
            $t = $line['text'];

            // Detect SR.NO header first (regardless of mode)
            if ($t !== '' && $this->isSerialHeader($t)) {
                if (!$current) {
                    $current = $this->emptyBlock();
                }
                $mode = 'serial';
                continue;
            }

            if ($t === '') {
                // Blank line handling
                if ($mode === 'serial') {
                    // Gap after serials — flip to skipped mode
                    $mode = 'skipped';
                } elseif ($mode === 'skipped') {
                    // Additional blank in skipped section — could be end of block
                    if ($current) {
                        $products[] = $this->finalizeBlock($current);
                        $current = null;
                    }
                    $mode = 'header';
                }
                continue;
            }

            // Non-empty line
            if ($mode === 'header') {
                if (!$current) $current = $this->emptyBlock();
                $current['name_lines'][] = $t;
            } elseif ($mode === 'serial') {
                // Only add if it looks like a serial
                if ($this->looksLikeSerial($t)) {
                    $current['serials'][] = $t;
                } else {
                    // Non-serial content in serial mode = end of serial section
                    // Move it into skipped, switch mode
                    $current['skipped_lines'][] = $t;
                    $mode = 'skipped';
                }
            } elseif ($mode === 'skipped') {
                // Any non-empty text after gap goes into skipped
                // If it happens to look like a serial, still skipped (per user requirement)
                $current['skipped_lines'][] = $t;
            }
        }

        if ($current) {
            $products[] = $this->finalizeBlock($current);
        }

        // Remove empty blocks
        $products = array_values(array_filter($products, function ($p) {
            return !empty($p['serials']) || !empty($p['product_name']);
        }));

        $this->detectDuplicates($products);
        return $products;
    }

    protected function emptyBlock(): array
    {
        return [
            'name_lines' => [],
            'serials' => [],
            'skipped_lines' => [],
        ];
    }

    protected function finalizeBlock(array $b): array
    {
        $name = trim(implode(' ', array_map('trim', $b['name_lines'])));
        // Collapse multiple whitespace
        $name = preg_replace('/\s+/', ' ', $name);
        if ($name === '') $name = '(unnamed product)';

        $serials = array_values(array_unique($b['serials']));

        $skippedText = trim(implode("\n", $b['skipped_lines']));

        return [
            'product_name' => $name,
            'serials' => $serials,
            'serial_count' => count($serials),
            'skipped_lines' => $b['skipped_lines'],
            'skipped_text' => $skippedText,
            'warnings' => $this->buildWarnings($serials, $b['skipped_lines']),
        ];
    }

    protected function buildWarnings(array $serials, array $skipped): array
    {
        $warnings = [];
        if (empty($serials)) {
            $warnings[] = "No serial numbers found for this product.";
        }
        if (!empty($skipped)) {
            $preview = array_slice($skipped, 0, 3);
            $more = count($skipped) > 3 ? ' (+' . (count($skipped) - 3) . ' more)' : '';
            $warnings[] = 'Skipped after line gap: ' . implode(' / ', $preview) . $more;
        }
        return $warnings;
    }

    /** Find the "Item & Description" column by header row */
    protected function findItemColumn(array $rows): ?int
    {
        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $c = strtolower(trim((string) $cell));
                if ($c === '') continue;
                if (str_contains($c, 'item') || str_contains($c, 'description') || str_contains($c, 'particular')) {
                    return $i;
                }
            }
        }
        return null;
    }

    /** Guess item column = column with most text content */
    protected function guessItemColumn(array $rows): int
    {
        $counts = [];
        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $s = trim((string) $cell);
                if ($s !== '' && strlen($s) > 3) {
                    $counts[$i] = ($counts[$i] ?? 0) + 1;
                }
            }
        }
        if (empty($counts)) return 1;
        arsort($counts);
        return array_key_first($counts);
    }

    protected function isSerialHeader(string $text): bool
    {
        $normalized = preg_replace('/\s+/', '', trim($text));
        if ($normalized === '') return false;
        // Direct patterns
        foreach ($this->serialHeaderPatterns as $pattern) {
            if (preg_match($pattern, $normalized)) return true;
        }
        $spaced = preg_replace('/\s+/', ' ', trim($text));
        // Short + contains SR/SERIAL and NO
        if (strlen($spaced) < 25) {
            if (preg_match('/\b(sr|serial)[\.\s]*(no|number|#)?[\.\s]*$/i', $spaced)) return true;
        }
        return false;
    }

    protected function looksLikeSerial(string $text): bool
    {
        if ($text === '') return false;
        if (strlen($text) < 4) return false;
        if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\-_\/\.]{3,}$/', $text)) return false;
        if (!preg_match('/\d/', $text)) return false;
        return true;
    }

    protected function detectDuplicates(array &$products): void
    {
        $seen = [];
        foreach ($products as &$p) {
            $dupes = [];
            foreach ($p['serials'] as $s) {
                $key = strtoupper($s);
                if (isset($seen[$key])) $dupes[] = $s;
                $seen[$key] = ($seen[$key] ?? 0) + 1;
            }
            if ($dupes) {
                $p['warnings'][] = 'Duplicate serials in file: ' . implode(', ', array_slice($dupes, 0, 5)) . (count($dupes) > 5 ? ' (+' . (count($dupes) - 5) . ' more)' : '');
                $p['has_duplicates_in_file'] = true;
            } else {
                $p['has_duplicates_in_file'] = false;
            }
        }
    }

    public function checkDbDuplicates(array $serials): array
    {
        if (empty($serials)) return [];
        return ProductSerial::whereIn('serial_number', $serials)
            ->pluck('serial_number')
            ->toArray();
    }

    public function suggestProductMatches(array $parsedProducts): array
    {
        $names = collect($parsedProducts)->pluck('product_name')->filter()->unique()->values();
        $matches = [];
        foreach ($names as $name) {
            if ($name === '(unnamed product)') { $matches[$name] = null; continue; }
            $found = Product::where('item_name', 'like', "%{$name}%")
                ->orWhere('item_code', 'like', "%{$name}%")
                ->first();
            if (!$found) {
                // Try first word as code hint
                $firstWord = strtok($name, ' ');
                if ($firstWord && strlen($firstWord) >= 4) {
                    $found = Product::where('item_code', 'like', "%{$firstWord}%")
                        ->orWhere('item_name', 'like', "%{$firstWord}%")
                        ->first();
                }
            }
            $matches[$name] = $found ? [
                'id' => $found->id,
                'item_name' => $found->item_name,
                'item_code' => $found->item_code,
                'category_id' => $found->category_id,
                'sub_category_id' => $found->sub_category_id,
                'is_serial_tracked' => (bool) $found->is_serialNumber_required,
            ] : null;
        }
        return $matches;
    }
}

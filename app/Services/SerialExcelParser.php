<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSerial;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SerialExcelParser — simple two-column logic.
 *
 * Column A = ID / # (row number 1, 2, 3...)
 * Column B = Item & Description (product name, then SR.NO, then serials, then warranty text)
 *
 * Whenever column A has a NEW number, a new product block starts.
 * SR.NO / SR NO / etc. in column B = serial section starts.
 * Blank cell in column B while collecting serials = section ends.
 * Anything after blank (until next numbered row) = skipped_lines warning.
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

        // Normalize: trim string cells
        $rows = array_map(function ($r) {
            return array_map(fn($v) => is_string($v) ? trim($v) : $v, $r);
        }, $rows);

        // Auto-detect columns
        [$idCol, $itemCol, $headerIdx] = $this->detectColumns($rows);
        if ($idCol === null || $itemCol === null) {
            return []; // Cannot detect structure
        }

        $products = [];
        $current = null;
        $mode = 'header'; // header | serial | skipped
        $totalRows = count($rows);

        for ($i = $headerIdx + 1; $i < $totalRows; $i++) {
            $row = $rows[$i];
            $idCell = isset($row[$idCol]) ? trim((string) $row[$idCol]) : '';
            $itemCell = isset($row[$itemCol]) ? trim((string) $row[$itemCol]) : '';

            // A numeric value in the ID column = NEW product block
            if ($idCell !== '' && is_numeric($idCell)) {
                // Close previous block
                if ($current) {
                    $products[] = $this->finalizeBlock($current);
                }
                // Start new block with first line of item as product name
                $current = [
                    'product_name' => $itemCell,
                    'serials' => [],
                    'skipped_lines' => [],
                ];
                $mode = 'header';
                continue;
            }

            // No current block yet, skip
            if (!$current) continue;

            // Serial header detected in item column → switch to serial mode
            if ($itemCell !== '' && $this->isSerialHeader($itemCell)) {
                $mode = 'serial';
                continue;
            }

            // Blank item cell
            if ($itemCell === '') {
                if ($mode === 'serial') {
                    // Gap ends serial collection → move to skipped mode
                    $mode = 'skipped';
                }
                continue;
            }

            // Non-empty item cell — behavior depends on mode
            if ($mode === 'header') {
                // Append to product name (multi-line product names)
                $current['product_name'] = trim($current['product_name'] . ' ' . $itemCell);
            } elseif ($mode === 'serial') {
                $current['serials'][] = $itemCell;
            } elseif ($mode === 'skipped') {
                $current['skipped_lines'][] = $itemCell;
            }
        }

        if ($current) {
            $products[] = $this->finalizeBlock($current);
        }

        $this->detectDuplicates($products);
        return $products;
    }

    /**
     * Detect ID column, Item column and header row.
     * Returns [idCol, itemCol, headerRowIdx].
     *
     * Strategy: scan up to first 15 rows for a row that has:
     *   - one cell that is "#" / "id" / "sr no" etc.  → ID column
     *   - one cell that contains "item" / "description" / "particular" / "name"  → Item column
     * If not found, fall back to: first column that has ANY row with a small integer (1, 2, 3...)
     * combined with the column that has the most text content = item column.
     */
    protected function detectColumns(array $rows): array
    {
        $limit = min(15, count($rows));

        // Pass 1: explicit header row
        for ($i = 0; $i < $limit; $i++) {
            $idCol = null;
            $itemCol = null;
            foreach ($rows[$i] as $c => $cell) {
                $v = strtolower(trim((string) $cell));
                if ($v === '') continue;
                if ($idCol === null && (
                    $v === '#' || $v === 'id' || $v === 'sr' || $v === 'sr.' ||
                    $v === 's.no' || $v === 's.no.' || $v === 'sno' ||
                    $v === 'sr no' || $v === 'sr.no' || $v === 'sr no.' || $v === 'sr.no.' ||
                    $v === 'serial'
                )) {
                    $idCol = $c;
                }
                if ($itemCol === null && (
                    str_contains($v, 'item') || str_contains($v, 'description') ||
                    str_contains($v, 'particular') || $v === 'name'
                )) {
                    $itemCol = $c;
                }
            }
            if ($idCol !== null && $idCol === $itemCol) {
                $itemCol = null; // same cell can't be both
            }
            if ($idCol !== null && $itemCol !== null) {
                return [$idCol, $itemCol, $i];
            }
        }

        // Pass 2: no explicit header — guess by content
        // Find first column that has consecutive integers 1, 2, 3
        $intCounts = [];
        foreach ($rows as $row) {
            foreach ($row as $c => $cell) {
                $s = trim((string) $cell);
                if ($s !== '' && is_numeric($s) && (int) $s === (float) $s && $s == (string) (int) $s) {
                    $intCounts[$c] = ($intCounts[$c] ?? 0) + 1;
                }
            }
        }
        if (!empty($intCounts)) {
            arsort($intCounts);
            $idCol = array_key_first($intCounts);
            // Item column = column with most non-numeric text content
            $textCounts = [];
            foreach ($rows as $row) {
                foreach ($row as $c => $cell) {
                    if ($c === $idCol) continue;
                    $s = trim((string) $cell);
                    if ($s !== '' && strlen($s) > 4) {
                        $textCounts[$c] = ($textCounts[$c] ?? 0) + 1;
                    }
                }
            }
            if (!empty($textCounts)) {
                arsort($textCounts);
                $itemCol = array_key_first($textCounts);
                return [$idCol, $itemCol, -1]; // -1 = no header row, start scanning from top
            }
        }

        return [null, null, null];
    }

    protected function finalizeBlock(array $b): array
    {
        $name = preg_replace('/\s+/', ' ', trim($b['product_name'])) ?: '(unnamed product)';
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
            $warnings[] = 'Skipped after line gap (copy-paste into Serial Numbers if needed): ' . implode(' / ', $preview) . $more;
        }
        return $warnings;
    }

    protected function isSerialHeader(string $text): bool
    {
        $collapsed = preg_replace('/\s+/', '', trim($text));
        if ($collapsed === '') return false;
        foreach ($this->serialHeaderPatterns as $pattern) {
            if (preg_match($pattern, $collapsed)) return true;
        }
        $spaced = preg_replace('/\s+/', ' ', trim($text));
        if (strlen($spaced) < 30) {
            if (preg_match('/\b(sr|serial)[\.\s]*(no|number|#)?[\.\s]*$/i', $spaced)) return true;
        }
        return false;
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
            $found = Product::whereRaw('LOWER(item_name) = ?', [strtolower($name)])->first();
            if (!$found) {
                $found = Product::where('item_name', 'like', '%' . $name . '%')->first();
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

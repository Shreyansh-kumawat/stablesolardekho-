<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSerial;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SerialExcelParser
 *
 * Parses purchase invoices / stock upload Excel files.
 * Detects product blocks, extracts serial numbers, matches qty.
 * Handles multi-product sheets.
 *
 * Recognized layout (Polycab / Adani / similar):
 *   | # | Item & Description | HSN | Qty | Rate | ... | Amount |
 *   | 1 | PRODUCT NAME       | ...
 *   |   | SR.NO              |
 *   |   | SERIAL1P           |
 *   |   | SERIAL2P           |
 *   |   | ...                |
 *   |   | WARRANTY TEXT      |  <-- ignored
 *   | 2 | NEXT PRODUCT NAME  |
 *   ...
 */
class SerialExcelParser
{
    /** Common headers we detect */
    protected array $serialHeaderPatterns = [
        '/^s[a-z\s\.\)]*(no|number)[\.\s]*$/i',
        '/^sr[\.\s]*(no|number)[\.\s]*$/i',
        '/^serial[\.\s]*(no|number)[s]?[\.\s]*$/i',
    ];

    /** Heuristic: strings we treat as non-serial "footer" text within a product block */
    protected array $footerKeywords = [
        'warranty', 'guarantee', 'terms', 'conditions',
        'total', 'subtotal', 'sub total', 'grand total',
        'tax', 'gst', 'cgst', 'sgst', 'igst', 'discount', 'rounding',
    ];

    /**
     * Parse an uploaded .xlsx/.xls/.csv file.
     * Returns array of product blocks with serials, qty, prices, mismatch warnings.
     */
    public function parse(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        // Trim and normalize
        $rows = array_map(function ($r) {
            return array_map(function ($v) {
                if (is_string($v)) return trim($v);
                return $v;
            }, $r);
        }, $rows);

        // Try to detect header row: contains "item" and "qty" and "amount" or "rate"
        $headerIdx = $this->findHeaderRow($rows);
        $columnMap = $headerIdx !== null ? $this->mapColumns($rows[$headerIdx]) : [];

        $products = [];
        $current = null;

        $start = $headerIdx !== null ? $headerIdx + 1 : 0;
        for ($i = $start; $i < count($rows); $i++) {
            $row = $rows[$i];
            $joined = implode(' ', array_map(fn($v) => (string) $v, $row));

            // Skip empty rows
            if (trim($joined) === '') {
                continue;
            }

            // Detect start of a new product block: has a numeric # in first column AND a description
            $numeric = false;
            if (isset($row[0]) && is_numeric(trim((string) $row[0]))) {
                $numeric = true;
            }

            $descIdx = $columnMap['desc'] ?? 1;
            $qtyIdx = $columnMap['qty'] ?? null;
            $rateIdx = $columnMap['rate'] ?? null;
            $amountIdx = $columnMap['amount'] ?? null;
            $hsnIdx = $columnMap['hsn'] ?? null;

            $desc = isset($row[$descIdx]) ? trim((string) $row[$descIdx]) : '';

            if ($numeric && $desc !== '') {
                // Save previous
                if ($current) {
                    $products[] = $this->finalizeBlock($current);
                }
                $cgstPctIdx = $columnMap['cgst_pct'] ?? null;
                $sgstPctIdx = $columnMap['sgst_pct'] ?? null;
                $igstPctIdx = $columnMap['igst_pct'] ?? null;
                $cgst = $cgstPctIdx !== null && isset($row[$cgstPctIdx]) ? $this->extractNumber($row[$cgstPctIdx]) : null;
                $sgst = $sgstPctIdx !== null && isset($row[$sgstPctIdx]) ? $this->extractNumber($row[$sgstPctIdx]) : null;
                $igst = $igstPctIdx !== null && isset($row[$igstPctIdx]) ? $this->extractNumber($row[$igstPctIdx]) : null;
                $gstPct = null;
                if ($cgst !== null || $sgst !== null) {
                    $gstPct = ($cgst ?? 0) + ($sgst ?? 0);
                } elseif ($igst !== null) {
                    $gstPct = $igst;
                }
                $current = [
                    'row_index' => $i,
                    'product_name' => $desc,
                    'description_lines' => [$desc],
                    'hsn_code' => $hsnIdx !== null && isset($row[$hsnIdx]) ? (string) $row[$hsnIdx] : null,
                    'qty' => $qtyIdx !== null && isset($row[$qtyIdx]) ? $this->extractNumber($row[$qtyIdx]) : null,
                    'rate' => $rateIdx !== null && isset($row[$rateIdx]) ? $this->extractNumber($row[$rateIdx]) : null,
                    'amount' => $amountIdx !== null && isset($row[$amountIdx]) ? $this->extractNumber($row[$amountIdx]) : null,
                    'gst_percent' => $gstPct,
                    'serials' => [],
                    'in_serial_section' => false,
                ];
                continue;
            }

            if (!$current) continue;

            $textCell = isset($row[$descIdx]) ? trim((string) $row[$descIdx]) : '';

            // Detect serial section header (SR.NO variants)
            if ($this->isSerialHeader($textCell)) {
                $current['in_serial_section'] = true;
                continue;
            }

            // Detect explicit end-of-serials keywords
            if ($this->isFooterText($textCell)) {
                $current['in_serial_section'] = false;
                continue;
            }

            // If in serial section, treat plain non-empty text as serial
            if ($current['in_serial_section']) {
                if ($textCell !== '' && $this->looksLikeSerial($textCell)) {
                    $current['serials'][] = $textCell;
                } elseif ($textCell !== '' && !$this->looksLikeSerial($textCell)) {
                    // hit non-serial content (e.g., warranty line) — close the section
                    $current['in_serial_section'] = false;
                }
            } else {
                // Additional description lines while not in serial section — append
                if ($textCell !== '') {
                    $current['description_lines'][] = $textCell;
                }
            }
        }

        if ($current) {
            $products[] = $this->finalizeBlock($current);
        }

        // Detect duplicates across all products in this upload
        $this->detectDuplicates($products);

        return $products;
    }

    protected function finalizeBlock(array $b): array
    {
        $serials = array_values(array_unique($b['serials']));
        $unitPriceFromAmount = null;
        if ($b['amount'] && $b['qty'] && (float) $b['qty'] > 0) {
            $unitPriceFromAmount = round(((float) $b['amount']) / ((float) $b['qty']), 2);
        }
        return [
            'product_name' => $b['product_name'],
            'hsn_code' => $b['hsn_code'],
            'qty' => (int) $b['qty'],
            'rate' => $b['rate'] ? (float) $b['rate'] : null,
            'amount' => $b['amount'] ? (float) $b['amount'] : null,
            'gst_percent' => isset($b['gst_percent']) ? $b['gst_percent'] : null,
            'unit_price_from_amount' => $unitPriceFromAmount,
            'serials' => $serials,
            'serial_count' => count($serials),
            'qty_matches' => (int) $b['qty'] === count($serials),
            'warnings' => $this->buildWarnings($b, count($serials)),
        ];
    }

    protected function buildWarnings(array $block, int $serialCount): array
    {
        $warnings = [];
        $qty = (int) $block['qty'];
        if ($qty > 0 && $serialCount !== $qty) {
            $warnings[] = "Quantity is {$qty} but found {$serialCount} serials.";
        }
        if ($qty > 0 && $serialCount === 0) {
            $warnings[] = "No serial numbers found for this product.";
        }
        return $warnings;
    }

    protected function findHeaderRow(array $rows): ?int
    {
        foreach ($rows as $idx => $row) {
            $joined = strtolower(implode('|', array_map(fn($v) => (string) $v, $row)));
            if (strpos($joined, 'item') !== false
                && strpos($joined, 'qty') !== false
                && (strpos($joined, 'amount') !== false || strpos($joined, 'rate') !== false)) {
                return $idx;
            }
        }
        return null;
    }

    protected function mapColumns(array $headerRow): array
    {
        $map = [];
        $sawCgst = false; $sawSgst = false; $sawIgst = false;
        foreach ($headerRow as $i => $cell) {
            $c = strtolower(trim((string) $cell));
            if ($c === '') continue;
            if (str_contains($c, 'item') || str_contains($c, 'description') || str_contains($c, 'particular')) {
                $map['desc'] = $i;
            } elseif (str_contains($c, 'hsn') || str_contains($c, 'sac')) {
                $map['hsn'] = $i;
            } elseif ($c === 'qty' || str_contains($c, 'quantity')) {
                $map['qty'] = $i;
            } elseif ($c === 'rate' || str_contains($c, 'price') || str_contains($c, 'unit')) {
                if (!isset($map['rate'])) $map['rate'] = $i;
            } elseif ($c === 'cgst') { $sawCgst = true; }
            elseif ($c === 'sgst') { $sawSgst = true; }
            elseif ($c === 'igst') { $sawIgst = true; }
            elseif ($c === '%' || $c === 'gst %' || $c === 'gst%' || $c === 'tax %') {
                if ($sawCgst && !isset($map['cgst_pct'])) $map['cgst_pct'] = $i;
                elseif ($sawSgst && !isset($map['sgst_pct'])) $map['sgst_pct'] = $i;
                elseif ($sawIgst && !isset($map['igst_pct'])) $map['igst_pct'] = $i;
            } elseif ($c === 'amt' || $c === 'gst amount' || $c === 'tax amount') {
                // ignore - we compute
            } elseif ($c === 'amount' || str_contains($c, 'total')) {
                if (!isset($map['amount'])) $map['amount'] = $i;
            }
        }
        return $map;
    }

    protected function isSerialHeader(string $text): bool
    {
        $normalized = preg_replace('/\s+/', ' ', trim($text));
        // Direct patterns
        foreach ($this->serialHeaderPatterns as $pattern) {
            if (preg_match($pattern, $normalized)) return true;
        }
        // Loose match: contains "SR" or "SERIAL" followed by NO within a short line
        if (strlen($normalized) < 30) {
            if (preg_match('/\b(sr|serial)[\.\s]*(no|number|#)/i', $normalized)) return true;
        }
        return false;
    }

    protected function isFooterText(string $text): bool
    {
        $low = strtolower($text);
        foreach ($this->footerKeywords as $kw) {
            if (str_contains($low, $kw)) return true;
        }
        return false;
    }

    protected function looksLikeSerial(string $text): bool
    {
        if ($text === '') return false;
        if (strlen($text) < 3) return false;
        // Must be alphanumeric-ish (allow -, _, /, .)
        if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\-_\/\.]{2,}$/', $text)) return false;
        // Reject pure numeric words that look like counts
        if (preg_match('/^\d+(\.\d+)?\s*(pcs|nos|kg|units)$/i', $text)) return false;
        // Reject if all letters (likely a word, not a serial)
        if (!preg_match('/\d/', $text)) return false;
        return true;
    }

    protected function extractNumber($val): ?float
    {
        if ($val === null) return null;
        if (is_numeric($val)) return (float) $val;
        $cleaned = preg_replace('/[^\d\.\-]/', '', (string) $val);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    /** Mark duplicates within this upload. */
    protected function detectDuplicates(array &$products): void
    {
        $seen = [];
        foreach ($products as $pi => &$p) {
            $dupes = [];
            foreach ($p['serials'] as $s) {
                $key = strtoupper($s);
                if (isset($seen[$key])) {
                    $dupes[] = $s;
                }
                $seen[$key] = ($seen[$key] ?? 0) + 1;
            }
            if ($dupes) {
                $p['warnings'][] = 'Duplicate serials within file: ' . implode(', ', array_slice($dupes, 0, 5)) . (count($dupes) > 5 ? ' (+' . (count($dupes) - 5) . ' more)' : '');
                $p['has_duplicates_in_file'] = true;
            } else {
                $p['has_duplicates_in_file'] = false;
            }
        }
    }

    /** Check which serials already exist in DB (across all products). */
    public function checkDbDuplicates(array $serials): array
    {
        if (empty($serials)) return [];
        return ProductSerial::whereIn('serial_number', $serials)
            ->pluck('serial_number')
            ->toArray();
    }

    /** Try to match parsed product names to existing products (by fuzzy code match in name). */
    public function suggestProductMatches(array $parsedProducts): array
    {
        $names = collect($parsedProducts)->pluck('product_name')->filter()->unique()->values();
        $matches = [];
        foreach ($names as $name) {
            $words = preg_split('/\s+/', strtoupper($name));
            $code = $words[0] ?? '';
            $found = null;
            if ($code) {
                $found = Product::where(function ($q) use ($code, $name) {
                    $q->where('item_code', 'like', "%{$code}%")
                      ->orWhere('item_name', 'like', "%{$code}%");
                })->first();
            }
            if (!$found) {
                $found = Product::where('item_name', 'like', "%{$name}%")->first();
            }
            $matches[$name] = $found ? [
                'id' => $found->id,
                'item_name' => $found->item_name,
                'item_code' => $found->item_code,
                'is_serial_tracked' => (bool) $found->is_serialNumber_required,
            ] : null;
        }
        return $matches;
    }
}

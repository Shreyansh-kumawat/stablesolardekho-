<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSerial;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SerialExcelParser
 *
 * Simplified parser: extract product name(s) + their serial numbers.
 * Everything else is ignored - admin fills price/GST manually.
 *
 * Expected layout (any of these works):
 *   Option A - Two column:
 *     Product Name              | Serial Number
 *     Waaree Panel 540W         |
 *                               | SN001
 *                               | SN002
 *
 *   Option B - Invoice-style (auto-detect):
 *     PSIS3K6SM1R2 POLYCAB...  <-- product name line
 *     SR.NO                     <-- header
 *     3K6210826-2628-986705182P
 *     3K6210826-2628-986705285P
 *     ...
 */
class SerialExcelParser
{
    protected array $serialHeaderPatterns = [
        '/^s[a-z\s\.\)]*(no|number)[\.\s]*$/i',
        '/^sr[\.\s]*(no|number)[\.\s]*$/i',
        '/^serial[\.\s]*(no|number)[s]?[\.\s]*$/i',
        '/^serial$/i',
    ];

    protected array $footerKeywords = [
        'warranty', 'guarantee', 'terms', 'conditions',
        'total', 'subtotal', 'sub total', 'grand total',
        'tax', 'gst', 'cgst', 'sgst', 'igst', 'discount', 'rounding',
        'invoice', 'hsn', 'sac',
    ];

    public function parse(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        $rows = array_map(function ($r) {
            return array_map(function ($v) {
                return is_string($v) ? trim($v) : $v;
            }, $r);
        }, $rows);

        $products = [];
        $current = null;
        $inSerialSection = false;

        foreach ($rows as $row) {
            // Get first non-empty cell as primary text
            $firstText = '';
            $secondText = '';
            $allText = '';
            foreach ($row as $c) {
                $s = trim((string) $c);
                if ($firstText === '' && $s !== '') $firstText = $s;
                elseif ($secondText === '' && $s !== '') $secondText = $s;
                if ($s !== '') $allText .= ' ' . $s;
            }
            $allText = trim($allText);

            if ($firstText === '' && $secondText === '') {
                // Empty row - close current block
                if ($current && !empty($current['serials'])) {
                    $products[] = $this->finalizeBlock($current);
                    $current = null;
                    $inSerialSection = false;
                }
                continue;
            }

            // Check for serial header (SR.NO, Serial Number etc.)
            if ($this->isSerialHeader($firstText) || $this->isSerialHeader($secondText)) {
                if (!$current) {
                    // Header found without product name yet - use blank name
                    $current = ['product_name' => '(unnamed)', 'serials' => []];
                }
                $inSerialSection = true;
                continue;
            }

            // Footer/skip keywords
            if ($this->isFooterText($firstText) || $this->isFooterText($secondText)) {
                continue;
            }

            // In serial section: any cell that looks like a serial is added
            if ($inSerialSection) {
                $added = false;
                foreach ($row as $c) {
                    $s = trim((string) $c);
                    if ($s !== '' && $this->looksLikeSerial($s)) {
                        $current['serials'][] = $s;
                        $added = true;
                    }
                }
                if (!$added && $allText !== '') {
                    // Row has content but nothing serial-like - could be next product name
                    if ($current && !empty($current['serials'])) {
                        $products[] = $this->finalizeBlock($current);
                    }
                    $current = ['product_name' => $firstText, 'serials' => []];
                    $inSerialSection = false;
                }
                continue;
            }

            // Not in serial section, non-empty row: treat as product name (start new block)
            if ($current && !empty($current['serials'])) {
                $products[] = $this->finalizeBlock($current);
            }

            // If second col has a serial-like value, treat as inline row
            if ($secondText !== '' && $this->looksLikeSerial($secondText)) {
                if (!$current || $current['product_name'] !== $firstText) {
                    if ($current && !empty($current['serials'])) $products[] = $this->finalizeBlock($current);
                    $current = ['product_name' => $firstText, 'serials' => []];
                }
                $current['serials'][] = $secondText;
                $inSerialSection = true;
            } else {
                $current = ['product_name' => $firstText, 'serials' => []];
                $inSerialSection = false;
            }
        }

        if ($current && !empty($current['serials'])) {
            $products[] = $this->finalizeBlock($current);
        }

        $this->detectDuplicates($products);
        return $products;
    }

    protected function finalizeBlock(array $b): array
    {
        $serials = array_values(array_unique($b['serials']));
        return [
            'product_name' => $b['product_name'],
            'hsn_code' => null,
            'qty' => count($serials),
            'rate' => null,
            'amount' => null,
            'gst_percent' => null,
            'unit_price_from_amount' => null,
            'serials' => $serials,
            'serial_count' => count($serials),
            'qty_matches' => true,
            'warnings' => empty($serials) ? ['No serial numbers found for this product.'] : [],
        ];
    }

    protected function isSerialHeader(string $text): bool
    {
        $normalized = preg_replace('/\s+/', ' ', trim($text));
        if ($normalized === '') return false;
        foreach ($this->serialHeaderPatterns as $pattern) {
            if (preg_match($pattern, $normalized)) return true;
        }
        if (strlen($normalized) < 30) {
            if (preg_match('/\b(sr|serial)[\.\s]*(no|number|#)/i', $normalized)) return true;
        }
        return false;
    }

    protected function isFooterText(string $text): bool
    {
        $low = strtolower($text);
        if ($low === '') return false;
        foreach ($this->footerKeywords as $kw) {
            if (str_contains($low, $kw)) return true;
        }
        return false;
    }

    protected function looksLikeSerial(string $text): bool
    {
        if ($text === '') return false;
        if (strlen($text) < 3) return false;
        if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\-_\/\.]{2,}$/', $text)) return false;
        if (preg_match('/^\d+(\.\d+)?\s*(pcs|nos|kg|units)$/i', $text)) return false;
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
                $p['warnings'][] = 'Duplicate serials within file: ' . implode(', ', array_slice($dupes, 0, 5)) . (count($dupes) > 5 ? ' (+' . (count($dupes) - 5) . ' more)' : '');
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
            if ($name === '(unnamed)') { $matches[$name] = null; continue; }
            $words = preg_split('/\s+/', strtoupper($name));
            $code = $words[0] ?? '';
            $found = null;
            if ($code) {
                $found = Product::where(function ($q) use ($code) {
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

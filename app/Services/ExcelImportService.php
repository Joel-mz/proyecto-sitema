<?php

namespace App\Services;

use ZipArchive;

class ExcelImportService
{
    /**
     * Parse an uploaded file (.xlsx or .csv) into an array of associative rows.
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseFile(string $filePath, string $extension): array
    {
        if (strtolower($extension) === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        return $this->parseCsv($filePath);
    }

    /**
     * Parse a CSV file.
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return [];
        }

        // Remove UTF-8 BOM if present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Detect delimiter (semicolon, comma, tab)
        $firstLine = strtok($content, "\r\n");
        $delimiter = ',';
        if ($firstLine !== false) {
            $commaCount = substr_count($firstLine, ',');
            $semicolonCount = substr_count($firstLine, ';');
            $tabCount = substr_count($firstLine, "\t");

            if ($semicolonCount > $commaCount && $semicolonCount > $tabCount) {
                $delimiter = ';';
            } elseif ($tabCount > $commaCount && $tabCount > $semicolonCount) {
                $delimiter = "\t";
            }
        }

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $headers = [];
        $rows = [];

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (empty($data) || (count($data) === 1 && $data[0] === null)) {
                continue;
            }

            // Clean up headers on first row
            if (empty($headers)) {
                $headers = array_map(function ($h) {
                    $h = trim((string) $h);
                    $h = strtolower($h);
                    $h = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ', ' '], ['a', 'e', 'i', 'o', 'u', 'n', '_'], $h);

                    return preg_replace('/[^a-z0-9_]/', '', $h);
                }, $data);

                continue;
            }

            $row = [];
            foreach ($headers as $index => $key) {
                if (! empty($key)) {
                    $row[$key] = isset($data[$index]) ? trim((string) $data[$index]) : '';
                }
            }

            if (! empty(array_filter($row))) {
                $rows[] = $row;
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Parse an XLSX file without external dependencies.
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseXlsx(string $filePath): array
    {
        if (! class_exists('ZipArchive')) {
            return [];
        }

        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            return [];
        }

        // 1. Read Shared Strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = simplexml_load_string($sharedStringsXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $t = '';
                        foreach ($si->r as $r) {
                            $t .= (string) $r->t;
                        }
                        $sharedStrings[] = $t;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Read First Worksheet
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $xml = simplexml_load_string($sheetXml);
        if (! $xml || ! isset($xml->sheetData->row)) {
            return [];
        }

        $rawRows = [];
        foreach ($xml->sheetData->row as $row) {
            $cells = [];
            foreach ($row->c as $c) {
                $cellRef = (string) $c['r']; // e.g. "A1", "B1"
                preg_match('/([A-Z]+)(\d+)/', $cellRef, $matches);
                $colLetters = $matches[1] ?? 'A';
                $colIndex = $this->columnLetterToIndex($colLetters);

                $cellType = (string) $c['t'];
                $val = isset($c->v) ? (string) $c->v : '';

                if ($cellType === 's' && isset($sharedStrings[(int) $val])) {
                    $val = $sharedStrings[(int) $val];
                } elseif ($cellType === 'inlineStr' && isset($c->is->t)) {
                    $val = (string) $c->is->t;
                }

                $cells[$colIndex] = trim($val);
            }
            if (! empty(array_filter($cells))) {
                // Ensure array keys are sequential
                $maxIndex = max(array_keys($cells));
                $rowArr = [];
                for ($i = 0; $i <= $maxIndex; $i++) {
                    $rowArr[$i] = $cells[$i] ?? '';
                }
                $rawRows[] = $rowArr;
            }
        }

        if (empty($rawRows)) {
            return [];
        }

        // Extract headers
        $headerRow = array_shift($rawRows);
        $headers = array_map(function ($h) {
            $h = trim((string) $h);
            $h = strtolower($h);
            $h = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ', ' '], ['a', 'e', 'i', 'o', 'u', 'n', '_'], $h);

            return preg_replace('/[^a-z0-9_]/', '', $h);
        }, $headerRow);

        $result = [];
        foreach ($rawRows as $rowCells) {
            $row = [];
            foreach ($headers as $index => $key) {
                if (! empty($key)) {
                    $row[$key] = $rowCells[$index] ?? '';
                }
            }
            if (! empty(array_filter($row))) {
                $result[] = $row;
            }
        }

        return $result;
    }

    /**
     * Convert Excel column letters (A, B, ..., Z, AA, AB) to 0-based integer index.
     */
    private function columnLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index = 0;
        $length = strlen($letters);
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }
}

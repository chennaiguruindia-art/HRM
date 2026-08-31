<?php

namespace App\Services;

class SimpleXlsxReader
{
    /**
     * Parse XLSX / XLS / CSV file into 2D array of strings
     *
     * @param string $filePath
     * @return array
     */
    public static function read(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'csv' || $extension === 'txt') {
            return self::readCsv($filePath);
        }

        // Try ZipArchive first if available
        if (class_exists(\ZipArchive::class)) {
            try {
                if (class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                    $sheet = $spreadsheet->getActiveSheet();
                    return $sheet->toArray(null, true, true, true);
                }
            } catch (\Throwable $e) {
                // fallback to native zip/xml extraction
            }
        }

        // Fallback: extract XLSX using Windows PowerShell / tar / 7z
        return self::readXlsxViaExtraction($filePath);
    }

    protected static function readCsv(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $rowNum = 1;
            while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                $rowAssoc = [];
                foreach ($data as $colIdx => $val) {
                    $colLetter = self::columnLetter($colIdx + 1);
                    $rowAssoc[$colLetter] = trim((string) $val);
                }
                $rows[$rowNum] = $rowAssoc;
                $rowNum++;
            }
            fclose($handle);
        }
        return $rows;
    }

    protected static function readXlsxViaExtraction(string $filePath): array
    {
        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'xlsx_' . uniqid();
        @mkdir($tempDir, 0777, true);

        $escapedZip = escapeshellarg($filePath);
        $escapedDest = escapeshellarg($tempDir);

        // Use PowerShell System.IO.Compression.ZipFile to extract without ZipArchive
        $psCommand = "powershell -NoProfile -ExecutionPolicy Bypass -Command \"[System.IO.Compression.ZipFile]::ExtractToDirectory({$escapedZip}, {$escapedDest})\"";
        exec($psCommand, $out, $ret);

        if (!file_exists($tempDir . '/xl/worksheets/sheet1.xml')) {
            // Try tar
            exec("tar -xf {$escapedZip} -C {$escapedDest}", $out2, $ret2);
        }

        $sheetPath = $tempDir . '/xl/worksheets/sheet1.xml';
        $sharedStringsPath = $tempDir . '/xl/sharedStrings.xml';

        if (!file_exists($sheetPath)) {
            self::cleanupDir($tempDir);
            throw new \Exception("Could not extract or parse Excel contents. Please ensure the file is a valid .xlsx file or restart the PHP server to enable the Zip extension.");
        }

        // Read shared strings
        $sharedStrings = [];
        if (file_exists($sharedStringsPath)) {
            $xml = simplexml_load_file($sharedStringsPath);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // Read sheet1.xml
        $sheetXml = simplexml_load_file($sheetPath);
        $rows = [];

        if ($sheetXml && isset($sheetXml->sheetData->row)) {
            foreach ($sheetXml->sheetData->row as $r) {
                $rowIdx = (int) $r['r'];
                $rowAssoc = [];

                if (isset($r->c)) {
                    foreach ($r->c as $c) {
                        $cellRef = (string) $c['r']; // e.g. "A1", "B2"
                        preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches);
                        $colLetter = $matches[1] ?? 'A';
                        $type = (string) $c['t'];
                        $val = isset($c->v) ? (string) $c->v : '';

                        if ($type === 's') { // shared string
                            $strIdx = (int) $val;
                            $cellValue = $sharedStrings[$strIdx] ?? '';
                        } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                            $cellValue = (string) $c->is->t;
                        } else {
                            $cellValue = $val;
                        }

                        $rowAssoc[$colLetter] = trim($cellValue);
                    }
                }

                $rows[$rowIdx] = $rowAssoc;
            }
        }

        self::cleanupDir($tempDir);

        return $rows;
    }

    protected static function cleanupDir(string $dir): void
    {
        if (!is_dir($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? self::cleanupDir($path) : @unlink($path);
        }
        @rmdir($dir);
    }

    protected static function columnLetter(int $colIndex): string
    {
        $letter = '';
        while ($colIndex > 0) {
            $mod = ($colIndex - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colIndex = (int) (($colIndex - $mod) / 26);
        }
        return $letter;
    }
}

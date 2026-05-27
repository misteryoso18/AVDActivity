<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

class ExcelExportService
{
    /**
    * Export data to a CSV file that opens directly in Excel.
     *
     * @param Collection|array $data Data to export
     * @param string $filename Output filename
     * @param array $headers Column headers
     * @return void
     */
    public static function exportToExcel($data, $filename = 'export.csv', $headers = [])
    {
        try {
            if ($data instanceof Collection) {
                $data = $data->toArray();
            }

            // Convert to array if needed
            if (!is_array($data)) {
                $data = (array)$data;
            }

            // Ensure it's an array of arrays
            if (!empty($data) && is_object($data[0])) {
                $data = array_map(function($item) {
                    return (array)$item;
                }, $data);
            }

            // Prefer generating a real .xlsx when PhpSpreadsheet is available
            if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                // Ensure filename ends with .xlsx
                if (!str_ends_with(strtolower($filename), '.xlsx')) {
                    $filename = preg_replace('/\.(csv|xls)$/i', '', $filename) . '.xlsx';
                }

                // Use PhpSpreadsheet to create a real XLSX file
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Headers
                if (!empty($headers)) {
                    $col = 1;
                    foreach ($headers as $header) {
                        $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
                        $sheet->setCellValue($coord, (string)$header);
                        $col++;
                    }
                } elseif (!empty($data)) {
                    $col = 1;
                    foreach (array_keys($data[0]) as $key) {
                        $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
                        $sheet->setCellValue($coord, (string)$key);
                        $col++;
                    }
                }

                // Data rows
                $rowNum = 2;
                foreach ($data as $row) {
                    $col = 1;
                    foreach ($row as $cell) {
                        $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . (string)$rowNum;
                        $sheet->setCellValue($coord, $cell);
                        $col++;
                    }
                    $rowNum++;
                }

                // Output headers
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                return;
            }

            // Fallback: output CSV when PhpSpreadsheet not available
            // Add .csv extension
            if (!str_ends_with(strtolower($filename), '.csv')) {
                $filename = preg_replace('/\.(xls|xlsx)$/i', '', $filename) . '.csv';
            }

            // Set headers for download
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            // Create UTF-8 CSV with BOM so Excel opens it cleanly.
            echo self::generateCsvContent($data, $headers);

        } catch (Exception $e) {
            throw new Exception('Excel Export Error: ' . $e->getMessage());
        }
    }

    /**
     * Export data to CSV format
     *
     * @param Collection|array $data Data to export
     * @param string $filename Output filename
     * @param array $headers Column headers
     * @return void
     */
    public static function exportToCSV($data, $filename = 'export.csv', $headers = [])
    {
        try {
            if ($data instanceof Collection) {
                $data = $data->toArray();
            }

            // Convert objects to arrays
            if (!empty($data) && is_object($data[0])) {
                $data = array_map(function($item) {
                    return (array)$item;
                }, $data);
            }

            // Set headers for download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $output = fopen('php://output', 'w');

            // If headers provided, write them first
            if (!empty($headers)) {
                fputcsv($output, $headers);
            } elseif (!empty($data)) {
                // Use first row keys as headers
                fputcsv($output, array_keys($data[0]));
            }

            // Write data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            fclose($output);

        } catch (Exception $e) {
            throw new Exception('CSV Export Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate CSV content
     *
     * @param array $data Data to export
     * @param array $headers Column headers
     * @return string CSV content
     */
    private static function generateCsvContent($data, $headers = [])
    {
        $output = fopen('php://temp', 'r+');

        if ($output === false) {
            throw new Exception('Unable to create CSV output stream.');
        }

        // UTF-8 BOM helps Excel detect encoding correctly.
        fwrite($output, "\xEF\xBB\xBF");

        if (!empty($headers)) {
            fputcsv($output, $headers);
        } elseif (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
        }

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv === false ? '' : $csv;
    }

    /**
     * Create downloadable file from array data
     *
     * @param array $data
     * @param string $filename
     * @param string $format (csv or xlsx)
     * @return void
     */
    public static function download($data, $filename = 'export', $format = 'csv')
    {
        $format = strtolower($format);
        
        if ($format === 'xlsx' || $format === 'excel') {
            self::exportToExcel($data, $filename);
        } else {
            self::exportToCSV($data, $filename);
        }
    }
}

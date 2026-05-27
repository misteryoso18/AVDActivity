<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

class ExcelExportService
{
    /**
     * Export data to Excel (CSV format)
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

            // Add .xlsx extension
            if (!str_ends_with($filename, '.xlsx')) {
                $filename = str_replace('.csv', '', $filename) . '.xlsx';
            }

            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            // Create simple Excel XML format
            $xmlContent = self::generateExcelXML($data, $headers);
            echo $xmlContent;

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
     * Generate Excel XML format (compatible with Excel)
     *
     * @param array $data Data to export
     * @param array $headers Column headers
     * @return string XML content
     */
    private static function generateExcelXML($data, $headers = [])
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">' . "\n";
        $xml .= '<Created>' . date('Y-m-dT H:i:sZ') . '</Created>' . "\n";
        $xml .= '</DocumentProperties>' . "\n";
        $xml .= '<Worksheet ss:Name="Sheet1">' . "\n";
        $xml .= '<Table>' . "\n";

        // Add headers
        if (!empty($headers)) {
            $xml .= '<Row>' . "\n";
            foreach ($headers as $header) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        } elseif (!empty($data)) {
            // Use first row keys as headers
            $xml .= '<Row>' . "\n";
            foreach (array_keys($data[0]) as $key) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($key) . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        }

        // Add data rows
        foreach ($data as $row) {
            $xml .= '<Row>' . "\n";
            foreach ($row as $cell) {
                $type = is_numeric($cell) ? 'Number' : 'String';
                $xml .= '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars((string)$cell) . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>' . "\n";

        return $xml;
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

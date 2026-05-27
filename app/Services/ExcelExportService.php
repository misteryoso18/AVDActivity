<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

class ExcelExportService
{
    /**
    * Export data to an Excel-compatible HTML spreadsheet (.xls)
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

            // Add .xls extension
            if (!str_ends_with(strtolower($filename), '.xls')) {
                $filename = preg_replace('/\.(csv|xlsx)$/i', '', $filename) . '.xls';
            }

            // Set headers for download
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            // Create Excel-readable HTML table
            echo self::generateExcelHtml($data, $headers);

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
     * Generate Excel-readable HTML format
     *
     * @param array $data Data to export
     * @param array $headers Column headers
     * @return string HTML content
     */
    private static function generateExcelHtml($data, $headers = [])
    {
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1">';

        if (!empty($headers)) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars((string) $header) . '</th>';
            }
            $html .= '</tr>';
        } elseif (!empty($data)) {
            $html .= '<tr>';
            foreach (array_keys($data[0]) as $key) {
                $html .= '<th>' . htmlspecialchars((string) $key) . '</th>';
            }
            $html .= '</tr>';
        }

        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars((string) $cell) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return $html;
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

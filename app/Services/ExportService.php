<?php

namespace App\Services;

use Dompdf\Options;
use Exception;

class ExportService
{
    /**
     * Generate PDF from HTML content
     *
     * @param string $html HTML content
     * @param string $filename Output filename
     * @param array $options PDF options
     * @return void
     */
    public static function generatePDF($html, $filename = 'report.pdf', $options = [])
    {
        try {
            /** @var \Dompdf\Dompdf $dompdf */
            $dompdf = new \Dompdf\Dompdf();

            $dompdfOptions = new Options();
            $dompdfOptions->set('isPhpEnabled', true);
            $dompdfOptions->set('isJavascriptEnabled', false);
            $dompdfOptions->set('isRemoteEnabled', false);
            $dompdfOptions->set('isHtml5ParserEnabled', true);
            $dompdfOptions->set('isFontSubsettingEnabled', true);
            $dompdfOptions->set('logOutputFile', storage_path('logs/dompdf.log'));

            foreach ($options as $key => $value) {
                if ($key === 'paper_size' || $key === 'orientation') {
                    continue;
                }

                $dompdfOptions->set($key, $value);
            }

            $dompdf->setOptions($dompdfOptions);
            
            // Load HTML
            $dompdf->loadHtml($html);
            
            // Set paper size and orientation
            $paperSize = $options['paper_size'] ?? 'A4';
            $orientation = $options['orientation'] ?? 'portrait';
            $dompdf->setPaper($paperSize, $orientation);
            
            // Render PDF
            $dompdf->render();
            
            // Output PDF
            $dompdf->stream($filename, ['Attachment' => false]);
            
        } catch (Exception $e) {
            throw new Exception('PDF Generation Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF and return as download
     *
     * @param string $html HTML content
     * @param string $filename Output filename
     * @param array $options PDF options
     * @return void
     */
    public static function downloadPDF($html, $filename = 'report.pdf', $options = [])
    {
        try {
            /** @var \Dompdf\Dompdf $dompdf */
            $dompdf = new \Dompdf\Dompdf();

            $dompdfOptions = new Options();
            $dompdfOptions->set('isPhpEnabled', true);
            $dompdfOptions->set('isJavascriptEnabled', false);
            $dompdfOptions->set('isRemoteEnabled', false);
            $dompdfOptions->set('isHtml5ParserEnabled', true);

            foreach ($options as $key => $value) {
                if ($key === 'paper_size' || $key === 'orientation') {
                    continue;
                }

                $dompdfOptions->set($key, $value);
            }

            $dompdf->setOptions($dompdfOptions);
            
            // Load HTML
            $dompdf->loadHtml($html);
            
            // Set paper size and orientation
            $paperSize = $options['paper_size'] ?? 'A4';
            $orientation = $options['orientation'] ?? 'portrait';
            $dompdf->setPaper($paperSize, $orientation);
            
            // Render PDF
            $dompdf->render();
            
            // Output as download
            $dompdf->stream($filename, ['Attachment' => true]);
            
        } catch (Exception $e) {
            throw new Exception('PDF Download Error: ' . $e->getMessage());
        }
    }
}

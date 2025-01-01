<?php

namespace Brayan\PruebaTecnica\Helpers;

use FPDF;

/**
 * Clase de ayuda para exportar datos a diferentes formatos.
 */
class ExportHelper
{
    /**
     * Exporta un array a un archivo CSV
     *
     * @param array $data
     * @param string $filename
     * @return void
     */
    public static function exportToCSV(array $data, string $filename = 'export.csv')
    {
        // Enviar encabezados HTTP para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Abrir un archivo temporal en modo escritura
        $file = fopen('php://output', 'w');

        // Obtener las claves del primer elemento del array como encabezados de columna
        if (!empty($data)) {
            fputcsv($file, array_keys($data[0]));
        }

        // Escribir los datos en el archivo CSV
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        // Cerrar el archivo
        fclose($file);
        exit;
    }

    /**
     * Exporta un array a un archivo PDF
     *
     * @param array $data
     * @param string $filename
     * @return void
     */
    public static function exportToPDF(array $data, string $filename = 'export.pdf')
    {
        if (empty($data)) {
            Response::json(400, 'No hay datos para exportar');
        }

        // Crear una instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '',  8);

        // Obtener las claves del primer elemento del array como encabezados de columna
        if (!empty($data)) {
            foreach (array_keys($data[0]) as $header) {
                $pdf->Cell(40, 10, $header, 1);
            }
            $pdf->Ln();

            // Escribir los datos en el archivo PDF
            foreach ($data as $row) {
                foreach ($row as $column) {
                    $pdf->Cell(40, 10, $column, 1);
                }
                $pdf->Ln();
            }
        }

        // Enviar encabezados HTTP para la descarga del archivo
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $pdf->Output('D', $filename);
        exit;
    }
}

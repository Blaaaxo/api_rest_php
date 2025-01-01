<?php

namespace Brayan\PruebaTecnica\Controllers;

use Brayan\PruebaTecnica\Helpers\ExportHelper;
use Brayan\PruebaTecnica\Models\User;
use FPDF;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Controlador para la exportación de datos.
 */
class ExportController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Exporta los usuarios a un archivo CSV.
     *
     * @return void
     */
    public function exportToCsv()
    {
        // Obtener todos los usuarios
        $users = $this->userModel->getAllUsers();

        // Exportar los usuarios a CSV
        ExportHelper::exportToCSV($users, 'users.csv');
    }

    /**
     * Exporta los usuarios a un archivo PDF.
     *
     * @return void
     */
    public function exportToPdf()
    {
        // Obtener todos los usuarios
        $users = $this->userModel->getAllUsers();

        // Exportar los usuarios a PDF
        ExportHelper::exportToPDF($users, 'users.pdf');
    }
}

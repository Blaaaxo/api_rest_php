<?php

use Brayan\PruebaTecnica\Controllers\ExportController;
use Brayan\PruebaTecnica\Controllers\UserController;
use Brayan\PruebaTecnica\Middlewares\AuthMiddleware;
use Brayan\PruebaTecnica\Models\User;

$userModel = new User();
$userController = new UserController($userModel);
$exportController = new ExportController($userModel);

// Ruta para exportar usuarios a CSV
$router->get('/api/users/export/csv', function () use ($exportController) {
    $exportController->exportToCsv();
});

// Ruta para exportar usuarios a PDF
$router->get('/api/users/export/pdf', function () use ($exportController) {
    $exportController->exportToPdf();
});

// obtiene todos los usuarios (solo para administradores)
$router->get('/api/users', function () use ($userController) {
    AuthMiddleware::verifyToken('Admin');
    $response = $userController->getAllUsers();
    http_response_code($response['status']);
    echo json_encode($response);
});

// obtiene un usuario por su id  
$router->get('/api/users/{id}', function ($id) use ($userController) {
    AuthMiddleware::verifyToken(); // para cualquier rol
    $response = $userController->getUserById($id);
    http_response_code($response['status']);
    echo json_encode($response);
});

// crea un nuevo usuario (solo para administradores)
$router->post('/api/users', function () use ($userController) {
    AuthMiddleware::verifyToken('Admin');
    $request = json_decode(file_get_contents('php://input'), true);
    $response = $userController->createUser($request);
    http_response_code($response['status']);
    echo json_encode($response);
});

// actualiza un usuario por su ID (solo para administradores)
$router->put('/api/users/{id}', function ($id) use ($userController) {
    AuthMiddleware::verifyToken('Admin');
    $request = json_decode(file_get_contents('php://input'), true);
    $response = $userController->updateUser($id, $request);
    http_response_code($response['status']);
    echo json_encode($response);
});

// elimina un usuario por su ID (solo para administradores)
$router->delete('/api/users/{id}', function ($id) use ($userController) {
    AuthMiddleware::verifyToken('Admin');
    $response = $userController->deleteUser($id);
    http_response_code($response['status']);
    echo json_encode($response);
});



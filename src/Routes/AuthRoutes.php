<?php
use Brayan\PruebaTecnica\Controllers\AuthController;

$authController = new AuthController();

// ruta para registrar un usuario
$router->post('/api/register', function () use ($authController) {
    
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $authController->register($data);
    http_response_code($response['status']);

    echo json_encode($response);
});


// ruta para iniciar sesión
$router->post('/api/login', function () use ($authController) {
    
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $authController->login($data);
    http_response_code($response['status']);

    echo json_encode($response);
});
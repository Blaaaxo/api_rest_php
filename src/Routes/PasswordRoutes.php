<?php

use Brayan\PruebaTecnica\Controllers\PasswordController;

$passwordController = new PasswordController();

// solicitar el token de restablecimiento de contraseña
$router->post('/api/password/forgot', function () use ($passwordController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $passwordController->requestPasswordReset($data);
});

// restablecer contraseña
$router->post('/api/password/reset', function () use ($passwordController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $passwordController->resetPassword($data);
});

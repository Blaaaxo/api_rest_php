<?php

namespace Brayan\PruebaTecnica\Controllers;

use Brayan\PruebaTecnica\Helpers\Response;
use Brayan\PruebaTecnica\Models\User;

class PasswordController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Solicitar restablecimiento de contraseña.
     *
     * @param array $request Datos de la solicitud.
     */
    public function requestPasswordReset($request)
    {
        if (!isset($request['email'])) {
            Response::json(400, 'Correo electrónico es requerido.');
        }

        $user = $this->userModel->getUserByEmail($request['email']);
        if (!$user) {
            Response::json(404, 'Usuario no encontrado.');
        }

        // Generar token unico
        $token = bin2hex(random_bytes(16));
        $this->userModel->deletePasswordResetToken($request['email']);
        $this->userModel->createPasswordResetToken($request['email'], $token);

        /*
        *  aquí simulamos el envío de un correo electrónico, pero para efectos de la prueba,
        *  guardamos el token en un archivo de texto que lleva como nombre el email del usuario.
        */
        file_put_contents(__DIR__ . '/../../emails/reset_' . $request['email'] . '.txt', $token);

        Response::json(200, 'Token de restablecimiento de contraseña enviado.');
    }

    /**
     * Restablecer contraseña.
     *
     * @param array $request Datos de la solicitud.
     */
    public function resetPassword($request)
    {
        if (!isset($request['token'], $request['password'])) {
            Response::json(400, 'Token y contraseña son requeridos.');
        }

        $reset = $this->userModel->getPasswordResetToken($request['token']);
        if (!$reset) {
            Response::json(404, 'Token no válido o expirado.');
        }

        $hasedPassword = password_hash($request['password'], PASSWORD_BCRYPT);
        $this->userModel->updatePasswordByEmail($reset['email'], $hasedPassword);
        $this->userModel->deletePasswordResetToken($reset['email']);

        Response::json(200, 'Contraseña restablecida con éxito.');
    }
}

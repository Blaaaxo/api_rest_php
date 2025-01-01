<?php

namespace Brayan\PruebaTecnica\Controllers;

use Brayan\PruebaTecnica\Helpers\Response;
use Brayan\PruebaTecnica\Helpers\Validator;
use Brayan\PruebaTecnica\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $request Información del usuario. Debe contener las claves 'full_name', 'email', 'password', etc.
     * @return array Respuesta con el estado y el mensaje del resultado de la operación.
     */
    public function register($request)
    {

        // validamos los datos
        if (!isset($request['full_name'], $request['email'], $request['password'], $request['phone_number'])) {

            // retornamos un mensaje de error en caso de faltar algún campo
            Response::json(400, 'Todos los campos son requeridos');
        }

        // verificamos si el correo es valido
        if (!Validator::validateEmail($request['email'])) {
            Response::json(400, 'Correo electrónico no válido');
        }

        // la contraseña debe tener al menos 8 caracteres
        if (!Validator::validatePassword($request['password'])) {
            Response::json(400, 'La contraseña debe tener al menos 8 caracteres');
        }

        // verificamos si el correo existe
        if ($this->userModel->getUserByEmail($request['email'])) {
            Response::json(400, 'El correo electrónico ya está en uso');
        }

        // encriptamos la contraseña
        $request['password'] = password_hash($request['password'], PASSWORD_BCRYPT);

        // registramos al usuario
        if ($this->userModel->createUser($request)) {
            Response::json(201, 'Usuario registrado correctamente');
        }

        Response::json(500, 'Ha ocurrido un error al registrar el usuario');
    }


    /**
     * Inicia sesión en la aplicación.
     *
     * @param array $request Información del usuario. Debe contener las claves 'email' y 'password'.
     * @return array Respuesta con el estado y el mensaje del resultado de la operación.
     */
    public function login($request)
    {

        // validamos los datos
        if (!isset($request['email'], $request['password'])) {
            Response::json(400, 'Todos los campos son requeridos');
        }

        // obtenemos al usuario
        $user = $this->userModel->getUserByEmail($request['email']);

        // verificamos si las credenciales son correctas
        if (!$user || !password_verify($request['password'], $user['password'])) {
            Response::json(400, 'Credenciales incorrectas');
        }

        // generamos el token JWT
        $payload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + 3600 // 1 hora
        ];
        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return [
            'status' => 200,
            'message' => 'sesión iniciada correctamente.',
            'token' => $jwt
        ];
    }
}

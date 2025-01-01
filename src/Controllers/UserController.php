<?php

namespace Brayan\PruebaTecnica\Controllers;

use Brayan\PruebaTecnica\Helpers\Response;
use Brayan\PruebaTecnica\Helpers\Validator;
use Brayan\PruebaTecnica\Models\User;

/**
 * Controlador para la gestión de usuarios.
 */
class UserController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Obtiene todos los usuarios de la base de datos.
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsers()
    {
        // obtener los parametros de consulta
        $filters = [];

        if (isset($_GET['name'])) {
            $filters['name'] = $_GET['name'];
        }

        if (isset($_GET['email'])) {
            $filters['email'] = $_GET['email'];
        }

        if (isset($_GET['role'])) {
            $filters['role'] = $_GET['role'];

            // validar el rol
            if (!Validator::validateRole($filters['role'])) {
                Response::json(400, 'el rol debe ser Admin o Usuario.');
            }
        }

        // paginacion
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // obtener los usuarios
        $users = $this->userModel->getAllUsers($filters, $limit, $offset);

        Response::json(200, null, $users);
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array Datos del usuario.
     */
    public function getUserById($id)
    {
        $user = $this->userModel->getUserById($id);

        if ($user) {
            Response::json(200, null, $user);
        }

        Response::json(404, 'Usuario no encontrado');
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $request Información del usuario. Debe contener las claves 'full_name', 'email', 'password', etc.
     * @return array Respuesta con el estado y el mensaje del resultado de la operación.
     */
    public function createUser($request)
    {
        if (!Validator::validateEmail($request['email'])) {
            Response::json(400, 'Correo electrónico no válido');
        }

        if (!Validator::validatePassword($request['password'])) {
            Response::json(400, 'La contraseña debe tener al menos 8 caracteres');
        }

        if (!Validator::validatePhoneNumber($request['phone_number'])) {
            Response::json(400, 'Número de teléfono no válido');
        }

        if (!Validator::validateRole($request['role'])) {
            Response::json(400, 'Rol no válido');
        }

        $request['password'] = password_hash($request['password'], PASSWORD_BCRYPT);

        $result = $this->userModel->createUserWhitRole($request);

        if ($result) {
            Response::json(201, 'Usuario creado correctamente');
        }

        Response::json(500, 'Error al crear el usuario');
    }

    /**
     * Actualiza un usuario en la base de datos.
     *
     * @param int $id ID del usuario.
     * @param array $request Información del usuario. Debe contener las claves 'full_name', 'email', 'password', etc.
     * @return array Respuesta con el estado y el mensaje del resultado de la operación.
     */
    public function updateUser($id, $request)
    {
        // Validar si el usuario existe
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            Response::json(404, 'Usuario no encontrado');
        }

        // Validar datos proporcionados
        if (!isset($request['full_name'], $request['email'], $request['phone_number'], $request['role'])) {
            Response::json(400, 'Todos los campos son requeridos');
        }

        if (!Validator::validateEmail($request['email'])) {
            Response::json(400, 'Correo electrónico no válido');
        }

        // Actualizar usuario
        if ($this->userModel->updateUser($id, $request)) {
            Response::json(200, 'Usuario actualizado con éxito');
        }

        Response::json(500, 'Error al actualizar el usuario');
    }

    /**
     * Elimina un usuario de la base de datos.
     *
     * @param int $id ID del usuario.
     * @return array Respuesta con el estado y el mensaje del resultado de la operación.
     */
    public function deleteUser($id)
    {
        // Validar si el usuario existe
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            Response::json(404, 'Usuario no encontrado');
        }

        // Eliminar usuario
        if ($this->userModel->deleteUser($id)) {
            Response::json(200, 'Usuario eliminado con éxito');
        }

        Response::json(500, 'Error al eliminar el usuario');
    }
}

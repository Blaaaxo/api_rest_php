<?php

namespace Brayan\PruebaTecnica\Models;

use Brayan\PruebaTecnica\Helpers\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }


    /**
     * Obtiene todos los usuarios de la base de datos.
     *
     * @param array $filters Filtros para la búsqueda de usuarios.
     * @param int $limit Cantidad de registros a obtener.
     * @param int $offset Registro desde el cual se obtendrán los datos.
     * @return array Lista de usuarios.
     */
    public function getAllUsers($filters = [], $limit = 10, $offset = 0)
    {
        $sql = "SELECT id, full_name, email, phone_number, role, created_at FROM users";
        $params = [];

        // Aplicar filtros
        if (!empty($filters)) {
            $sql .= " WHERE ";
            $conditions = [];

            if (isset($filters['name'])) {
                $conditions[] = "full_name LIKE :name";
                $params[':name'] = "%" . $filters['name'] . "%";
            }
            if (isset($filters['email'])) {
                $conditions[] = "email LIKE :email";
                $params[':email'] = "%" . $filters['email'] . "%";
            }
            if (isset($filters['role'])) {
                $conditions[] = "role = :role";
                $params[':role'] = $filters['role'];
            }

            $sql .= implode(" AND ", $conditions);
        }

        // Paginación
        $sql .= " LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);

        // Enlazar los parámetros de los filtros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array Datos del usuario.
     */
    public function getUserById($id)
    {
        $sql = "SELECT id, full_name, email, phone_number, role, created_at FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $data Información del usuario. Debe contener las claves 'full_name', 'email', 'password' y 'phone_number'.
     * @return bool Resultado de la operación.
     */
    public function createUser($data)
    {
        $sql = "INSERT INTO  users (full_name, email, password, phone_number) VALUES (:full_name, :email, :password, :phone_number)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']); // debe estar la contraseña encriptada
        $stmt->bindParam(':phone_number', $data['phone_number']);

        return $stmt->execute();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $data Información del usuario. Debe contener las claves 'full_name', 'email', 'password', 'phone_number' y 'role'.
     * @return bool Resultado de la operación.
     */
    public function createUserWhitRole($data)
    {
        $sql = "INSERT INTO  users (full_name, email, password, phone_number, role) VALUES (:full_name, :email, :password, :phone_number, :role)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']); // debe estar la contraseña encriptada
        $stmt->bindParam(':phone_number', $data['phone_number']);
        $stmt->bindParam(':role', $data['role']);

        return $stmt->execute();
    }

    /**
     * Obtiene un usuario por su correo electrónico.
     *
     * @param string $email Correo electrónico del usuario.
     * @return array Datos del usuario.
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza un usuario en la base de datos.
     *
     * @param int $id ID del usuario.
     * @param array $data Información del usuario a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateUser($id, $data)
    {
        $sql = "UPDATE users SET full_name = :full_name, email = :email, phone_number = :phone_number, role = :role WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':full_name', $data['full_name'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $data['phone_number'], PDO::PARAM_STR);
        $stmt->bindValue(':role', $data['role'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Elimina un usuario de la base de datos.
     *
     * @param int $id ID del usuario.
     * @return bool Resultado de la operación.
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Crea un token para restablecer la contraseña de un usuario.
     *
     * @param string $email Correo electrónico del usuario.
     * @param string $token Token de restablecimiento de contraseña.
     * @return bool Resultado de la operación.
     */
    public function createPasswordResetToken($email, $token)
    {
        $sql = "INSERT INTO password_resets (email, token) VALUES (:email, :token)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);

        return $stmt->execute();
    }

    /**
     * Obtiene un token de restablecimiento de contraseña.
     *
     * @param string $token Token de restablecimiento de contraseña.
     * @return array Datos del token.
     */
    public function getPasswordResetToken($token)
    {
        $sql = "SELECT * FROM password_resets WHERE token = :token AND created_at >= NOW() - INTERVAL 1 HOUR";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina un token de restablecimiento de contraseña.
     *
     * @param string $email Correo electrónico del usuario.
     * @return bool Resultado de la operación.
     */
    public function deletePasswordResetToken($email)
    {
        $sql = "DELETE FROM password_resets WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param string $email Correo electrónico del usuario.
     * @param string $password Nueva contraseña.
     * @return bool Resultado de la operación.
     */
    public function updatePasswordByEmail($email, $password)
    {
        $sql = "UPDATE users SET password = :password WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }
}

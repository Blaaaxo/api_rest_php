<?php
namespace Brayan\PruebaTecnica\Helpers;

use Brayan\PruebaTecnica\Models\Role;

class Validator
{
    /**
     * Valida un email.
     *
     * @param string $email Email a validar.
     * @return bool True si el email es válido, false en caso contrario.
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valida una contraseña.
     *
     * @param string $password Contraseña a validar.
     * @return bool True si la contraseña es válida, false en caso contrario.
     */
    public static function validatePassword($password)
    {
        return strlen($password) >= 8;
    }

    /**
     * Valida un número de teléfono.
     *
     * @param string $phone Número de teléfono a validar.
     * @return bool True si el número de teléfono es válido, false en caso contrario.
     */
    public static function validatePhoneNumber($phone) 
    {
        return is_numeric($phone) && strlen($phone) === 7;
    }

    /**
     * Valida un rol.
     *
     * @param string $role Rol a validar.
     * @return bool True si el rol es válido, false en caso contrario.
     */
    public static function validateRole($role)
    {
        $reflection = new \ReflectionClass(Role::class);
        $roles = $reflection->getConstants();

        return in_array($role, $roles);
    }
}
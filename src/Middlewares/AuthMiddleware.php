<?php
namespace Brayan\PruebaTecnica\Middlewares; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    /**
     * Verifica si el token proporcionado es válido.
     *
     * @param string $requiredRole Rol requerido para acceder al recurso.
     * @return object Datos del token decodificado.
     */
    public static function verifyToken($requiredRole = null) 
    {
        // obtenemos el token de la cabecera
        $headers = getallheaders();

        // verificamos si se ha proporcionado un token
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode([
                'status' => 401,
                'message' => 'no se ha proporcionado un token.'
            ]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // decodificamos el token
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            
            // verificar el rol si es necesario
            if ($requiredRole && $decoded->role !== $requiredRole) {
                http_response_code(403);
                echo json_encode([
                    'status' => 403,
                    'message' => 'no tienes permisos para acceder a este recurso.'
                ]);
                exit;
            }

            // retornamos los datos del token
            return $decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode([
                'status' => 401,
                'message' => 'token inválido o expirado.'
            ]);
            exit;
        }
    }
}
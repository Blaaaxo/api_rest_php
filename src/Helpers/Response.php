<?php

namespace Brayan\PruebaTecnica\Helpers;

class Response
{
    /**
     * Devuelve una respuesta en formato JSON.
     *
     * @param int $status Código de estado HTTP.
     * @param string $message Mensaje de la respuesta.
     * @param mixed $data Datos de la respuesta.
     */
    public static function json($status, $message = null , $data = null)
    {
        http_response_code($status);

        $response = [
            'status' => $status,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response);
        exit;
    }
}

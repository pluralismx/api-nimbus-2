<?php
class Cors {
    public function headers() {
        // Permitir solicitudes desde el dominio permitido
        header("Access-Control-Allow-Origin: http://localhost:8080");

        // Métodos permitidos
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        // Encabezados permitidos
        header("Access-Control-Allow-Headers: Content-Type");

        // Permitir el intercambio de cookies
        header("Access-Control-Allow-Credentials: true");

        // Si la solicitud es una opción (por ejemplo, una pre-solicitud CORS),
        // responde con una confirmación exitosa (200 OK) y termina la ejecución del script.
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}

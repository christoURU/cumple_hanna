<?php
define('RUTA_CARPETA_DESTINO', __DIR__ . '/uploads/');
session_start();

$ruta = $_SERVER['REQUEST_URI']; 
$metodo = $_SERVER['REQUEST_METHOD']; 
switch ($ruta) {
    case '/': // Página principal (formulario de subida)
    case '/index.php': 
        if ($metodo === 'GET') {
            require 'upload_form.php'; 
        } else {
            // Método no permitido en la raíz (solo GET)
            http_response_code(405);
            echo 'Método no permitido.';
        }
        break;

    case '/procesar_subida':
        if ($metodo === 'POST') {
            require 'process_upload.php'; 
        } else {
            http_response_code(405); // Method Not Allowed
            echo 'Método no permitido para esta ruta.';
        }
        break;
    default:
        http_response_code(404); // Not Found
        echo 'Página no encontrada.';
        break;
}
?>


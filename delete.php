<?php
session_start(); // Iniciar sesión para mostrar mensajes

// Definir la carpeta donde se almacenan los archivos
define('UPLOADS_DIR', __DIR__ . '/uploads/');

// Verificar si se ha pasado un archivo por GET
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $archivo = urldecode($_GET['file']); // Decodificar el nombre del archivo
    $rutaArchivo = UPLOADS_DIR . $archivo; // Ruta completa del archivo

    // Validar que el archivo existe antes de intentar eliminarlo
    if (file_exists($rutaArchivo) && is_file($rutaArchivo)) {
        if (unlink($rutaArchivo)) {
            $_SESSION['mensaje_exito'] = "✅ Archivo '{$archivo}' eliminado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje_exito'] = "❌ Error al eliminar el archivo '{$archivo}'.";
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje_exito'] = "❌ El archivo '{$archivo}' no existe.";
        $_SESSION['tipo_mensaje'] = "error";
    }
} else {
    $_SESSION['mensaje_exito'] = "❌ No se proporcionó un archivo válido.";
    $_SESSION['tipo_mensaje'] = "error";
}

// Redirigir de vuelta a `index.php`
header("Location: index.php");
exit;
?>

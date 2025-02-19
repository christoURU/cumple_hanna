<?php
session_start(); // Agregar esta línea al inicio

define('RUTA_CARPETA_DESTINO', __DIR__ . '/uploads/');

if (!is_dir(RUTA_CARPETA_DESTINO)) {
    if (!mkdir(RUTA_CARPETA_DESTINO, 0755, true)) {
        $_SESSION['mensaje_exito'] = "Error al crear la carpeta de destino.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo'])) {
    $mensajes = [];
    $huboErrores = false;

    $archivosSubidos = $_FILES['archivo'];
    $totalArchivos = count($archivosSubidos['name']);

    for ($i = 0; $i < $totalArchivos; $i++) {
        if ($archivosSubidos['error'][$i] === UPLOAD_ERR_OK) {
            $nombreArchivoOriginal = $archivosSubidos['name'][$i];
            $nombreArchivoTemporal = $archivosSubidos['tmp_name'][$i];
            $extension = strtolower(pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION));
            $nombreArchivoUnico = uniqid('archivo_') . '.' . $extension;
            $rutaDestinoArchivo = RUTA_CARPETA_DESTINO . $nombreArchivoUnico;

            if (move_uploaded_file($nombreArchivoTemporal, $rutaDestinoArchivo)) {
                $mensajes[] = "✅ '{$nombreArchivoOriginal}' subido correctamente.";
            } else {
                $mensajes[] = "❌ Error al subir '{$nombreArchivoOriginal}'.";
                $huboErrores = true;
            }
        } else {
            $mensajes[] = "❌ Error al procesar '{$archivosSubidos['name'][$i]}'.";
            $huboErrores = true;
        }
    }

    // Guardar mensaje en la sesión
    $_SESSION['mensaje_exito'] = implode("<br>", $mensajes);
    $_SESSION['tipo_mensaje'] = $huboErrores ? "error" : "success";

    header("Location: index.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
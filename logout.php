<?php
session_start(); // Iniciar la sesión si no está iniciada

// Destruir la sesión
$_SESSION = []; // Vaciar el array de sesión
session_unset(); // Liberar variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir al index
header("Location: index.php");
exit;
?>

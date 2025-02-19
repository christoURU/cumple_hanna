<?php
session_start();
$username = "root";
$password = "S@randi319";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST["username"];
    $pass = $_POST["password"];

    if ($username = $user && $password = $pass) {
        $_SESSION["user"] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form { display: inline-block; padding: 20px; border: 1px solid #ccc; margin-top: 50px; }
    </style>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required><br><br>
        <input type="password" name="password" placeholder="Contraseña" required><br><br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>

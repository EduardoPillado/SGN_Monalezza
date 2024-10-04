<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Monalezza Pizzeria - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="justify-content: center; align-items: center; display: flex; height: 100vh; margin: 0;">
    <div class="login-container">
        <img src="img/logo_lamonalezza.webp" alt="La Monalezza Pizzeria" class="logo">
        <form action="login.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];

        // Aquí iría la lógica de autenticación
    }
    ?>
</body>

</html>
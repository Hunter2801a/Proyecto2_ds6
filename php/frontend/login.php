<?php
session_start();
include '../backend/conexion.php'; // Archivo que conecta a la base de datos

// Redirigir a la página de usuario correspondiente si ya ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: pag_adm.php");
    } else {
        header("Location: pag_user.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../../image/logo2.png" alt="Logo Epsilon" style="width:80px;display:block;margin:0 auto 10px auto;">
            <h1 class="login-title">Tecno Y</h1>
            <div class="admin-info">
                <strong>Usuario admin de prueba:</strong> <code>admin</code> | <strong>Contraseña:</strong> <code>Admin123</code>
            </div>
        </div>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="usuario" class="form-input" placeholder="ID o Correo" required>
            </div>
            <div class="form-group">
                <input type="password" name="contraseña" class="form-input" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="login-button">Iniciar Sesión</button>
        </form>
        <?php
        // Procesar el inicio de sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $contraseña = $_POST['contraseña'];

            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nomb_user = ? AND contraseña = ?");
            $stmt->bind_param("ss", $usuario, $contraseña);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {
                $user = $resultado->fetch_assoc();
                $_SESSION['usuario'] = $user['nomb_user'];
                $_SESSION['rol'] = $user['rol'];

                if ($user['rol'] === 'admin') {
                    header("Location: pag_adm.php");
                } else {
                    header("Location: pag_user.php");
                }
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        }
        ?>
        <?php
        // Mensaje de error si lo hay
        if (isset($error)) {
            echo '<div class="error-message">' . $error . '</div>';
        }
        ?>
    </div>
    <a href="logout.php" class="logout-link">Cerrar sesión</a>
</body>
</html>

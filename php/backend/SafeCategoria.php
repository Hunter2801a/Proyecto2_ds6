<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../frontend/login.php");
    exit;
}
include 'conexion.php';

// Validar datos recibidos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $imagen = null;

    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['imagen']['tmp_name'];
        $imgName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $rutaRelativa = "image/img_categorias/" . $imgName; // Ruta que se guarda en la BDD
        $destino = "../../" . $rutaRelativa; // Ruta física para mover el archivo

        if (move_uploaded_file($imgTmp, $destino)) {
            $imagen = $rutaRelativa;
        }
    }

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, imagen) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $imagen);
    $stmt->execute();
    $stmt->close();

    // Redirigir al panel de admin
    header("Location: ../frontend/pag_adm.php");
    exit;
} else {
    // Si no hay datos, redirigir de vuelta al formulario
    header("Location: ../frontend/categorias.php");
    exit;
}
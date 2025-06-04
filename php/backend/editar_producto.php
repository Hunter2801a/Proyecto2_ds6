<?php
// filepath: c:\xampp\htdocs\Proy2DS6\php\frontend\editar_producto.php
include '../backend/conexion.php';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$imagen = null;

// Procesar imagen si se subió
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imgTmp = $_FILES['imagen']['tmp_name'];
    $imgName = uniqid() . "_" . basename($_FILES['imagen']['name']);
    $carpeta = "../../image/img_productos/";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $destino = $carpeta . $imgName;
    if (move_uploaded_file($imgTmp, $destino)) {
        $imagen = "image/img_productos/" . $imgName;
    }
}

if ($imagen) {
    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, imagen=? WHERE id=?");
    $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $id);
} else {
    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=? WHERE id=?");
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id);
}
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
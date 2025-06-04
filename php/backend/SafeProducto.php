<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? 0;
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['imagen']['tmp_name'];
        $imgName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $carpeta = "../image/img_productos/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $destino = $carpeta . $imgName;
        if (move_uploaded_file($imgTmp, $destino)) {
            $imagen = "image/img_productos/" . $imgName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, categoria_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $categoria_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["success" => true, "message" => "Producto guardado correctamente."]);
}
?>

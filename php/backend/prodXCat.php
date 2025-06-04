<?php
include 'conexion.php';

$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;
$productos = [];

if ($categoria_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria_id = ?");
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $stmt->close();
}

echo json_encode($productos);
?>
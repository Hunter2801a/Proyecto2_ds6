<?php
include 'conexion.php';

$sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
$resultado = $conn->query($sql);

$categorias = [];
while ($row = $resultado->fetch_assoc()) {
    $categorias[] = $row;
}

header('Content-Type: application/json');
echo json_encode($categorias);
?>

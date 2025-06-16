<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');

//este backend es de uso exclusivo para la app movil, no se usa en el frontend
//un ultimo recuerdo a mi buen perro Mufasa (DEP) 2015-2025

include 'conexion.php';

$sql = "SELECT id, nombre, imagen FROM categorias ORDER BY nombre ASC";
$resultado = $conn->query($sql);

$categorias = [];

while ($row = $resultado->fetch_assoc()) {
    $categorias[] = [
        'id' => $row['id'],
        'nombre' => $row['nombre'],
        'imagen' => $row['imagen']
    ];
}

echo json_encode($categorias);
?>
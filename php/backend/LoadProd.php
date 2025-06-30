<?php
include 'conexion.php';

// Obtener categorías
$categorias = $conn->query("SELECT * FROM categorias");
$resultado = [];

foreach ($categorias as $cat) {
    $catId = $cat['id'];
    $productos = [];
    $queryProd = $conn->query("SELECT * FROM productos WHERE categoria_id = $catId");
    foreach ($queryProd as $prod) {
        $productos[] = [
            'id' => $prod['id'],
            'nombre' => $prod['nombre'],
            'descripcion' => $prod['descripcion'],
            'precio' => $prod['precio'],
            'imagen' => $prod['imagen'],
            'stock' => $prod['stock']
        ];
    }
    $resultado[] = [
        'id' => $cat['id'],
        'nombre' => $cat['nombre'],
        'imagen' => $cat['imagen'],
        'productos' => $productos
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);
<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'd62025'; // Usuario por defecto en XAMPP
$password = 'admin123'; // Contraseña por defecto en XAMPP
$dbname = 'proy2'; // Nueva base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

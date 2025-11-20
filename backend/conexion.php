<?php
// backend/conexion.php
$servername = "localhost";
$username = "root";
$password = ""; // Si tu root tiene contraseña, cámbiala aquí
$dbname = "tienda_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}
?>

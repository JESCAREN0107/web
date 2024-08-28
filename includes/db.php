<?php
$servername = "localhost";
$username = "root"; // Cambia a tu nombre de usuario de MySQL si es necesario
$password = ""; // Cambia a tu contraseña de MySQL si es necesario
$dbname = "ticket_system";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

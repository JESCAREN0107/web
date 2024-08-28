<?php
// includes/functions.php


function createTicket($conn, $data) {
    $sql = "INSERT INTO tickets (id_usuario, nombre_usuario, nombre_solicitante, direccion_solicitante, tipo_telefono, telefono_solicitante, tipo_peticion, tipo_solicitante, direccion_problematica, problematica, colonia, nombre_departamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Bind parameters and execute
}
?>



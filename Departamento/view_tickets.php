<?php
include '../includes/db.php';
include '../includes/functions.php';

$tickets = getTickets($conn);

echo "<h1>Tickets</h1>";
echo "<table border='1'>
<tr>
<th>ID</th>
<th>Nombre Solicitante</th>
<th>Edad</th>
<th>Dirección Solicitante</th>
<th>Tipo Teléfono</th>
<th>Teléfono</th>
<th>Tipo Petición</th>
<th>Tipo Solicitante</th>
<th>Dirección Problemática</th>
<th>Problema</th>
<th>Colonia</th>
<th>Departamento</th>
<th>Fecha Reporte</th>
<th>Estado</th>
</tr>";

while ($row = $tickets->fetch_assoc()) {
    echo "<tr>
    <td>{$row['id_ticket']}</td>
    <td>{$row['nombre_solicitante']}</td>
    <td>{$row['edad']}</td>
    <td>{$row['direccion_solicitante']}</td>
    <td>{$row['tipo_telefono']}</td>
    <td>{$row['telefono_solicitante']}</td>
    <td>{$row['tipo_peticion']}</td>
    <td>{$row['tipo_solicitante']}</td>
    <td>{$row['direccion_problematica']}</td>
    <td>{$row['problematica']}</td>
    <td>{$row['colonia']}</td>
    <td>{$row['id_departamento']}</td>
    <td>{$row['fecha_reporte']}</td>
    <td>{$row['estatus']}</td>
    </tr>";
}

echo "</table>";
?>

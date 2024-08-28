<?php
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = $_POST['id_ticket'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE tickets SET estatus = ? WHERE id_ticket = ?");
    $stmt->bind_param("si", $estado, $ticket_id);
    $stmt->execute();
    $stmt->close();

    echo "Ticket actualizado exitosamente.";
}

// Obtener todos los tickets para que la secretaria pueda seleccionar uno
$tickets = getTickets($conn);

echo "<h1>Actualizar Ticket</h1>";
echo "<form method='post'>";
echo "<select name='id_ticket'>";
while ($row = $tickets->fetch_assoc()) {
    echo "<option value='{$row['id_ticket']}'>{$row['id_ticket']} - {$row['nombre_solicitante']}</option>";
}
echo "</select>";
echo "<select name='estado'>
        <option value='Pendiente'>Pendiente</option>
        <option value='En Progreso'>En Progreso</option>
        <option value='Resuelto'>Resuelto</option>
      </select>";
echo "<button type='submit'>Actualizar Estado</button>";
echo "</form>";
?>

<?php
// Incluye el archivo de conexión a la base de datos
include '../includes/db.php';

// Inicializa la variable de mensaje
$message = '';

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y sanitiza el ID del ticket y el ID del departamento del formulario
    $id_ticket = filter_var($_POST['id_ticket'], FILTER_SANITIZE_NUMBER_INT);
    $id_departamento = filter_var($_POST['id_departamento'], FILTER_SANITIZE_NUMBER_INT);

    // Inicia una transacción
    mysqli_begin_transaction($conn);
    
    try {
        // Prepara y ejecuta el procedimiento almacenado para asignar el ticket
        $query = "CALL AsignarTicket(?, ?)";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $id_ticket, $id_departamento);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Confirma la transacción
            mysqli_commit($conn);
            $message = "<div class='alert alert-success'>Ticket asignado con éxito.</div>";
        } else {
            throw new Exception("Error en la preparación de la consulta: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollback($conn);
        $message = "<div class='alert alert-danger'>Error al asignar el ticket: " . $e->getMessage() . "</div>";
    }
}

// Parámetros para la paginación
$limit = 10; // Número de tickets por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Parámetros para la búsqueda
$search = isset($_GET['search']) ? '%' . filter_var($_GET['search'], FILTER_SANITIZE_STRING) . '%' : '%';

// Consulta con búsqueda y paginación con JOIN
$tickets_query = "
    SELECT 
        t.id, 
        t.nombre_solicitante, 
        t.direccion_solicitante, 
        t.tipo_telefono, 
        t.telefono, 
        t.fecha_reporte, 
        t.direccion_problematica, 
        t.problematica, 
        t.colonia,
        p.tipo_peticion AS nombre_peticion,  -- Usar el nombre correcto del campo en `peticiones`
        d.nombre AS nombre_departamento
    FROM tickets t
    LEFT JOIN peticiones p ON t.id_tipo_peticion = p.id  -- Cambiar `id_peticion` a `id_tipo_peticion`
    LEFT JOIN departamentos d ON t.id_departamento = d.id
    WHERE t.id_departamento IS NULL
    AND (t.nombre_solicitante LIKE ? OR t.direccion_problematica LIKE ?)
    LIMIT ? OFFSET ?
";
$tickets_stmt = mysqli_prepare($conn, $tickets_query);

// Verificar si la preparación de la consulta fue exitosa
if ($tickets_stmt) {
    mysqli_stmt_bind_param($tickets_stmt, 'ssii', $search, $search, $limit, $offset);
    mysqli_stmt_execute($tickets_stmt);
    $tickets_result = mysqli_stmt_get_result($tickets_stmt);
} else {
    die("Error en la preparación de la consulta: " . mysqli_error($conn));
}

// Contar el número total de tickets para la paginación
$count_query = "
    SELECT COUNT(*) as total
    FROM tickets t
    LEFT JOIN peticiones p ON t.id_tipo_peticion = p.id  -- Cambiar `id_peticion` a `id_tipo_peticion`
    WHERE t.id_departamento IS NULL
    AND (t.nombre_solicitante LIKE ? OR t.direccion_problematica LIKE ?)
";
$count_stmt = mysqli_prepare($conn, $count_query);

// Verificar si la preparación de la consulta fue exitosa
if ($count_stmt) {
    mysqli_stmt_bind_param($count_stmt, 'ss', $search, $search);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $total_tickets = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_tickets / $limit);
} else {
    die("Error en la preparación de la consulta: " . mysqli_error($conn));
}

// Consulta para obtener los departamentos
$departments_result = mysqli_query($conn, "SELECT id, nombre FROM departamentos");
$departments = [];
while ($row = mysqli_fetch_assoc($departments_result)) {
    $departments[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-bottom: 1px solid #0056b3;
            font-weight: bold;
            border-radius: 15px 15px 0 0;
        }
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table thead th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .table tbody tr:hover {
            background-color: #e2e6ea;
        }
        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
        }
        .table td {
            text-align: center;
        }
        .form-select {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004494;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        .pagination {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Asignar Tickets Pendientes</h1>

        <!-- Mostrar mensaje si está disponible -->
        <?php if (!empty($message)) echo $message; ?>

        <!-- Barra de búsqueda -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar tickets..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        <!-- Card para el formulario -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-ticket-alt me-1"></i>
                Asignar Ticket
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Solicitante</th>
                            <th>Dirección Solicitante</th>
                            <th>Tipo Teléfono</th>
                            <th>Teléfono</th>
                            <th>Fecha Reporte</th>
                            <th>Dirección Problemática</th>
                            <th>Problema</th>
                            <th>Colonia</th>
                            <th>Nombre Petición</th>
                            <th>Asignar a Departamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['nombre_solicitante'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['direccion_solicitante'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['tipo_telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['fecha_reporte'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['direccion_problematica'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['problematica'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['colonia'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['nombre_peticion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="id_ticket" value="<?php echo htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <select class="form-select" name="id_departamento" required>
                                            <option value="">Seleccionar Departamento</option>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?php echo htmlspecialchars($department['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars($department['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary mt-2">Asignar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Paginación -->
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

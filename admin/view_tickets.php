<?php
// Incluye el archivo de conexión a la base de datos
include '../includes/db.php';

// Construye la base de consulta con JOINs para obtener datos de departamentos, peticiones, y estatus
$query = "
    SELECT 
        t.id AS ticket_id, 
        t.nombre_solicitante, 
        t.direccion_solicitante, 
        t.tipo_telefono, 
        t.telefono, 
        t.tipo_solicitante, 
        t.fecha_reporte, 
        t.direccion_problematica, 
        t.problematica, 
        t.colonia, 
        d.nombre AS nombre_departamento, 
        e.nombre_estatus AS estatus_ticket, 
        p.tipo_peticion AS nombre_peticion 
    FROM tickets t 
    LEFT JOIN departamentos d ON t.id_departamento = d.id 
    LEFT JOIN estatus_tickets e ON t.estatus_ticket = e.nombre_estatus 
    LEFT JOIN peticiones p ON t.id_tipo_peticion = p.id 
    WHERE 1
";

// Aplica filtros si están presentes
$filters = [];
if (isset($_POST['nombre_solicitante']) && !empty($_POST['nombre_solicitante'])) {
    $nombre_solicitante = mysqli_real_escape_string($conn, $_POST['nombre_solicitante']);
    $filters[] = "t.nombre_solicitante LIKE '%$nombre_solicitante%'";
}
if (isset($_POST['colonia']) && !empty($_POST['colonia'])) {
    $colonia = mysqli_real_escape_string($conn, $_POST['colonia']);
    $filters[] = "t.colonia = '$colonia'";
}
if (isset($_POST['estatus_ticket']) && !empty($_POST['estatus_ticket'])) {
    $estatus_ticket = mysqli_real_escape_string($conn, $_POST['estatus_ticket']);
    $filters[] = "e.nombre_estatus = '$estatus_ticket'";
}
if (isset($_POST['departamento']) && !empty($_POST['departamento'])) {
    $departamento = mysqli_real_escape_string($conn, $_POST['departamento']);
    $filters[] = "d.nombre = '$departamento'";
}
if (isset($_POST['peticion']) && !empty($_POST['peticion'])) {
    $peticion = mysqli_real_escape_string($conn, $_POST['peticion']);
    $filters[] = "p.tipo_peticion = '$peticion'";
}
if (isset($_POST['tipo_solicitante']) && !empty($_POST['tipo_solicitante'])) {
    $tipo_solicitante = mysqli_real_escape_string($conn, $_POST['tipo_solicitante']);
    $filters[] = "t.tipo_solicitante = '$tipo_solicitante'";
}
if (isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio'])) {
    $fecha_inicio = mysqli_real_escape_string($conn, $_POST['fecha_inicio']);
    $filters[] = "t.fecha_reporte >= '$fecha_inicio'";
}
if (isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin'])) {
    $fecha_fin = mysqli_real_escape_string($conn, $_POST['fecha_fin']);
    $filters[] = "t.fecha_reporte <= '$fecha_fin'";
}

// Agrega los filtros a la consulta
if (count($filters) > 0) {
    $query .= " AND " . implode(" AND ", $filters);
}

$resultado = mysqli_query($conn, $query);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Obtener las opciones de colonias, departamentos, peticiones y tipo de solicitante desde la base de datos
$colonias_query = "SELECT nombre_colonia FROM colonias";
$colonias_resultado = mysqli_query($conn, $colonias_query);
if (!$colonias_resultado) {
    die("Error en la consulta de colonias: " . mysqli_error($conn));
}

$departamentos_query = "SELECT nombre FROM departamentos";
$departamentos_resultado = mysqli_query($conn, $departamentos_query);
if (!$departamentos_resultado) {
    die("Error en la consulta de departamentos: " . mysqli_error($conn));
}

$peticiones_query = "SELECT tipo_peticion FROM peticiones";
$peticiones_resultado = mysqli_query($conn, $peticiones_query);
if (!$peticiones_resultado) {
    die("Error en la consulta de peticiones: " . mysqli_error($conn));
}

$tipo_solicitante_query = "SELECT DISTINCT tipo_solicitante FROM tickets";
$tipo_solicitante_resultado = mysqli_query($conn, $tipo_solicitante_query);
if (!$tipo_solicitante_resultado) {
    die("Error en la consulta de tipo de solicitante: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
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
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:hover {
            background-color: #e2e6ea;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Lista de tickets</h1>
        <!-- Formulario de filtros -->
        <form method="POST" action="">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-filter me-1"></i> Filtrar tickets
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre_solicitante" class="form-label">Nombre del solicitante</label>
                            <input type="text" class="form-control" id="nombre_solicitante" name="nombre_solicitante" value="<?php echo isset($_POST['nombre_solicitante']) ? htmlspecialchars($_POST['nombre_solicitante']) : ''; ?>" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="colonia" class="form-label">Colonia</label>
                            <select class="form-select" id="colonia" name="colonia">
                                <option value="">--Seleccionar--</option>
                                <?php while ($colonia_row = mysqli_fetch_assoc($colonias_resultado)): ?>
                                    <option value="<?php echo htmlspecialchars($colonia_row['nombre_colonia']); ?>" <?php echo (isset($_POST['colonia']) && $_POST['colonia'] == $colonia_row['nombre_colonia']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($colonia_row['nombre_colonia']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="departamento" class="form-label">Departamento</label>
                            <select class="form-select" id="departamento" name="departamento">
                                <option value="">--Seleccionar--</option>
                                <?php while ($departamento_row = mysqli_fetch_assoc($departamentos_resultado)): ?>
                                    <option value="<?php echo htmlspecialchars($departamento_row['nombre']); ?>" <?php echo (isset($_POST['departamento']) && $_POST['departamento'] == $departamento_row['nombre']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($departamento_row['nombre']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="peticion" class="form-label">Petición</label>
                            <select class="form-select" id="peticion" name="peticion">
                                <option value="">--Seleccionar--</option>
                                <?php while ($peticion_row = mysqli_fetch_assoc($peticiones_resultado)): ?>
                                    <option value="<?php echo htmlspecialchars($peticion_row['tipo_peticion']); ?>" <?php echo (isset($_POST['peticion']) && $_POST['peticion'] == $peticion_row['tipo_peticion']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($peticion_row['tipo_peticion']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tipo_solicitante" class="form-label">Tipo de Solicitante</label>
                            <select class="form-select" id="tipo_solicitante" name="tipo_solicitante">
                                <option value="">--Seleccionar--</option>
                                <?php while ($tipo_solicitante_row = mysqli_fetch_assoc($tipo_solicitante_resultado)): ?>
                                    <option value="<?php echo htmlspecialchars($tipo_solicitante_row['tipo_solicitante']); ?>" <?php echo (isset($_POST['tipo_solicitante']) && $_POST['tipo_solicitante'] == $tipo_solicitante_row['tipo_solicitante']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo_solicitante_row['tipo_solicitante']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estatus_ticket" class="form-label">Estatus</label>
                            <select class="form-select" id="estatus_ticket" name="estatus_ticket">
                                <option value="">--Seleccionar--</option>
                                <option value="AUTORIZADO" <?php echo (isset($_POST['estatus_ticket']) && $_POST['estatus_ticket'] == 'AUTORIZADO') ? 'selected' : ''; ?>>AUTORIZADO</option>
                                <option value="DENEGADO" <?php echo (isset($_POST['estatus_ticket']) && $_POST['estatus_ticket'] == 'DENEGADO') ? 'selected' : ''; ?>>DENEGADO</option>
                                <option value="EN SERVICIO" <?php echo (isset($_POST['estatus_ticket']) && $_POST['estatus_ticket'] == 'EN SERVICIO') ? 'selected' : ''; ?>>EN SERVICIO</option>
                                <option value="TERMINADO" <?php echo (isset($_POST['estatus_ticket']) && $_POST['estatus_ticket'] == 'TERMINADO') ? 'selected' : ''; ?>>TERMINADO</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo isset($_POST['fecha_inicio']) ? htmlspecialchars($_POST['fecha_inicio']) : ''; ?>" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo isset($_POST['fecha_fin']) ? htmlspecialchars($_POST['fecha_fin']) : ''; ?>" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.href=window.location.href;">Limpiar Filtros</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Tickets
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Solicitante</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Tipo Solicitante</th>
                            <th>Fecha</th>
                            <th>Colonia</th>
                            <th>Problema</th>
                            <th>Departamento</th>
                            <th>Estatus</th>
                            <th>Petición</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_solicitante']); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion_solicitante']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_telefono']) . ' - ' . htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_solicitante']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_reporte']); ?></td>
                                <td><?php echo htmlspecialchars($row['colonia']); ?></td>
                                <td><?php echo htmlspecialchars($row['problematica']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_departamento']); ?></td>
                                <td><?php echo htmlspecialchars($row['estatus_ticket']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_peticion']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datatable = new simpleDatatables.DataTable("#example");
        });
    </script>
</body>
</html>

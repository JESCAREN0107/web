<?php
// Include database connection file
include('../includes/db.php');

// Initialize message and error variables
$mensaje = '';
$error = false;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $nombre_solicitante = $_POST['nombre_solicitante'];
    $direccion_solicitante = $_POST['direccion_solicitante'];
    $tipo_telefono = $_POST['tipo_telefono'];
    $telefono = $_POST['telefono'];
    $id_tipo_peticion = $_POST['id_tipo_peticion']; // Updated to use id_tipo_peticion
    $tipo_solicitante = $_POST['tipo_solicitante'];
    $direccion_problematica = $_POST['direccion_problematica'];
    $problematica = $_POST['problematica'];
    $colonia = $_POST['colonia'];
    $id_departamento = $_POST['id_departamento'];

    // Prepare SQL query
    $sql = "INSERT INTO tickets (nombre_solicitante, direccion_solicitante, tipo_telefono, telefono, id_tipo_peticion, tipo_solicitante, direccion_problematica, problematica, colonia, id_departamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Execute SQL query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssisssssi', $nombre_solicitante, $direccion_solicitante, $tipo_telefono, $telefono, $id_tipo_peticion, $tipo_solicitante, $direccion_problematica, $problematica, $colonia, $id_departamento);
        if ($stmt->execute()) {
            $mensaje = "Ticket creado con éxito.";
        } else {
            $mensaje = "Error al crear el ticket: " . $stmt->error;
            $error = true;
        }
        $stmt->close();
    } else {
        $mensaje = "Error en la preparación de la consulta: " . $conn->error;
        $error = true;
    }
}

// Fetch peticiones from the database
$peticiones = [];
$result = $conn->query("SELECT id, tipo_peticion FROM peticiones ORDER BY tipo_peticion");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $peticiones[] = $row;
    }
}

// Fetch departamentos from the database
$departamentos = [];
$result = $conn->query("SELECT id, nombre FROM departamentos ORDER BY nombre");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departamentos[] = $row;
    }
}

// Fetch colonias from the database
$colonias = [];
$result = $conn->query("SELECT nombre_colonia FROM colonias ORDER BY nombre_colonia");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $colonias[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Create Ticket - SB Admin</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Crear Ticket</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($mensaje): ?>
                                        <div class="alert alert-<?php echo $error ? 'danger' : 'success'; ?>" role="alert">
                                            <?php echo htmlspecialchars($mensaje); ?>
                                        </div>
                                    <?php endif; ?>
                                    <form action="create_ticket.php" method="post">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="text" class="form-control" id="nombre_solicitante" name="nombre_solicitante" placeholder="Nombre del Solicitante" required />
                                                    <label for="nombre_solicitante">Nombre del Solicitante</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="text" class="form-control" id="direccion_solicitante" name="direccion_solicitante" placeholder="Dirección del Solicitante" required />
                                                    <label for="direccion_solicitante">Dirección del Solicitante</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="tipo_telefono" name="tipo_telefono" required>
                                                <option value="" disabled selected>Selecciona un tipo de teléfono</option>
                                                <option value="CASA">Casa</option>
                                                <option value="CELULAR">Celular</option>
                                            </select>
                                            <label for="tipo_telefono">Tipo de Teléfono</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Número de teléfono" required />
                                            <label for="telefono">Teléfono</label>
                                        </div>
                                        <!-- Menú desplegable de Tipos de Peticiones -->
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="id_tipo_peticion" name="id_tipo_peticion" required>
                                                <option value="" disabled selected>Selecciona un tipo de petición</option>
                                                <?php foreach ($peticiones as $peticion): ?>
                                                    <option value="<?php echo htmlspecialchars($peticion['id']); ?>">
                                                        <?php echo htmlspecialchars($peticion['tipo_peticion']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="id_tipo_peticion">Tipo de Petición</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="tipo_solicitante" name="tipo_solicitante" required>
                                                <option value="" disabled selected>Selecciona un tipo de solicitante</option>
                                                <option value="CIUDADANIA">Ciudadanía</option>
                                                <option value="EJIDOS">Ejidos</option>
                                                <option value="ESCUELAS">Escuelas</option>
                                                <option value="IGLESIAS">Iglesias</option>
                                            </select>
                                            <label for="tipo_solicitante">Tipo de Solicitante</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="direccion_problematica" name="direccion_problematica" placeholder="Dirección de la Problemática" required />
                                            <label for="direccion_problematica">Dirección de la Problemática</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" id="problematica" name="problematica" rows="4" placeholder="Descripción de la Problemática" required></textarea>
                                            <label for="problematica">Descripción de la Problemática</label>
                                        </div>

                                        <!-- Menú desplegable de Colonias -->
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="colonia" name="colonia" required>
                                                <option value="" disabled selected>Selecciona una colonia</option>
                                                <?php foreach ($colonias as $colonia): ?>
                                                    <option value="<?php echo htmlspecialchars($colonia['nombre_colonia']); ?>">
                                                        <?php echo htmlspecialchars($colonia['nombre_colonia']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="colonia">Colonia</label>
                                        </div>

                                        <!-- Menú desplegable de Departamentos -->
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="id_departamento" name="id_departamento" required>
                                                <option value="" disabled selected>Selecciona un departamento</option>
                                                <?php foreach ($departamentos as $departamento): ?>
                                                    <option value="<?php echo htmlspecialchars($departamento['id']); ?>">
                                                        <?php echo htmlspecialchars($departamento['nombre']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="id_departamento">Departamento</label>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block">Crear Ticket</button>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.html">Volver al inicio</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>

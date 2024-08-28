<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username']; // Cambiado de 'username' a 'usuario'
    $contrasena = $_POST['password']; // Cambiado de 'password' a 'contrasena'
    $rol = $_POST['role']; // Cambiado de 'role' a 'rol'

    // Preparar y ejecutar consulta
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ? AND rol = ?");
    
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }
    
    $stmt->bind_param("sss", $usuario, $contrasena, $rol);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc(); // Obtener los datos del usuario

        // Almacenar datos en variables de sesión
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['telefono'] = $row['telefono'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['rol'];

        // Redirigir según el rol
        if ($rol == 'ADMINISTRADOR') {
            header("Location: admin/index.php");
        } elseif ($rol == 'SECRETARIA') {
            header("Location: secretaria/index.php");
        } elseif ($rol == 'EMPLEADO') {
            header("Location: empleado/index.php");
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
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
    <title>Login - SB Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Sistema de Enlaces Municipal</h3></div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Username" required />
                                            <label for="inputUsername">Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="inputRole" name="role" required>
                                                <option value="ADMINISTRADOR">Administrador</option>
                                                <option value="SECRETARIA">Secretaria</option>
                                                <option value="EMPLEADO">Empleado</option>
                                            </select>
                                            <label for="inputRole">Role</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">

    <div class="d-flex justify-content-center w-100"> <!-- Contenedor flex para centrar -->
        <button class="btn btn-primary" type="submit">Login</button>
    </div>
</div>

                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                   
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
    <script src="js/scripts.js"></script>
</body>
</html>


<?php
session_start();
include('../includes/db.php');

// Verificar si el usuario está autenticado y es ADMINISTRADOR
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'ADMINISTRADOR') {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Departamento', 'Cantidad'],
                <?php
                $sql = "SELECT Departamento, COUNT(*) as cantidad FROM tickets GROUP BY Departamento";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "['" . $row['Departamento'] . "', " . $row['cantidad'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Tickets por Departamento'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <h1>Estadísticas de Tickets</h1>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <?php include('../includes/footer.php'); ?>
</body>
</html>


<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "billar";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$filterType = 'mes'; // Filtro por defecto
$startDate = '';
$endDate = '';
$whereClause = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filterType'])) {
    $filterType = $_GET['filterType'];

    // Ajustar el rango de fechas basado en el filtro seleccionado
    switch ($filterType) {
        case 'dia':
            $startDate = date('Y-m-d');
            $endDate = $startDate;
            break;
        case 'semana':
            $startDate = date('Y-m-d', strtotime('-7 days'));
            $endDate = date('Y-m-d');
            break;
        case 'mes':
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            break;
        case 'año':
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
            break;
        case 'rango':
            $startDate = $_GET['startDate'] ?? '';
            $endDate = $_GET['endDate'] ?? '';
            break;
    }

    // Construir la cláusula WHERE
    if (!empty($startDate) && !empty($endDate)) {
        $whereClause = "WHERE fecha BETWEEN '$startDate' AND '$endDate'";
    }
}

// Consulta para obtener las estadísticas de las mesas
$sql = "SELECT mesa_numero, COUNT(*) as veces_jugadas FROM mesas $whereClause GROUP BY mesa_numero";
$result = $conn->query($sql);

$mesas = [];
$vecesJugadas = [];

while ($row = $result->fetch_assoc()) {
    $mesas[] = "Mesa " . $row['mesa_numero'];
    $vecesJugadas[] = $row['veces_jugadas'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Mesas</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="estadisticas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="nav">
        <div class="logo">
            <img class="img-logo" src="img/logo.png" alt="logo">
        </div>
        <div class="navegacion">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="estadisticas.php">Estadísticas</a></li>
                <li><a href="ganancias.php">Ganancias</a></li>
                <li><a href="analisis.php">Análisis de Ingresos</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 animate__animated animate__backInDown">
        <h1 class="text-center mb-4">Estadísticas de Mesas</h1>

        <!-- Formulario de filtros -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="filterType" class="form-label">Filtro:</label>
                    <select id="filterType" name="filterType" class="form-select">
                        <option value="dia" <?= $filterType === 'dia' ? 'selected' : '' ?>>Hoy</option>
                        <option value="semana" <?= $filterType === 'semana' ? 'selected' : '' ?>>Últimos 7 días</option>
                        <option value="mes" <?= $filterType === 'mes' ? 'selected' : '' ?>>Este mes</option>
                        <option value="año" <?= $filterType === 'año' ? 'selected' : '' ?>>Este año</option>
                        <option value="rango" <?= $filterType === 'rango' ? 'selected' : '' ?>>Rango personalizado</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="startDate" class="form-label">Fecha inicio:</label>
                    <input type="date" id="startDate" name="startDate" class="form-control" value="<?= $startDate ?>">
                </div>

                <div class="col-md-3">
                    <label for="endDate" class="form-label">Fecha fin:</label>
                    <input type="date" id="endDate" name="endDate" class="form-control" value="<?= $endDate ?>">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <canvas id="mesasChart" width="400" height="200"></canvas>
    </div>

    <script>
        // Datos de PHP para JavaScript
        const mesas = <?php echo json_encode($mesas); ?>;
        const vecesJugadas = <?php echo json_encode($vecesJugadas); ?>;

        // Configuración de la gráfica
        const ctx = document.getElementById('mesasChart').getContext('2d');
        const mesasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mesas,
                datasets: [{
                    label: 'Veces Jugadas',
                    data: vecesJugadas,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                    }
                }
            }
        });
    </script>
</body>
</html>

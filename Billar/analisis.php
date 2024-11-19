<?php
// Configuración de la conexión a la base de datos
$servername = "127.0.0.1";
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
$ganancias = 0;
$periodoSeleccionado = '';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periodoSeleccionado = $_POST['periodo'] ?? '';

    // Función para obtener las ganancias según el período seleccionado
    function obtenerGanancias($conn, $periodo) {
        $query = "";
        switch ($periodo) {
            case 'dia':
                $query = "SELECT SUM(precio_a_pagar) as total FROM mesas WHERE fecha = CURDATE()";
                break;
            case 'semana':
                $query = "SELECT SUM(precio_a_pagar) as total FROM mesas WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";
                break;
            case 'mes':
                $query = "SELECT SUM(precio_a_pagar) as total FROM mesas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
                break;
            case 'anio':
                $query = "SELECT SUM(precio_a_pagar) as total FROM mesas WHERE YEAR(fecha) = YEAR(CURDATE())";
                break;
            default:
                return 0; // Si no se selecciona un período válido, devolver 0
        }

        $resultado = $conn->query($query);
        return $resultado->fetch_assoc()['total'] ?? 0;
    }

    // Obtener las ganancias para el período seleccionado
    $ganancias = obtenerGanancias($conn, $periodoSeleccionado);
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Billar</title>
    <link rel="stylesheet" href="analisi.css">
    <link rel="stylesheet" href="Billar.css">
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
    <div class="container">
        <h1>Consulta de Ganancias</h1>
        <div class="form-container">
            <form method="POST">
                <label for="periodo">Selecciona el período:</label><br>
                <select name="periodo" id="periodo" required>
                    <option value="">-- Selecciona --</option>
                    <option value="dia" <?= $periodoSeleccionado === 'dia' ? 'selected' : '' ?>>Día</option>
                    <option value="semana" <?= $periodoSeleccionado === 'semana' ? 'selected' : '' ?>>Semana</option>
                    <option value="mes" <?= $periodoSeleccionado === 'mes' ? 'selected' : '' ?>>Mes</option>
                    <option value="anio" <?= $periodoSeleccionado === 'anio' ? 'selected' : '' ?>>Año</option>
                </select><br>
                <button type="submit">Consultar</button>
            </form>
        </div>
        <?php if ($periodoSeleccionado): ?>
            <div class="ganancias">
                <p>Ganancias para el período seleccionado (<strong><?= ucfirst($periodoSeleccionado) ?></strong>):</p>
                <p><strong>$<?= number_format($ganancias, 2) ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

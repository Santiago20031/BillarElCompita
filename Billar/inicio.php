<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Billar</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="Billar.css">
</head>
<body class="main">
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
        <div id="mensaje" style=" background-color: green; border-radius:3px; text-align:center; color: white;" ></div>
        <h1>BILLAR EL COMPITA</h1>
        <table id="billarTable">
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Hora de Inicio</th>
                    <th>Hora Final</th>
                    <th>Total Horas Jugadas</th>
                    <th>Precio por Hora</th>
                    <th>Precio a Pagar</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <tr>
                    <td data-label="Mesa">Mesa <?= $i ?></td>
                    <td data-label="Hora de Inicio"><input type="time" name="horaInicio<?= $i ?>" id="horaInicio<?= $i ?>"></td>
                    <td data-label="Hora Final"><input type="time" name="horaFinal<?= $i ?>" id="horaFinal<?= $i ?>"></td>
                    <td data-label="Total Horas Jugadas"><input type="text" name="totalHoras<?= $i ?>" id="totalHoras<?= $i ?>" readonly></td>
                    <td data-label="Precio por Hora"><input type="number" name="precioHora<?= $i ?>" id="precioHora<?= $i ?>" value="30"></td>
                    <td data-label="Precio a Pagar"><input type="text" name="precioPagar<?= $i ?>" id="precioPagar<?= $i ?>" readonly></td>
                    <td data-label="Acciones"><button type="button" onclick="calcularPrecio(<?= $i ?>)">Calcular</button></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
    <script src="Billar.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
  
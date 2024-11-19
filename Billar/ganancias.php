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

// Función para obtener las ganancias
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
    }

    $resultado = $conn->query($query);
    return $resultado->fetch_assoc()['total'] ?? 0;
}

// Obtener las ganancias
$ganancias_dia = obtenerGanancias($conn, 'dia');
$ganancias_semana = obtenerGanancias($conn, 'semana');
$ganancias_mes = obtenerGanancias($conn, 'mes');
$ganancias_anio = obtenerGanancias($conn, 'anio');

// Cerrar la conexión
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Billar</title>
    <link rel="stylesheet" href="ganancias.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #282c34;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #444444;
            padding: 10px 20px;
            width: 100%;
            height: 120px;
            box-sizing: border-box;
        }

        .logo {
            display: flex;
            align-items: center;
            
        }

        .img-logo {
            width: 180px;
            height: 110px;
            margin-left: 30px;
            padding-top:10px ;
            padding-bottom: 10px;
        }

        .navegacion ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navegacion li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 20px;
            font-family: "Handjet", sans-serif;
            padding: 10px;
            width: 130px;
            background-color: red;
            text-align: center;
            border-radius: 5px;
        }

        .container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1, h2 {
            text-align: center;
        }

        canvas {
            width: 100% !important; /* Asegúrate de que el canvas ocupe el 100% del contenedor */
            height: 400px !important; /* Establece una altura fija para las gráficas */
            max-width: 800px; /* Limita el ancho máximo de las gráficas */
            margin: 20px auto;
            border: 1px solid #ffffff;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            /* Navegación móvil */
            .nav {
                flex-direction: column;
                height: auto;
                padding: 20px;
                width: 100%;
                height: 300px;
            }

            .logo {
                margin: 0;
                justify-content: center;
                margin-left: 0;
            }

            .navegacion {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .navegacion ul {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .navegacion li a {
                font-size: 18px;
                padding: 5px;
            }

        }
    </style>
</head>
<body  >
    <nav class="nav">
        <div>
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

    <div class="container animate__animated animate__backInDown">
        <h1 >Ganancias del Billar</h1>
        <canvas  id="gananciasDia"></canvas>
        <canvas id="gananciasSemana"></canvas>
        <canvas id="gananciasMes"></canvas>
        <canvas id="gananciasAnio"></canvas>
    </div>

    <script>
        const ganancias = {
            dia: <?php echo $ganancias_dia; ?>,
            semana: <?php echo $ganancias_semana; ?>,
            mes: <?php echo $ganancias_mes; ?>,
            anio: <?php echo $ganancias_anio; ?>
        };

        const ctxDia = document.getElementById('gananciasDia').getContext('2d');
        const ctxSemana = document.getElementById('gananciasSemana').getContext('2d');
        const ctxMes = document.getElementById('gananciasMes').getContext('2d');
        const ctxAnio = document.getElementById('gananciasAnio').getContext('2d');

        const chartDia = new Chart(ctxDia, {
            type: 'bar',
            data: {
                labels: ['Ganancias del Día' ],
                datasets: [{
                    label: 'Ganancias',
                    data: [ganancias.dia],
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const chartSemana = new Chart(ctxSemana, {
            type: 'bar',
            data: {
                labels: ['Ganancias de la Semana'],
                datasets: [{
                    label: 'Ganancias',
                    data: [ganancias.semana],
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const chartMes = new Chart(ctxMes, {
            type: 'bar',
            data: {
                labels: ['Ganancias del Mes'],
                datasets: [{
                    label: 'Ganancias',
                    data: [ganancias.mes],
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const chartAnio = new Chart(ctxAnio, {
            type: 'bar',
            data: {
                labels: ['Ganancias del Año'],
                datasets: [{
                    label: 'Ganancias',
                    data: [ganancias.anio],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>    
    
    <script src="Billar.js"></script>
    
</body>
</html>
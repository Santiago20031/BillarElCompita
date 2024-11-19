<?php
// Configuración de la conexión a la base de datos
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

// Fecha actual para la entrada en la base de datos
$fecha = date("Y-m-d");

// Verificar que los campos de hora de inicio y hora final estén presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST["hora_inicio"]) && !empty($_POST["hora_final"])) {
    $mesa_numero = $_POST["mesa_numero"];
    $hora_inicio = $_POST["hora_inicio"];
    $hora_final = $_POST["hora_final"];
    $total_horas = $_POST["total_horas"] ?? 0;
    $precio_por_hora = $_POST["precio_por_hora"] ?? 30; // Precio por defecto
    $precio_a_pagar = $_POST["precio_a_pagar"] ?? 0;

    // Preparar la consulta SQL para insertar los datos
    $stmt = $conn->prepare("INSERT INTO mesas (mesa_numero, hora_inicio, hora_final, total_horas, precio_por_hora, precio_a_pagar, fecha) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $mesa_numero, $hora_inicio, $hora_final, $total_horas, $precio_por_hora, $precio_a_pagar, $fecha);

    // Ejecutar la consulta para insertar los datos de esta mesa
    if ($stmt->execute()) {
        echo "Datos guardados correctamente en la base de datos.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Los campos de hora de inicio y hora final son obligatorios.";
}

// Cerrar la conexión
$conn->close();
?>
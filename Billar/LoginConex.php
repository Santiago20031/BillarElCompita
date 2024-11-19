<?php
// Archivo: conexLogin.php
// Datos de conexión a la base de datos
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "Billar"; 

// Establecer conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die('<div class="alert alert-danger" role="alert">Conexión fallida: ' . $conn->connect_error . '</div>');
}

// Verificar si se enviaron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el correo electrónico y la contraseña enviados desde el formulario
    $correo = $conn->real_escape_string($_POST['email']);
    $contraseña = $_POST['password'];

    // Consulta SQL para obtener el usuario con el correo electrónico dado y estado activo
    $sql = "SELECT * FROM usuario WHERE correo = '$correo' AND estado_cuenta = 'activo'";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si la consulta fue exitosa
    if ($result === false) {
        die('<div class="alert alert-danger" role="alert">Error en la consulta: ' . $conn->error . '</div>');
    }

    // Si se encontró un usuario con el correo electrónico dado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["contraseña"]; // Asegúrate de que el campo se llama "contraseña"

        // Verificar la contraseña
        if (password_verify($contraseña, $hashed_password)) {
            // Contraseña correcta
            echo '<div class="alert alert-success" role="alert">Inicio de sesión exitoso. Redirigiendo...</div>';
            header("Refresh: 2; URL=inicio.php"); // Redirigir al usuario al inicio
            exit();
        } else {
            // Contraseña incorrecta
            echo '<div class="alert alert-danger" role="alert">Contraseña incorrecta. <a href="javascript:history.back()" class="alert-link">Volver</a></div>';
            exit();
        }
    } else {
        // Usuario no encontrado o cuenta no activa
        echo '<div class="alert alert-danger" role="alert">Usuario no encontrado o cuenta inactiva. <a href="javascript:history.back()" class="alert-link">Volver</a></div>';
        exit();
    }
}

// Cerrar la conexión con la base de datos
$conn->close();
?>
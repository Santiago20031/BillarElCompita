<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "billar"; // Cambiar por el nombre correcto de la base de datos

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('<div class="alert alert-danger" role="alert">Error en la conexión: ' . $conn->connect_error . '</div>');
}

// Procesar el formulario de activación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $conn->real_escape_string($_POST["email"]);
    $codigo_verificacion = $conn->real_escape_string($_POST["codigo_verificacion"]);

    // Verificar el código y correo en la base de datos
    $sql = "SELECT * FROM usuario WHERE correo = '$correo' AND codigo_verificacion = '$codigo_verificacion' AND estado_cuenta = 'pendiente'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Actualizar el estado de la cuenta a 'activo'
        $update_sql = "UPDATE usuario SET estado_cuenta = 'activo' WHERE correo = '$correo'";
        if ($conn->query($update_sql) === TRUE) {
            echo '<div class="alert alert-success" role="alert">¡Tu cuenta ha sido activada correctamente!</div>';
            // Redirigir al usuario a la página de inicio de sesión
            header("Location: index.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al activar la cuenta: ' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Código de verificación incorrecto o cuenta ya activada.</div>';
    }
}

$conn->close();
?>

<?php
$servername = "localhost"; // Cambiar si la base de datos está en un servidor diferente
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$database = "billar"; // Nombre de la base de datos

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die('<div class="alert alert-danger" role="alert">Error en la conexión: ' . $conn->connect_error . '</div>');
}

// Procesar el formulario de registro si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST["username"];
    $correo = $_POST["email"];
    $password = $_POST["password"];

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generar un código de verificación único
    $codigo_verificacion = str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT);

    // Query para insertar los datos en la tabla de usuario
    $sql = "INSERT INTO usuario (nombre_usuario, correo, contraseña, codigo_verificacion) 
            VALUES ('$nombre_usuario', '$correo', '$hashed_password', '$codigo_verificacion')";

    if ($conn->query($sql) === TRUE) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor de correo
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'santiagogonzalez07082003@gmail.com'; // Cambiar por tu correo
            $mail->Password = 'k v y e v z i o z j q z e e i p'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Destinatarios
            $mail->setFrom('santiagogonzalez07082003@gmail.com', 'Christian Santiago Montoya Gonzalez');
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta';
            $mail->Body = "Hola <b>$nombre_usuario</b>,<br>Este es tu código de verificación: <b>$codigo_verificacion</b>";
            $mail->AltBody = "Hola $nombre_usuario, Este es tu código de verificación: $codigo_verificacion";

            $mail->send();
            echo '<div class="alert alert-success" role="alert">El mensaje ha sido enviado. Verifica tu correo para activar tu cuenta.</div>';

            // Redirigir a la página de activación
            header("Location: activar.php");
            exit();
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">Error al enviar el correo: ' . $mail->ErrorInfo . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al registrar el usuario: ' . $conn->error . '</div>';
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
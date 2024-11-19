<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión / Registro</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="index.css">
    <style>
        .row{
            display: flex;
            justify-content: center;
        }
        .links {
            margin-top: 10px;
        }
    </style>
</head>
<body>


    <div class="container mt-5">
        <div class="row">
            <div class="logo">
                <img class="img-logo" src="img/logo.png" alt="logo">
            </div>
            <!-- Formulario de Inicio de Sesión -->
            <div class="col-md-6">
                <div class="card shadow p-4 mb-5">
                    <h3 class="text-center mb-4">Iniciar Sesión</h3>
                    <form action="conexActivar.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" name="email" required>
                            <label for="loginEmail">Correo Electrónico</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" id="verification-code" class="form-control" placeholder="Ingresa el código de verificación " name="codigo_verificacion" required>
                            <label for="verification-code">Verification Code</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Activar</button>
                            <div class="links">
                                <a href="index.php">Regresar a inicio de sesión </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
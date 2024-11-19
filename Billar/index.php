<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión / Registro</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="index.css">
    <style>
        .links{
            display: flex;
            justify-content: center;
            margin-top: 20px;
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
                    <form action="LoginConex.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" name="email" required>
                            <label for="loginEmail">Correo Electrónico</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password" name="password" required>
                            <label for="loginPassword">Contraseña</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Iniciar Sesión</button>
                            
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulario de Registro -->
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4">Registro</h3>
                    <form action="conexRegistro.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="registerUsername" placeholder="Usuario" name="username" required>
                            <label for="registerUsername">Nombre de Usuario</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="registerEmail" placeholder="name@example.com" name="email" required>
                            <label for="registerEmail">Correo Electrónico</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="registerPassword" placeholder="Password" name="password" required>
                            <label for="registerPassword">Contraseña</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-success btn-register text-uppercase fw-bold" type="submit">Registrarme</button>
                        </div>
                        
                    </form>
                </div>
            </div>
            <div class="links">
                <a href="activar.php">Activar cuenta</a>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

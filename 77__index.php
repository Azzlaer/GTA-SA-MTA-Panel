<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6d5dfc, #37c2cc);
            text-align: center;
        }
        .container-box {
            background: #ffffff;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
        }
        .nav-tabs .nav-link {
            color: #6d5dfc;
        }
        .nav-tabs .nav-link.active {
            background-color: #6d5dfc;
            color: #ffffff;
        }
    </style>
</head>
<body>
<div class="container-box">
    <h2 class="mb-4">Bienvenido</h2>
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Iniciar Sesión</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Registrarse</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="login" role="tabpanel">
            <form action="process.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="mb-3">
                    <label for="login-username" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="login-username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="login-password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="login-password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>
        </div>
        <div class="tab-pane fade" id="register" role="tabpanel">
            <form action="process.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="mb-3">
                    <label for="register-username" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="register-username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="register-password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="register-password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Registrarse</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
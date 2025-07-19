<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Crear Nueva Cuenta MTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-dark text-light">

<?php include 'header.php'; ?>

<div class="container my-5" style="max-width: 480px;">
    <div class="card bg-secondary shadow-lg">
        <div class="card-body">
            <h2 class="card-title mb-4 text-center">Crear Nueva Cuenta MTA (SQLite)</h2>
            <form method="post" action="procesar_creacion.php" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ingresa un usuario" required autofocus>
                    <div class="invalid-feedback">
                        Por favor ingresa un nombre de usuario válido.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa una contraseña" required>
                    <div class="invalid-feedback">
                        Por favor ingresa una contraseña.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
            </form>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('form')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>
</html>

<?php
require_once "./includes/acl.class.php";

$acl = new ACLManager("Z:/Servidores/mta/mods/deathmatch/acl.xml");

// Validar si llegó la información esperada
if (isset($_POST['usuario'], $_POST['grupo'], $_POST['accion'])) {
    $usuario = trim($_POST['usuario']);
    $grupo = trim($_POST['grupo']);
    $accion = $_POST['accion'];

    // Seguridad básica
    if ($usuario === '' || $grupo === '') {
        die("❌ Usuario o grupo inválido.");
    }

    // Ejecutar acción
    if ($accion === 'agregar') {
        $resultado = $acl->addUserToGroup($usuario, $grupo);
        $mensaje = $resultado
            ? "✅ El usuario <strong>$usuario</strong> fue agregado al grupo <strong>$grupo</strong>."
            : "❌ No se pudo agregar el usuario.";
    } elseif ($accion === 'remover') {
        $resultado = $acl->removeUserFromGroup($usuario, $grupo);
        $mensaje = $resultado
            ? "✅ El usuario <strong>$usuario</strong> fue removido del grupo <strong>$grupo</strong>."
            : "❌ No se pudo remover el usuario.";
    } else {
        $mensaje = "❌ Acción no válida.";
    }

    // Redirección con mensaje (puedes implementar mejor feedback con sesiones o alertas)
    echo "<meta http-equiv='refresh' content='2;url=users.php'>";
    echo "<p>$mensaje<br>Redirigiendo...</p>";
} else {
    echo "❌ Datos incompletos.";
}
?>

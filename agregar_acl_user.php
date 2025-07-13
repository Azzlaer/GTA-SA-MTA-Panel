<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: acl_editor.php");
    exit();
}

$group = $_POST['group'] ?? '';
$user = $_POST['user'] ?? '';

if (!$group || !$user) {
    die("Parámetros incompletos.");
}

$aclFile = 'Z:\Servidores\mta\mods\deathmatch\acl.xml';

if (!file_exists($aclFile)) {
    die("Archivo acl.xml no encontrado.");
}

// Cargar XML
$xml = simplexml_load_file($aclFile);

// Buscar el grupo
$targetGroup = null;
foreach ($xml->group as $g) {
    if ((string)$g['name'] === $group) {
        $targetGroup = $g;
        break;
    }
}

if (!$targetGroup) {
    die("Grupo no encontrado: $group");
}

// Verificar si ya existe el usuario en ese grupo
foreach ($targetGroup->object as $obj) {
    if ((string)$obj['name'] === "user.$user") {
        header("Location: acl_editor.php?msg=La cuenta '$user' ya pertenece al grupo '$group'");
        exit();
    }
}

// Agregar el objeto al grupo
$newObject = $targetGroup->addChild('object');
$newObject->addAttribute('name', "user.$user");

// Guardar el XML
$xml->asXML($aclFile);

// Redirigir con mensaje
header("Location: acl_editor.php?msg=Cuenta '$user' añadida al grupo '$group'");
exit();
?>

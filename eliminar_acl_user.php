<?php
if (!isset($_GET['group']) || !isset($_GET['user'])) {
    header("Location: acl_editor.php?msg=Parámetros incompletos.");
    exit();
}

$group = $_GET['group'];
$userObject = $_GET['user']; // Ej: "user.luisr5"

$aclFile = 'Z:\Servidores\mta\mods\deathmatch\acl.xml';

if (!file_exists($aclFile)) {
    die("Archivo acl.xml no encontrado.");
}

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
    header("Location: acl_editor.php?msg=Grupo '$group' no encontrado.");
    exit();
}

// Buscar y eliminar el objeto
$found = false;
for ($i = 0; $i < count($targetGroup->object); $i++) {
    if ((string)$targetGroup->object[$i]['name'] === $userObject) {
        unset($targetGroup->object[$i]);
        $found = true;
        break;
    }
}

if ($found) {
    $xml->asXML($aclFile);
    header("Location: acl_editor.php?msg=Cuenta eliminada del grupo '$group'");
} else {
    header("Location: acl_editor.php?msg=La cuenta no existe en el grupo '$group'");
}
exit();
?>

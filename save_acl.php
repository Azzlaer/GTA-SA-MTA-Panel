<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

$groups = $_POST['groups'] ?? [];
$permissions = $_POST['permissions'] ?? [];

if (count($groups) !== count($permissions)) {
    die("Datos inválidos");
}

$acl_file = __DIR__ . "Z:/Servidores/mta/mods/deathmatch/acl.xml";

$xml = new SimpleXMLElement('<acl></acl>');

for ($i = 0; $i < count($groups); $i++) {
    $group_name = trim($groups[$i]);
    $perms = trim($permissions[$i]);

    if ($group_name === '') continue; // Omitir grupos sin nombre

    $group = $xml->addChild('group');
    $group->addAttribute('name', $group_name);
    $group->addChild('permissions', htmlspecialchars($perms));
}

$xml->asXML($acl_file);

header("Location: acl_editor.php?msg=ACL actualizada con éxito");
exit();

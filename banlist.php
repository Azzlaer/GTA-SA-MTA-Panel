<?php
require_once "./includes/accounts_sqlite.class.php";

// Ruta de banlist.xml
$banlist_file = 'Z:\Servidores\mta\mods\deathmatch\banlist.xml';

// Función para leer banlist.xml
function loadBanList($file) {
    if (!file_exists($file)) return [];
    $xml = simplexml_load_file($file);
    $bans = [];
    foreach ($xml->ban as $ban) {
        $bans[] = [
            'name' => (string)$ban->name,
            'serial' => (string)$ban->serial,
            'ip' => (string)$ban->ip,
            'reason' => (string)$ban->reason,
            'time' => (int)$ban->time,
            'expire' => (int)$ban->expire,
        ];
    }
    return $bans;
}

// Función para guardar banlist.xml
function saveBanList($file, $bans) {
    $xml = new SimpleXMLElement('<banlist/>');
    foreach ($bans as $ban) {
        $banNode = $xml->addChild('ban');
        $banNode->addChild('name', $ban['name']);
        $banNode->addChild('serial', $ban['serial']);
        $banNode->addChild('ip', $ban['ip']);
        $banNode->addChild('reason', $ban['reason']);
        $banNode->addChild('time', $ban['time']);
        $banNode->addChild('expire', $ban['expire']);
    }
    $xml->asXML($file);
}

// Cargar usuarios
$manager = new AccountManagerSQL();
$usuarios = $manager->getAccounts();

// Cargar baneos actuales
$bans = loadBanList($banlist_file);

// Manejar formulario de ban
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ban'])) {
    $selectedUsers = $_POST['users'] ?? [];
    $reason = trim($_POST['reason'] ?? 'Sin motivo');
    $duration = intval($_POST['duration'] ?? 0); // en minutos
    if ($duration <= 0) $duration = 60; // default 1 hora

    $now = time();
    $expire = $now + ($duration * 60);

    foreach ($usuarios as $user) {
        if (in_array($user['id'], $selectedUsers)) {
            // Revisar si ya está baneado para no duplicar
            $exists = false;
            foreach ($bans as $b) {
                if ($b['serial'] === $user['serial'] || $b['ip'] === $user['ip']) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $bans[] = [
                    'name' => $user['name'],
                    'serial' => $user['serial'],
                    'ip' => $user['ip'],
                    'reason' => $reason,
                    'time' => $now,
                    'expire' => $expire,
                ];
            }
        }
    }
    saveBanList($banlist_file, $bans);
    $msg = 'Usuarios baneados correctamente.';
    // Recargar bans para actualizar la vista
    $bans = loadBanList($banlist_file);
}

// Manejar formulario para eliminar baneos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unban'])) {
    $unbanSerial = $_POST['serial'] ?? '';
    $bans = array_filter($bans, function($ban) use ($unbanSerial) {
        return $ban['serial'] !== $unbanSerial;
    });
    saveBanList($banlist_file, $bans);
    $msg = 'Usuario desbaneado correctamente.';
    $bans = loadBanList($banlist_file);
}

include 'header.php';
?>

<div class="container my-4">
    <h2 class="text-light text-center mb-4">🚫 Banear Usuarios</h2>
    <?php if ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post">
        <h4 class="text-light">Selecciona usuarios para banear:</h4>
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-dark table-hover table-sm">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>IP</th>
                        <th>Serial</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><input type="checkbox" name="users[]" value="<?= $u['id'] ?>"></td>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['ip']) ?></td>
                        <td><?= htmlspecialchars($u['serial']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mb-3 mt-3">
            <label for="reason" class="form-label">Motivo del baneo</label>
            <input type="text" class="form-control" id="reason" name="reason" placeholder="Motivo (opcional)">
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Duración del baneo (minutos)</label>
            <input type="number" class="form-control" id="duration" name="duration" min="1" max="43200" value="60" required>
            <small class="form-text text-muted">Duración en minutos (máximo 30 días = 43200 minutos)</small>
        </div>

        <button type="submit" name="ban" class="btn btn-danger">Banear seleccionados</button>
    </form>

    <hr class="my-4" />

    <h4 class="text-light">Lista de baneados actuales</h4>
    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-dark table-hover table-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>IP</th>
                    <th>Serial</th>
                    <th>Motivo</th>
                    <th>Inicio (UTC)</th>
                    <th>Expira (UTC)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bans as $ban): 
                    $expireText = $ban['expire'] > time() ? date('Y-m-d H:i:s', $ban['expire']) : '<span class="text-danger">Expirado</span>';
                ?>
                <tr>
                    <td><?= htmlspecialchars($ban['name']) ?></td>
                    <td><?= htmlspecialchars($ban['ip']) ?></td>
                    <td><?= htmlspecialchars($ban['serial']) ?></td>
                    <td><?= htmlspecialchars($ban['reason']) ?></td>
                    <td><?= date('Y-m-d H:i:s', $ban['time']) ?></td>
                    <td><?= $expireText ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="serial" value="<?= htmlspecialchars($ban['serial']) ?>">
                            <button type="submit" name="unban" class="btn btn-sm btn-success" onclick="return confirm('¿Desbanear usuario?');">Desbanear</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($bans) === 0): ?>
                    <tr><td colspan="7" class="text-center">No hay usuarios baneados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>

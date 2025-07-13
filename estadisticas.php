<?php
include 'header.php';
$db = new SQLite3('Z:\Servidores\mta\mods\deathmatch\internal.db');

// Usuarios más activos
$activos = $db->query("SELECT userid, COUNT(*) AS logins FROM serialusage GROUP BY userid ORDER BY logins DESC LIMIT 10");

// Nuevas cuentas
$nuevos = $db->querySingle("SELECT COUNT(*) FROM accounts WHERE id > 0");

// Número de bans
$banlistCount = 0;
if (file_exists("Z:\Servidores\mta\mods\deathmatch\banlist.xml")) {
    $banlist = simplexml_load_file("Z:\Servidores\mta\mods\deathmatch\banlist.xml");
    $banlistCount = count($banlist->ban);
}

// Tiempo promedio conexión
$promedio = $db->query("SELECT AVG(last_login_date - added_date) AS promedio FROM serialusage WHERE last_login_date > added_date");
$tiempoPromedio = round($promedio->fetchArray(SQLITE3_ASSOC)['promedio'] / 60); // minutos
?>

<div class="container py-4">
    <h2 class="text-center text-white mb-4">📊 Panel de Estadísticas - Servidor MTA</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow rounded">
                <div class="card-body text-center">
                    <h5 class="card-title">⛔ Usuarios Baneados</h5>
                    <p class="display-5 fw-bold"><?= $banlistCount ?></p>
                    <small class="text-light">Desde archivo banlist.xml</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow rounded">
                <div class="card-body text-center">
                    <h5 class="card-title">🧍‍♂️ Cuentas Registradas</h5>
                    <p class="display-5 fw-bold"><?= $nuevos ?></p>
                    <small class="text-light">Actualmente en base de datos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow rounded">
                <div class="card-body text-center">
                    <h5 class="card-title">⏱ Tiempo Promedio</h5>
                    <p class="display-5 fw-bold"><?= $tiempoPromedio ?> min</p>
                    <small class="text-light">Entre conexión y última sesión</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-white border border-secondary mt-5 shadow">
        <div class="card-header fw-bold fs-5">👥 Usuarios Más Activos</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover table-sm mb-0">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>#</th>
                            <th>ID Usuario</th>
                            <th>Conexiones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $activos->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['userid']) ?></td>
                            <td><?= $row['logins'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <a href="exportar_estadisticas.php?formato=csv" class="btn btn-outline-light me-2">📥 Exportar CSV</a>
        <a href="exportar_estadisticas.php?formato=json" class="btn btn-outline-warning">📤 Exportar JSON</a>
    </div>
</div>

<?php include 'footer.php'; ?>

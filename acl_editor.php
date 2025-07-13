<?php
include 'header.php';  // Incluye el navbar y estilos

$aclFile = 'Z:\Servidores\mta\mods\deathmatch\acl.xml';
$acl = simplexml_load_file($aclFile);
?>

<div class="container my-4">
    <h2 class="text-light text-center mb-4">🔐 Editor de Grupos ACL</h2>

    <div class="accordion" id="aclAccordion">
        <?php $i = 0; foreach ($acl->group as $group): ?>
            <div class="accordion-item bg-dark text-light">
                <h2 class="accordion-header" id="heading<?= $i ?>">
                    <button class="accordion-button collapsed bg-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
                        🧷 <?= htmlspecialchars($group['name']) ?>
                    </button>
                </h2>
                <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>" data-bs-parent="#aclAccordion">
                    <div class="accordion-body">

                        <h6 class="text-info">👥 Cuentas:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($group->object as $obj): ?>
                                <?php if (str_starts_with((string)$obj, 'user.')): ?>
                                    <li class="list-group-item bg-dark text-light">
                                        <?= htmlspecialchars(str_replace('user.', '', (string)$obj)) ?>
                                        <a href="eliminar_acl_user.php?group=<?= urlencode($group['name']) ?>&user=<?= urlencode($obj) ?>" class="btn btn-sm btn-danger float-end">Eliminar</a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>

                        <form method="post" action="agregar_acl_user.php" class="mb-3">
                            <input type="hidden" name="group" value="<?= htmlspecialchars($group['name']) ?>">
                            <div class="input-group">
                                <input type="text" name="user" class="form-control" placeholder="Nombre de cuenta (sin 'user.')" required>
                                <button class="btn btn-success" type="submit">➕ Agregar cuenta</button>
                            </div>
                        </form>

                        <h6 class="text-warning">🔑 Derechos (ACLs):</h6>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($group->acl as $perm): ?>
                                <li class="list-group-item bg-dark text-light"><?= htmlspecialchars((string)$perm) ?></li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                </div>
            </div>
        <?php $i++; endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login("reserves.php");

$codiClient = (int)$_SESSION['codiClient'];

$r = crs_call([
    "func" => 4,
    "codiClient" => $codiClient
]);

include __DIR__ . '/header.php';
?>

    <h2>Les meves reserves</h2>

    <div class="card">
        <?php if (!$r["ok"]): ?>
            <p class="err"><?= h($r["error"]) ?></p>
        <?php else: ?>
            <?php $data = $r["data"]; ?>
            <?php if (($data["idresp"] ?? "") !== "R4OK"): ?>
                <p class="err">Resposta CRS inesperada.</p>
                <pre><?= h(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>Codi</th><th>Entrada</th><th>Sortida</th><th>Estat</th>
                        <th>Hotel</th><th>Tipus</th><th>Règim</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (($data["reserves"] ?? []) as $row): ?>
                        <tr>
                            <td><?= h((string)$row["codiReserva"]) ?></td>
                            <td><?= h((string)$row["dataEntrada"]) ?></td>
                            <td><?= h((string)$row["dataSortida"]) ?></td>
                            <td><?= h((string)$row["estatReserva"]) ?></td>
                            <td><?= h((string)$row["nomHotel"]) ?></td>
                            <td><?= h((string)$row["tipusHabitacio"]) ?></td>
                            <td><?= h((string)$row["descripcioRegim"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
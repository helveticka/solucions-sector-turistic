<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

if (!isset($_SESSION['entrada'], $_SESSION['sortida'], $_SESSION['pax'])) {
    header("Location: principal.php");
    exit();
}

$idHotel = (int)($_SESSION['idHotel'] ?? 0);
$entrada = (string)$_SESSION['entrada'];
$sortida = (string)$_SESSION['sortida'];
$pax = (int)($_SESSION['pax'] ?? 1);
$tipusFiltre = (string)($_SESSION['tipusSel'] ?? '');

$conn = ch_db();

$hotels = [];
if ($idHotel > 0) {
    $stmt = $conn->prepare("SELECT codiHotel, nomHotel FROM HOTEL WHERE codiHotel=?");
    $stmt->bind_param("i", $idHotel);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $hotels[] = $row;
    $stmt->close();
} else {
    $res = $conn->query("SELECT codiHotel, nomHotel FROM HOTEL ORDER BY codiHotel");
    while ($row = $res->fetch_assoc()) $hotels[] = $row;
}

$tipus = [];
if ($tipusFiltre !== '') {
    $stmt = $conn->prepare("SELECT codiTipusHabitacio, denominacio, pax FROM TIPUS_HABITACIO WHERE codiTipusHabitacio=?");
    $stmt->bind_param("s", $tipusFiltre);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $tipus[] = $row;
    $stmt->close();
} else {
    $res = $conn->query("SELECT codiTipusHabitacio, denominacio, pax FROM TIPUS_HABITACIO ORDER BY codiTipusHabitacio");
    while ($row = $res->fetch_assoc()) $tipus[] = $row;
}

$results = [];
foreach ($hotels as $h) {
    foreach ($tipus as $t) {
        if ((int)$t['pax'] < $pax) continue;

        $calc = ch_disponibilitat_rang((int)$h['codiHotel'], (string)$t['codiTipusHabitacio'], $entrada, $sortida);
        if ($calc === null) continue;
        if ((int)$calc['disponibles'] <= 0) continue;

        $results[] = [
                "codiHotel" => (int)$h['codiHotel'],
                "nomHotel" => $h['nomHotel'],
                "codiTipus" => $t['codiTipusHabitacio'],
                "denominacio" => $t['denominacio'],
                "paxTipus" => (int)$t['pax'],
                "nits" => (int)$calc['nits'],
                "disponibles" => (int)$calc['disponibles'],
                "preuTotal" => (float)$calc['preuTotal'],
        ];
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Resultats (Channel)</h2>

    <div class="card" style="margin-bottom:14px">
        <p style="margin:0">
            <b>Entrada:</b> <?= h($entrada) ?> &nbsp;·&nbsp;
            <b>Sortida:</b> <?= h($sortida) ?> &nbsp;·&nbsp;
            <b>Pax:</b> <?= h((string)$pax) ?>
        </p>
    </div>

    <div class="card">
        <?php if (empty($results)): ?>
            <p style="margin:0 0 12px">No hi ha disponibilitat publicada pel Channel Manager amb aquests criteris.</p>
            <a class="btn btn-primary" href="principal.php">Nova cerca</a>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Tipus</th>
                    <th>Pax tipus</th>
                    <th>Nits</th>
                    <th>Disponibles</th>
                    <th>Preu total (€)</th>
                    <th>Acció</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?= h($r['nomHotel']) ?></td>
                        <td><?= h($r['codiTipus']) ?> — <?= h($r['denominacio']) ?></td>
                        <td><?= h((string)$r['paxTipus']) ?></td>
                        <td><?= h((string)$r['nits']) ?></td>
                        <td><b><?= h((string)$r['disponibles']) ?></b></td>
                        <td><?= h(number_format($r['preuTotal'], 2)) ?></td>
                        <td>
                            <a class="btn btn-primary" href="reservar.php?codiHotel=<?= h((string)$r['codiHotel']) ?>&codiTipus=<?= h($r['codiTipus']) ?>">
                                Reservar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:12px">
                <a class="btn" href="principal.php">Enrere</a>
            </p>
        <?php endif; ?>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
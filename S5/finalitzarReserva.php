<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login("finalitzarReserva.php");

if (!isset($_SESSION['codiClient'], $_SESSION['idHotel'], $_SESSION['entrada'], $_SESSION['sortida'], $_SESSION['numeroHabitacio'], $_SESSION['codiRegim'])) {
    header("Location: principal.php");
    exit();
}

$codiClient = (int)$_SESSION['codiClient'];
$codiHotel  = (int)$_SESSION['idHotel'];
$entrada    = (string)$_SESSION['entrada'];
$sortida    = (string)$_SESSION['sortida'];
$numeroHabitacio = (int)$_SESSION['numeroHabitacio'];
$regim      = (int)$_SESSION['codiRegim'];

$r = crs_call([
    "func" => 2,
    "codiClient" => $codiClient,
    "codiHotel" => $codiHotel,
    "numeroHabitacio" => $numeroHabitacio,
    "regim" => $regim,
    "entrada" => $entrada,
    "sortida" => $sortida
]);

include __DIR__ . '/header.php';
?>

    <h2>Resultat de la reserva</h2>

    <div class="card">
        <?php if (!$r["ok"]): ?>
            <p class="err"><?= h($r["error"]) ?></p>
            <p><small><?= h($r["url"] ?? "") ?></small></p>
        <?php else: ?>
            <?php $data = $r["data"]; ?>
            <?php if (($data["idresp"] ?? "") === "R2OK"): ?>
                <p class="ok">✅ Reserva creada correctament.</p>
                <p>Número de reserva: <b><?= h((string)$data["reserva"]["codiReserva"]) ?></b></p>
            <?php else: ?>
                <p class="err">❌ No s'ha pogut crear la reserva.</p>
                <pre><?= h(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <p>
        <a class="btn btn-primary" href="reserves.php">Veure les meves reserves</a>
        <a class="btn" href="principal.php">Nova cerca</a>
    </p>

<?php include __DIR__ . '/footer.php'; ?>
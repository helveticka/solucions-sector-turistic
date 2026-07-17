<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login("reservar.php");

if (!isset($_SESSION['entrada'], $_SESSION['sortida'])) {
    header("Location: principal.php");
    exit();
}

$codiClient = (int)$_SESSION['codiClient'];
$entrada = (string)$_SESSION['entrada'];
$sortida = (string)$_SESSION['sortida'];

$codiHotel = (int)($_GET['codiHotel'] ?? 0);
$codiTipus = (string)($_GET['codiTipus'] ?? '');

if ($codiHotel <= 0 || $codiTipus === '') {
    header("Location: disponibilitat.php");
    exit();
}

$calc = ch_disponibilitat_rang($codiHotel, $codiTipus, $entrada, $sortida);
if ($calc === null || (int)$calc['disponibles'] <= 0) {
    $err = "Ja no hi ha disponibilitat al Channel per aquesta opció.";
}

$regims = [];
$rres = pms_db()->query("SELECT codiRegim, descripcioRegim FROM REGIM ORDER BY codiRegim");
while ($row = $rres->fetch_assoc()) $regims[] = $row;

$err = $err ?? null;
$ok = null;
$detall = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$err) {
    $regim = (int)($_POST['regim'] ?? 0);
    if ($regim <= 0) {
        $err = "Has de seleccionar un règim.";
    } else {
        $r1 = crs_call([
            "func" => 1,
            "idHotel" => $codiHotel,
            "tipus" => (int)$codiTipus,
            "entrada" => $entrada,
            "sortida" => $sortida
        ]);

        if (!$r1["ok"]) {
            $err = $r1["error"];
        } else {
            $data1 = $r1["data"];
            if (($data1["idresp"] ?? "") !== "R1OK" || empty($data1["habitacions"])) {
                $err = "El CRS no retorna habitacions disponibles per crear la reserva.";
            } else {
                $numeroHabitacio = (int)$data1["habitacions"][0]["numeroHabitacio"];

                $r2 = crs_call([
                    "func" => 2,
                    "codiClient" => $codiClient,
                    "codiHotel" => $codiHotel,
                    "numeroHabitacio" => $numeroHabitacio,
                    "regim" => $regim,
                    "entrada" => $entrada,
                    "sortida" => $sortida
                ]);

                if (!$r2["ok"]) {
                    $err = $r2["error"];
                } else {
                    $data2 = $r2["data"];
                    if (($data2["idresp"] ?? "") !== "R2OK") {
                        $motiu = $data2["motiu"] ?? null;
                        $det = $data2["detall"] ?? null;
                        $err = "No s'ha pogut crear la reserva al PMS.";
                        if ($motiu) $err .= " Motiu: " . $motiu . ".";
                        if ($det) $err .= " Detall: " . $det;
                        $detall = json_encode($data2, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    } else {
                        $codiReserva = $data2["reserva"]["codiReserva"] ?? null;

                        // 5) Incrementar disponibilitat Channel (reservesChannel +1 per nit)
                        $updated = ch_incrementa_reserva($codiHotel, $codiTipus, $entrada, $sortida);

                        if (!$updated) {
                            $ok = "✅ Reserva creada al PMS (codi $codiReserva), però ⚠️ no s'ha pogut reflectir al Channel (revisa DISPONIBILITAT).";
                        } else {
                            $ok = "✅ Reserva creada al PMS (codi $codiReserva) i reflectida al Channel (+1 reservesChannel).";
                        }

                    }
                }
            }
        }
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Reservar</h2>

    <div class="card" style="margin-bottom:14px">
        <p style="margin:0">
            <b>Hotel:</b> <?= h((string)$codiHotel) ?>
            &nbsp;·&nbsp; <b>Tipus:</b> <?= h($codiTipus) ?>
            &nbsp;·&nbsp; <b>Entrada:</b> <?= h($entrada) ?>
            &nbsp;·&nbsp; <b>Sortida:</b> <?= h($sortida) ?>
        </p>
    </div>

<?php if ($err): ?>
    <div class="card" style="margin-bottom:14px">
        <p class="err" style="margin:0"><?= h($err) ?></p>
        <?php if ($detall): ?><pre><?= h($detall) ?></pre><?php endif; ?>
        <p style="margin-top:12px"><a class="btn" href="disponibilitat.php">Tornar</a></p>
    </div>
<?php endif; ?>

<?php if ($ok): ?>
    <div class="card" style="margin-bottom:14px">
        <p class="ok" style="margin:0"><?= h($ok) ?></p>
        <p style="margin-top:12px">
            <a class="btn btn-primary" href="reserves.php">Veure reserves PMS</a>
            <a class="btn" href="principal.php">Nova cerca</a>
        </p>
    </div>
<?php endif; ?>

<?php if (!$ok): ?>
    <div class="card">
        <form method="post">
            <label>Règim (PMS)</label>
            <select name="regim" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($regims as $r): ?>
                    <option value="<?= h((string)$r['codiRegim']) ?>"><?= h($r['descripcioRegim']) ?></option>
                <?php endforeach; ?>
            </select>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Confirmar i reservar</button>
                <a class="btn" href="disponibilitat.php">Cancel·lar</a>
            </p>
        </form>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
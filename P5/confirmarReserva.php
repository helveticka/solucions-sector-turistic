<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login("confirmarReserva.php");

if (!isset($_SESSION['idHotel'], $_SESSION['entrada'], $_SESSION['sortida'], $_SESSION['tipusSel'])) {
    header("Location: principal.php");
    exit();
}

$idHotel = (int)$_SESSION['idHotel'];
$entrada = (string)$_SESSION['entrada'];
$sortida = (string)$_SESSION['sortida'];
$tipusSel = (int)$_SESSION['tipusSel'];

$conn = db();

$regims = [];
$res = $conn->query("SELECT codiRegim, descripcioRegim FROM REGIM ORDER BY codiRegim");
while ($row = $res->fetch_assoc()) $regims[] = $row;

$r = crs_call([
    "func" => 1,
    "idHotel" => $idHotel,
    "tipus" => $tipusSel,
    "entrada" => $entrada,
    "sortida" => $sortida
]);

$err = null;
$habitacions = [];

if (!$r["ok"]) $err = $r["error"];
else {
    $data = $r["data"];
    if (($data["idresp"] ?? "") !== "R1OK") $err = "Resposta CRS inesperada.";
    else $habitacions = $data["habitacions"] ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroHabitacio = (int)($_POST['numeroHabitacio'] ?? 0);
    $codiRegim = (int)($_POST['codiRegim'] ?? 0);

    if ($numeroHabitacio <= 0 || $codiRegim <= 0) {
        $err = "Has de seleccionar una habitació i un règim.";
    } else {
        $_SESSION['numeroHabitacio'] = $numeroHabitacio;
        $_SESSION['codiRegim'] = $codiRegim;
        header("Location: finalitzarReserva.php");
        exit();
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Confirmar reserva</h2>

    <p>Hotel: <b><?= h((string)$idHotel) ?></b> — Entrada: <b><?= h($entrada) ?></b> — Sortida: <b><?= h($sortida) ?></b></p>

<?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>

    <div class="card">
        <form method="post">
            <label>Habitació disponible</label>
            <select name="numeroHabitacio" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($habitacions as $h): ?>
                    <option value="<?= h((string)$h['numeroHabitacio']) ?>">
                        #<?= h((string)$h['numeroHabitacio']) ?> — <?= h((string)$h['denominacio']) ?> (pax <?= h((string)$h['pax']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Règim</label>
            <select name="codiRegim" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($regims as $re): ?>
                    <option value="<?= h((string)$re['codiRegim']) ?>"><?= h($re['descripcioRegim']) ?></option>
                <?php endforeach; ?>
            </select>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Finalitzar reserva</button>
                <a class="btn" href="disponibilitat.php">Enrere</a>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
<?php
require_once __DIR__ . '/config.php';
session_start();

$conn = ch_db();

$hotels = [];
$res = $conn->query("SELECT codiHotel, nomHotel FROM HOTEL ORDER BY codiHotel");
while ($row = $res->fetch_assoc()) $hotels[] = $row;

$tipus = [];
$res = $conn->query("SELECT codiTipusHabitacio, denominacio, pax FROM TIPUS_HABITACIO ORDER BY codiTipusHabitacio");
while ($row = $res->fetch_assoc()) $tipus[] = $row;

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idHotel = (int)($_POST['idHotel'] ?? 0);
    $entrada = $_POST['entrada'] ?? '';
    $sortida = $_POST['sortida'] ?? '';
    $pax = (int)($_POST['pax'] ?? 1);
    $tipusSel = (string)($_POST['tipusSel'] ?? '');

    if ($entrada === '' || $sortida === '' || $pax < 1) {
        $err = "Falten camps obligatoris.";
    } elseif (strtotime($sortida) <= strtotime($entrada)) {
        $err = "La sortida ha de ser posterior a l'entrada.";
    } else {
        $_SESSION['idHotel'] = $idHotel;     // 0 = tots
        $_SESSION['entrada'] = $entrada;
        $_SESSION['sortida'] = $sortida;
        $_SESSION['pax'] = $pax;
        $_SESSION['tipusSel'] = $tipusSel;   // '' = tots
        header("Location: disponibilitat.php");
        exit();
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Cerca d’allotjament</h2>

    <div class="card">
        <p style="margin-top:0">Resultats basats en la disponibilitat publicada pel <b>Channel Manager</b>. La reserva final es crea al <b>PMS</b> via webservice.</p>

        <?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>

        <form method="post">
            <label>Hotel</label>
            <select name="idHotel">
                <option value="0">Tots els hotels</option>
                <?php foreach ($hotels as $h): ?>
                    <option value="<?= h((string)$h['codiHotel']) ?>"><?= h($h['nomHotel']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Entrada</label>
            <input type="date" name="entrada" required>

            <label>Sortida</label>
            <input type="date" name="sortida" required>

            <label>Pax</label>
            <select name="pax">
                <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
            </select>

            <label>Tipus d'habitació (opcional)</label>
            <select name="tipusSel">
                <option value="">Tots els tipus</option>
                <?php foreach ($tipus as $t): ?>
                    <option value="<?= h($t['codiTipusHabitacio']) ?>">
                        <?= h($t['codiTipusHabitacio']) ?> — <?= h($t['denominacio']) ?> (pax <?= h((string)$t['pax']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Cercar</button>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
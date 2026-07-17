<file name=0 path=principal.php><?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

$conn = db();

$hotels = [];
$res = $conn->query("SELECT codiHotel, nomHotel FROM HOTEL ORDER BY codiHotel");
while ($row = $res->fetch_assoc()) $hotels[] = $row;

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idHotel = $_POST['idHotel'] ?? '';
    $entrada = $_POST['entrada'] ?? '';
    $sortida = $_POST['sortida'] ?? '';
    $adults  = (int)($_POST['adults'] ?? 1);

    if ($idHotel === '' || $entrada === '' || $sortida === '' || $adults < 1) {
        $err = "Falten camps obligatoris.";
    } else {
        $_SESSION['idHotel'] = (int)$idHotel;
        $_SESSION['entrada'] = $entrada;
        $_SESSION['sortida'] = $sortida;
        $_SESSION['adults']  = $adults;
        header("Location: disponibilitat.php");
        exit();
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Consulta de disponibilitat</h2>

    <div class="card">
        <?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>

        <form method="post">
            <label>Hotel</label>
            <select name="idHotel" required>
                <?php foreach ($hotels as $h): ?>
                    <option value="<?= h($h['codiHotel']) ?>"><?= h($h['nomHotel']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Entrada</label>
            <input type="date" name="entrada" required>

            <label>Sortida</label>
            <input type="date" name="sortida" required>

            <label>Adults (pax)</label>
            <select name="adults">
                <option value="1">1</option><option value="2">2</option>
                <option value="3">3</option><option value="4">4</option>
            </select>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Comprovar disponibilitat</button>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?></file>
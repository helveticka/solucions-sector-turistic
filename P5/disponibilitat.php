<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['idHotel'], $_SESSION['entrada'], $_SESSION['sortida'])) {
    header("Location: principal.php");
    exit();
}

$idHotel = (int)$_SESSION['idHotel'];
$entrada = (string)$_SESSION['entrada'];
$sortida = (string)$_SESSION['sortida'];
$adults  = (int)($_SESSION['adults'] ?? 1);

$conn = db();

$tipus = [];
$res = $conn->query("SELECT codiTipusHabitacio, denominacio, pax, llits FROM TIPUS_HABITACIO ORDER BY codiTipusHabitacio");
while ($row = $res->fetch_assoc()) $tipus[] = $row;

$alternatives = [];
$errors = [];

foreach ($tipus as $t) {
    if ((int)$t['pax'] < $adults) continue;

    $r = crs_call([
        "func" => 1,
        "idHotel" => $idHotel,
        "tipus" => (int)$t['codiTipusHabitacio'],
        "entrada" => $entrada,
        "sortida" => $sortida
    ]);

    if (!$r["ok"]) { $errors[] = $r["error"]; continue; }

    $data = $r["data"];
    if (($data["idresp"] ?? "") !== "R1OK") continue;

    $alternatives[] = [
        "codiTipusHabitacio" => (int)$t['codiTipusHabitacio'],
        "denominacio" => $t['denominacio'],
        "pax" => (int)$t['pax'],
        "llits" => (int)$t['llits'],
        "disponibles" => (int)($data["disponibles"] ?? 0)
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipusSel = (int)($_POST['tipusSel'] ?? 0);
    if ($tipusSel <= 0) {
        $errors[] = "Has de seleccionar un tipus d'habitació.";
    } else {
        $_SESSION['tipusSel'] = $tipusSel;
        header("Location: confirmarReserva.php");
        exit();
    }
}

include __DIR__ . '/header.php';
?>

<h2>Disponibilitat</h2>

<div class="card" style="margin-bottom:14px">
  <p style="margin:0">
    <b>Hotel:</b> <?= h((string)$idHotel) ?>
    &nbsp;·&nbsp; <b>Entrada:</b> <?= h($entrada) ?>
    &nbsp;·&nbsp; <b>Sortida:</b> <?= h($sortida) ?>
    &nbsp;·&nbsp; <b>Pax:</b> <?= h((string)$adults) ?>
  </p>
</div>

<?php if (!empty($errors)): ?>
  <div class="card" style="margin-bottom:14px">
    <?php foreach ($errors as $e): ?>
      <p class="err" style="margin:0 0 8px"><?= h($e) ?></p>
    <?php endforeach; ?>
    <p style="margin:0"><a class="btn" href="principal.php">Tornar a la cerca</a></p>
  </div>
<?php endif; ?>

<div class="card">
  <?php if (empty($alternatives)): ?>
    <p style="margin:0 0 12px">No s'han trobat alternatives per aquestes dates i pax.</p>
    <p style="margin:0">
      <a class="btn btn-primary" href="principal.php">Nova cerca</a>
    </p>
  <?php else: ?>
    <form method="post">
      <table>
        <thead>
          <tr>
            <th>Tria</th>
            <th>Tipus d'habitació</th>
            <th>Pax</th>
            <th>Llits</th>
            <th>Disponibles</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alternatives as $a): ?>
            <tr>
              <td>
                <input type="radio" name="tipusSel" value="<?= h((string)$a['codiTipusHabitacio']) ?>" <?= $a['disponibles'] > 0 ? '' : 'disabled' ?>>
              </td>
              <td><?= h($a['denominacio']) ?></td>
              <td><?= h((string)$a['pax']) ?></td>
              <td><?= h((string)$a['llits']) ?></td>
              <td><?= h((string)$a['disponibles']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <p style="margin-top:12px">
        <button class="btn btn-primary" type="submit">Continuar</button>
        <a class="btn" href="principal.php">Enrere</a>
      </p>
    </form>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

$conn = db();
$msg = null; $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $llinatges = trim($_POST['llinatges'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $nacionalitat = trim($_POST['nacionalitat'] ?? '');
    $dataNaixement = trim($_POST['dataNaixement'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nom === '' || $email === '' || $password === '') {
        $err = "Nom, email i password són obligatoris.";
    } else {
        $r = crs_call([
            "func" => 3,
            "nom" => $nom,
            "llinatges" => $llinatges,
            "email" => $email,
            "telefon" => $telefon,
            "dni" => $dni,
            "nacionalitat" => $nacionalitat,
            "dataNaixement" => $dataNaixement
        ]);

        if (!$r["ok"]) $err = $r["error"];
        else {
            $data = $r["data"];
            if (($data["idresp"] ?? "") !== "R3OK") {
                $motiu = $data["motiu"] ?? null;
                $detall = $data["detall"] ?? null;
                $err = "No s'ha pogut crear el client al CRS.";
                if ($motiu) $err .= " Motiu: " . $motiu . ".";
                if ($detall) $err .= " Detall: " . $detall;
            } else {
                // recuperar codiClient per email
                $stmt = $conn->prepare("SELECT codiClient FROM CLIENT WHERE emailClient=? ORDER BY codiClient DESC LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $stmt->close();

                if (!$row) $err = "Client creat però no s'ha pogut recuperar el codiClient.";
                else {
                    $codiClient = (int)$row["codiClient"];
                    $hash = password_hash($password, PASSWORD_BCRYPT);

                    $stmt = $conn->prepare("INSERT INTO WEB_USER (email, password_hash, codiClient) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssi", $email, $hash, $codiClient);
                    if (!$stmt->execute()) {
                        $err = "Error creant usuari web (potser email ja existeix).";
                    } else {
                        $_SESSION['email'] = $email;
                        $_SESSION['codiClient'] = $codiClient;
                        $msg = "Usuari creat i sessió iniciada.";
                    }
                    $stmt->close();
                }
            }
        }
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Register</h2>

    <div class="card">
        <?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>
        <?php if ($msg): ?><p class="ok"><?= h($msg) ?></p><?php endif; ?>

        <form method="post">
            <label>Nom*</label><input name="nom" required>
            <label>Llinatges</label><input name="llinatges">
            <label>Email*</label><input name="email" type="email" required>
            <label>Telèfon</label><input name="telefon">
            <label>DNI</label><input name="dni">
            <label>Nacionalitat</label><input name="nacionalitat">
            <label>Data naixement</label><input name="dataNaixement" type="date">
            <label>Password*</label><input name="password" type="password" required>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Crear compte</button>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
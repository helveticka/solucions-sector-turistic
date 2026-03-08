<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

$conn = pms_db();
$err = null; $ok = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nom === '' || $email === '' || $password === '') {
        $err = "Nom, email i password són obligatoris.";
    } else {
        $r = crs_call(["func"=>3, "nom"=>$nom, "email"=>$email]);
        if (!$r["ok"]) {
            $err = $r["error"];
        } else {
            $data = $r["data"];
            if (($data["idresp"] ?? "") !== "R3OK") {
                $motiu = $data["motiu"] ?? null;
                $det = $data["detall"] ?? null;
                $err = "No s'ha creat el client al CRS.";
                if ($motiu) $err .= " Motiu: $motiu.";
                if ($det) $err .= " Detall: $det";
            } else {
                $stmt = $conn->prepare("SELECT codiClient FROM CLIENT WHERE emailClient=? ORDER BY codiClient DESC LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (!$row) {
                    $err = "Client creat però no s'ha pogut recuperar el codiClient.";
                } else {
                    $codiClient = (int)$row["codiClient"];
                    $hash = password_hash($password, PASSWORD_BCRYPT);

                    $stmt = $conn->prepare("INSERT INTO WEB_USER (email, password_hash, codiClient) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssi", $email, $hash, $codiClient);
                    if (!$stmt->execute()) {
                        $err = "No s'ha pogut crear l'usuari web (email duplicat?).";
                    } else {
                        $_SESSION['email'] = $email;
                        $_SESSION['codiClient'] = $codiClient;
                        $ok = "Usuari creat i sessió iniciada.";
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
        <?php if ($ok): ?><p class="ok"><?= h($ok) ?></p><?php endif; ?>

        <form method="post">
            <label>Nom</label><input name="nom" required>
            <label>Email</label><input name="email" type="email" required>
            <label>Password</label><input name="password" type="password" required>
            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Crear compte</button>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
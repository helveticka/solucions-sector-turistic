<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

$conn = db();
$err = null;

$returnTo = $_GET['returnTo'] ?? 'principal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("
      SELECT w.password_hash, w.codiClient
      FROM WEB_USER w
      WHERE w.email = ?
      LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (!$row || !password_verify($password, $row['password_hash'])) {
        $err = "Credencials incorrectes.";
    } else {
        $_SESSION['email'] = $email;
        $_SESSION['codiClient'] = (int)$row['codiClient'];
        header("Location: " . $returnTo);
        exit();
    }
}

include __DIR__ . '/header.php';
?>

    <h2>Login</h2>

    <div class="card">
        <?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>

        <form method="post">
            <input type="hidden" name="returnTo" value="<?= h($returnTo) ?>">
            <label>Email</label><input name="email" type="email" required>
            <label>Password</label><input name="password" type="password" required>

            <p style="margin-top:12px">
                <button class="btn btn-primary" type="submit">Entrar</button>
                <a class="btn" href="register.php">Crear compte</a>
            </p>
        </form>
    </div>

<?php include __DIR__ . '/footer.php'; ?>
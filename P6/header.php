<?php // header.php ?>
<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking (Channel → PMS)</title>
    <style>
        :root{
            --bg:#eef7f0;--card:rgba(255,255,255,.55);--stroke:rgba(20,70,40,.18);
            --text:#12301d;--muted:rgba(18,48,29,.72);--accent:#7bdc8f;--accent-strong:#32b85a;
            --danger:#b00020;--ok:#1b7f3a;--shadow:0 18px 45px rgba(0,0,0,.10);--radius:16px;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);
            background:radial-gradient(900px 500px at 50% -80px, rgba(123,220,143,.55), transparent 60%),
            radial-gradient(900px 500px at 50% 0px, rgba(255,255,255,.75), transparent 60%),
            var(--bg);min-height:100vh}
        .topbar{position:sticky;top:0;z-index:10;background:linear-gradient(180deg, rgba(123,220,143,.50), rgba(123,220,143,.22));
            backdrop-filter:blur(6px);border-bottom:1px solid rgba(0,0,0,.05);padding:14px 18px}
        .nav{max-width:980px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:12px}
        .nav-left,.nav-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .brand{font-weight:900;font-size:18px;letter-spacing:.2px}
        .nav a{text-decoration:none;color:var(--text);padding:8px 12px;border-radius:999px;border:1px solid rgba(0,0,0,.08);
            background:rgba(255,255,255,.35);font-weight:650}
        .nav a:hover{background:rgba(255,255,255,.5)}
        .container{max-width:980px;margin:0 auto;padding:26px 18px 60px}
        h2{margin:6px 0 14px;font-size:26px;font-weight:900;color:rgba(18,48,29,.92)}
        p{color:var(--muted)}
        .card{background:var(--card);border:1px solid var(--stroke);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);backdrop-filter:blur(4px)}
        label{display:block;margin:10px 0 6px;font-weight:750;color:rgba(18,48,29,.90)}
        input,select{width:100%;max-width:560px;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,.12);
            background:rgba(255,255,255,.70);color:var(--text);outline:none}
        input:focus,select:focus{border-color:rgba(50,184,90,.55);box-shadow:0 0 0 4px rgba(123,220,143,.25)}
        .btn{display:inline-block;padding:10px 14px;border-radius:12px;border:1px solid rgba(0,0,0,.12);background:rgba(255,255,255,.40);
            color:var(--text);cursor:pointer;text-decoration:none;font-weight:750}
        .btn:hoverbtn{}
        .btn:hover{background:rgba(255,255,255,.55)}
        .btn-primary{background:linear-gradient(180deg,var(--accent),var(--accent-strong));border-color:rgba(0,0,0,.08)}
        table{width:100%;border-collapse:separate;border-spacing:0;overflow:hidden;border-radius:14px;border:1px solid var(--stroke);background:rgba(255,255,255,.40)}
        th,td{padding:10px;border-bottom:1px solid rgba(20,70,40,.12);text-align:left}
        th{background:rgba(123,220,143,.22);font-weight:900}
        tr:last-child td{border-bottom:none}
        .err{color:var(--danger);font-weight:800}
        .ok{color:var(--ok);font-weight:800}
        small{color:rgba(18,48,29,.62)}
    </style>
</head>
<body>
<div class="topbar">
    <div class="nav">
        <div class="nav-left">
            <span class="brand">Booking (Channel → PMS)</span>
            <a href="principal.php">Cerca</a>
            <a href="reserves.php">Les meves reserves</a>
        </div>
        <div class="nav-right">
            <?php if (!empty($_SESSION['email'])): ?>
                <span> <?= h($_SESSION['email']) ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
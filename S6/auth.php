<?php
require_once __DIR__ . '/config.php';
session_start();
ensure_web_user_table();

function is_logged(): bool {
    return isset($_SESSION['codiClient']) && is_numeric($_SESSION['codiClient']);
}

function require_login(string $returnTo): void {
    if (!is_logged()) {
        header("Location: login.php?returnTo=" . urlencode($returnTo));
        exit();
    }
}
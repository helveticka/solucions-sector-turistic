<?php
require_once __DIR__ . '/auth.php';
session_destroy();
header("Location: principal.php");
exit();
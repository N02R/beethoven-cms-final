<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'], true)) {
    $_SESSION['site_lang'] = $_GET['lang'];
}

$redirect_url = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect_url);
exit;

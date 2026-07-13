<?php
// includes/check_auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// دالة تفحص هل المستخدم الحالي مسؤول أم لا
function isAdmin() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

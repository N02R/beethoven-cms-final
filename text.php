<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
?>

<style>
    :root {
        --primary: #3b82f6; --primary-dark: #2563eb; --bg-soft: #f8fafc;
        --border-color: #dbeafe; --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1); --radius: 20px;
    }
    .custom-modal .modal-content { border-radius: var(--radius); border: none; box-shadow: var(--shadow-md); overflow: hidden; }
    .custom-modal .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--border-color); }
    .custom-modal .modal-title { margin: 0; display: flex; align-items: center; gap: .5rem; font-weight: 700; color: #1e293b; }
    .custom-modal .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border-color); background: var(--bg-soft); }
    .custom-modal .form-control { border-radius: 12px; border: 1px solid var(--border-color); height: 48px; padding: 0 16px; width: 100%; }
    .section-label { font-size: 0.85rem; font-weight: 700; color: #64748b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .btn-premium { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 12px; padding: 12px 24px; font-weight: 600; border: none; width: 100%; }
</style>



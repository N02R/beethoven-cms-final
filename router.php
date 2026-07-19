<?php
// router.php
define('BASE_URL', '/'); // المسار الأساسي للمشروع
require_once 'includes/header.php';

$page = $_GET['page'] ?? 'index';
$allowed = ['index', 'about', 'contact', 'education', 'job', 'guide'];

if (in_array($page, $allowed)) {
    include($page . '.php');
} else {
    include('404.php');
}

require_once 'includes/footer.php';
?>

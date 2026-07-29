<?php
define('ALLOWED_ACCESS', true);
require_once __DIR__ . '/../api/init.php';

use App\Controllers\Admin\DashboardController;

$controller = new DashboardController();
$controller->index();

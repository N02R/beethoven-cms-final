<?php
namespace App\Controllers;
use App\Models\Page;

class DashboardController {
    public function index() {
        $pageCount = Page::getCount(); // جلب العدد
        require_once '../views/dashboard.php';
    }
}

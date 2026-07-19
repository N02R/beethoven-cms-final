<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // استدعاء الواجهة الرئيسية
        require_once ROOT . 'public/pages/home.php';
    }
}
<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        echo "<h1>مرحباً بكِ في متجري الإلكتروني!</h1>";
        echo "<p>هذه هي الصفحة الرئيسية التي تم استدعاؤها عبر الـ Router.</p>";
    }
}

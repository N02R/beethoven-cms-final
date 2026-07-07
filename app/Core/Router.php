<?php
namespace App\Core;
class Router {
    public function dispatch($url) {
        require_once '../views/home.php';
    }
}
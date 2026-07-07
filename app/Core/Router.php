<?php
namespace App\Core;
class Router {
    public function dispatch($url) {
        if ($url === '/login' || $url === '/') {
            (new \App\Controllers\AuthController())->login();
        }
    }
}
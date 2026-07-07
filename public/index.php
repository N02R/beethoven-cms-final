<?php
require_once '../app/Core/Router.php';
$router = new \App\Core\Router();
$router->dispatch($_SERVER['REQUEST_URI']);
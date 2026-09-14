<?php
namespace App\Controllers;

class LegalController {
    public function impressum() {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }
        require_once __DIR__ . '/src/Views/partials/impressum.php';
    }

    public function datenschutz() {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }
        require_once __DIR__ . '/src/Views/partials/datenschutz.php';
    }
}

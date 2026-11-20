<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\HealthModel;
use App\Models\SiteModel;

class HealthController
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];
    private const SESSION_TIMEOUT = 1800;

    public function index(string $langCode = 'de'): void
    {
        $this->initSecureSession();

        $cleanLang = strtolower(trim($langCode));
        if (!in_array($cleanLang, self::SUPPORTED_LANGS, true)) {
            $sessionLang = strtolower(trim((string)($_SESSION['site_lang'] ?? 'de')));
            $cleanLang = in_array($sessionLang, self::SUPPORTED_LANGS, true) ? $sessionLang : 'de';
        }

        $_SESSION['site_lang'] = $cleanLang;
        $current_lang = $cleanLang;
        $current_dir  = ($current_lang === 'ar') ? 'rtl' : 'ltr';

        $lang = $this->loadLanguageArray($current_lang);

        $globalData = SiteModel::getGlobalData();
        $healthData = class_exists(HealthModel::class)
            ? HealthModel::getHealthData($current_lang)
            : [];

        $data = array_merge(
            is_array($globalData) ? $globalData : [],
            is_array($healthData) ? $healthData : []
        );

        $is_admin     = $this->verifyAdminSession();
        $is_logged_in = $is_admin;

        $data['is_admin']        = $is_admin;
        $data['is_logged_in']    = $is_logged_in;
        $data['admin_name']      = $_SESSION['admin_name'] ?? 'Admin';
        $data['current_lang']    = $current_lang;
        $data['current_dir']     = $current_dir;
        $data['lang']            = $lang;
        $data['currentSettings'] = $data;

        $path_prefix = '/';
        $page_css    = ['/assets/css/style.css', '/assets/css/edu-services.css'];
        $page_js     = [];

        extract($data, EXTR_SKIP);

        $viewsRoot = __DIR__ . '/../../Views';

        $header_file = $viewsRoot . '/partials/header.php';
        if (is_file($header_file)) {
            include_once $header_file;
        }

        $view_file = $viewsRoot . '/edu-services/health.php';
        if (is_file($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        $footer_file = $viewsRoot . '/partials/footer.php';
        if (is_file($footer_file)) {
            include_once $footer_file;
        }
    }

    private function initSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
                || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');

            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isSecure,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);

            session_start();
        }
    }

    private function loadLanguageArray(string $langCode): array
    {
        $langDir = is_dir(__DIR__ . '/../../Lang/')
            ? __DIR__ . '/../../Lang/'
            : __DIR__ . '/../../lang/';

        $candidates = [$langCode, 'de', 'en', 'ar'];

        foreach (array_unique($candidates) as $code) {
            $file = $langDir . $code . '.php';
            if (is_file($file)) {
                $loaded = require $file;
                if (is_array($loaded)) {
                    return $loaded;
                }
            }
        }
        return [];
    }

    private function verifyAdminSession(): bool
    {
        if (empty($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            return false;
        }

        $role = (string)($_SESSION['role'] ?? '');
        if (!in_array($role, ['admin', 'super_admin'], true)) {
            return false;
        }

        $lastActivity = (int)($_SESSION['LAST_ACTIVITY'] ?? 0);
        if ($lastActivity > 0 && (time() - $lastActivity) > self::SESSION_TIMEOUT) {
            $_SESSION['is_logged_in'] = false;
            unset($_SESSION['role'], $_SESSION['admin_id'], $_SESSION['admin_name']);
            return false;
        }

        $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        if (!empty($_SESSION['user_agent_hash']) && !hash_equals((string)$_SESSION['user_agent_hash'], $currentAgentHash)) {
            $_SESSION['is_logged_in'] = false;
            unset($_SESSION['role'], $_SESSION['admin_id'], $_SESSION['admin_name']);
            return false;
        }

        $_SESSION['LAST_ACTIVITY'] = time();
        return true;
    }
}

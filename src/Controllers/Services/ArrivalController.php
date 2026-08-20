<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;
use App\Models\ArrivalModel;

class ArrivalController {
    public function index(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $root_path = realpath(__DIR__ . '/../../../');

        // 1. جلب البيانات العامة
        $data = SiteModel::getGlobalData();

        // 2. جلب بيانات صفحة الوصول عبر المودل الجديد
        $data['arrival_data'] = ArrivalModel::getArrivalData();

        // فحص حالة المشرف
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $data['is_admin'] = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        extract($data);

        // استدعاء ملفات العرض
        include_once $root_path . '/src/Views/partials/header.php';
        require_once $root_path . '/src/Views/edu-services/arrival.php';
        include_once $root_path . '/src/Views/partials/footer.php';
    }
}

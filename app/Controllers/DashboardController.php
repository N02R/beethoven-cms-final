<?php
namespace App\Controllers;
use App\Models\Page;
use App\Core\CMS; // تأكدي من استدعاء الـ CMS

class DashboardController {
    
    // واجهة لوحة التحكم الرئيسية
    public function index() {
        $pageCount = Page::getCount(); 
        require_once '../views/dashboard.php';
    }

    // دالة حفظ التعديلات القادمة من الواجهة الأمامية
    public function saveAll() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $name => $value) {
                // الصيغة المتفق عليها: section_key (مثال: hero_title)
                $data = explode('_', $name, 2);
                if(count($data) == 2) {
                    CMS::update('home', $data[0], $data[1], $value);
                }
            }
            // بعد الحفظ نعود للصفحة الرئيسية
            header('Location: /');
            exit;
        }
    }
}

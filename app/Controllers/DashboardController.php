<?php
namespace App\Controllers;

use App\Models\Page;
use App\Core\CMS;

class DashboardController {
    
    /**
     * واجهة لوحة التحكم الرئيسية
     * تعرض الإحصائيات وتوفر التنقل
     */
    public function index() {
        // جلب عدد الصفحات من الـ Model
        $pageCount = Page::getCount(); 
        
        // عرض واجهة الداشبورد
        require_once dirname(__DIR__, 2) . '/views/dashboard.php';
    }

    /**
     * دالة حفظ التعديلات القادمة من الواجهة الأمامية
     * تعمل بنظام الـ AJAX أو الـ Form Submit التقليدي
     */
    public function saveAll() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $name => $value) {
                // تقسيم الاسم المستلم من الفورم (مثال: hero_title -> section=hero, key=title)
                $data = explode('_', $name, 2);
                
                if (count($data) === 2) {
                    $section = $data[0];
                    $key = $data[1];
                    
                    // تحديث المحتوى في قاعدة البيانات
                    CMS::update('home', $section, $key, $value);
                }
            }
            
            // بعد الحفظ نعود للصفحة الرئيسية لمعاينة التغييرات
            header('Location: /');
            exit;
        }
        
        // إذا لم يكن الطلب POST
        http_response_code(405);
        echo "طريقة الطلب غير مسموح بها.";
    }
}

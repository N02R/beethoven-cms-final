<?php
namespace App\Controllers;

use App\Models\Page;
use App\Core\CMS;

class DashboardController {
    
    /**
     * واجهة لوحة التحكم الرئيسية
     */
    public function index() {
        $pageCount = Page::getCount(); 
        require_once dirname(__DIR__, 2) . '/views/dashboard.php';
    }

    /**
     * دالة حفظ التعديلات القادمة من الواجهة الأمامية (Visual Editor)
     * تستقبل البيانات بصيغة JSON وتحدث قاعدة البيانات
     */
    public function saveAll() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // التأكد من أن البيانات التي تصلنا صحيحة
            foreach ($_POST as $name => $value) {
                // تقسيم الاسم المستلم (مثال: hero_title -> section=hero, key=title)
                $data = explode('_', $name, 2);
                
                if (count($data) === 2) {
                    $section = $data[0];
                    $key = $data[1];
                    
                    // تحديث المحتوى في قاعدة البيانات باستخدام الموديل الخاص بك
                    CMS::update('home', $section, $key, $value);
                }
            }
            
            // إرسال استجابة بنجاح العملية ليفهمها الـ JavaScript
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'تم حفظ التعديلات بنجاح']);
            exit;
        }
        
        // في حال فشل الطلب
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'طريقة الطلب غير مسموح بها']);
        exit;
    }
}

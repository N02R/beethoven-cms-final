<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use Exception;
use App\Core\Database;

class MotivationModel {
    
    /**
     * جلب بيانات صفحة خطاب الدافع والتحفيز من جدول site_settings
     */
    public static function getMotivationData(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'motivation_page' LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['setting_value'])) {
                $decoded = json_decode($result['setting_value'], true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (Exception $e) {
            // يمكن تسجيل الخطأ أو تجاهله لضمان عمل الموقع بالقيم الافتراضية
        }
        
        // القيم الافتراضية الآمنة في حال عدم وجود سجل أو حدوث خطأ
        return [
            'page_breadcrumb'     => 'خطاب الدافع / التحفيز',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg3.png',
            'hero_position'       => 'center center',
            'main_title'          => 'خطاب دافع احترافي يعزز طلبك الأكاديمي أو المهني',
            'main_desc'           => "خطاب الدافع/التحفيز هي وثيقة من صفحة واحدة كحد أقصى. تكتُب فيها عن نفسك وتُظهر إهتمامك بالطلب الذي تتقدم إليه و الهدف الذي تريد تحقيقه مثل: (دورة لغة ألمانية، سنة تحضيرية بهدف دخول الجامعة، درجة البكالوريوس أو الماجستير، التدريب أو الزمالة الطبية، إلخ).\nإضافة الى ذلك، يتركز الأمر أكثر على دراستك المستقبلية وخططك المهنية وكيف أن درجة البكالوريوس مثلا التي تتقدم إليها ستساعدك على تحقيق أهدافك المستقبلية. أيضا يمكنك أن تشرح بها الأسباب التي تجعل منك المرشح المثالي لهذا المنصب.",
            'advice_section'      => [
                'title' => 'نصائح سريعة لكتابة خطاب الدافع',
                'items' => [
                    'ابدأ بمقدمة تلخّص دوافعك',
                    'اذكر أمثلة ملموسة (دراسة، تدريب، تجربة)',
                    'اربط خبراتك بأهدافك القادمة',
                    'استخدم لغة واضحة وشخصية',
                    'احصل على مراجعة من مختص أو ناطق أصلي.',
                    'راجع الأخطاء اللغوية جيدًا.'
                ]
            ],
            'download_items'      => [
                [
                    'type'  => 'pdf',
                    'title' => 'خطاب الدافع / التحفيز',
                    'sub'   => 'Example (PDF)',
                    'file'  => '#'
                ],
                [
                    'type'  => 'word',
                    'title' => 'خطاب الدافع / التحفيز',
                    'sub'   => 'Example (Word)',
                    'file'  => '#'
                ]
            ]
        ];
    }

    /**
     * تحديث بيانات صفحة خطاب الدافع
     */
    public static function updateMotivationData(string $jsonData): bool {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'motivation_page'");
            return $stmt->execute([$jsonData]);
        } catch (Exception $e) {
            return false;
        }
    }
}

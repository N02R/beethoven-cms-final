<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

class GuideBlogOneModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSettings(): array
    {
        $stmt = $this->pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = :key LIMIT 1");
        $stmt->execute(['key' => 'guide_blog_one_page']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || empty($result['setting_value'])) {
            return $this->getDefaultData();
        }

        $decoded = json_decode($result['setting_value'], true);
        return is_array($decoded) ? array_merge($this->getDefaultData(), $decoded) : $this->getDefaultData();
    }

    private function getDefaultData(): array
    {
        return [
            'page_breadcrumb' => 'الدليل الشامل',
            'page_breadcrumb_url' => 'guide',
            'hero_img' => 'assets/img/home/image(0).jpg',
            'hero_position' => 'center center',
            'main_title' => 'تعتبر ألمانيا من أفضل الوجهات المفضلة للدراسة لكثير من الطلبة الأجانب',
            'main_desc' => 'في الوقت الراهن، من بين 2.7 مليون طالب يدرسون في الجامعات الألمانية هنالك أكثر من 380 ألف طالب أجنبي -من بينهم الكثير من الطلبه العرب-. وبالإعتماد على أحدث التقارير، فإن هذا العدد يزداد بإستمرار سنوياً.',
            'notes_title' => 'ملاحظات هامة جداً',
            'note_1_bold' => 'لدى الجامعات الألمانية مواعيد مختلفة لتقديم طلبات التسجيل، ويوجد غالباً مواعيد لفصول الشتاء و الصيف:',
            'note_winter' => 'تبدأ عملية التقديم في مارس، يكون الموعد النهائي للتقديم هو 15 يوليو ويبدأ الفصل الدراسي في أكتوبر.',
            'note_summer' => 'تبدأ عملية التقديم في سبتمبر، يكون الموعد النهائي للتقديم هو 15 يناير ويبدأ الفصل الدراسي في مارس / أبريل.',
            'note_2_text' => 'الأخذ بعين الإعتبار أن عملية التسجيل لدى الجامعة و تجهيز الوثائق اللازمة للتأشيرة الدخول إلى ألمانيا تستغرق من شهرين على الأقل لغاية أكثر من أربعة أشهر بحسب الحالة، لذلك ننصح بشدة البدء باكراً بإجراءات التسجيل.',
            'note_3_title' => 'للإجابة على أكثر الأسئلة التي يطرحُها أغلب الطلاب، نقول:',
            'faq_1' => 'الأوراق المطلوبة للحصول على قبول جامعي يَعتمِد كثيراً على الدرجة الجامِعية التي تريد أن تَحصل عليها و كذلك المستوى العلمي الذي حصلت عليه سابقاً...',
            'faq_2_prefix' => 'لمعرفة',
            'faq_2_url' => 'visa',
            'faq_2_link_text' => 'متطلبات تأشيرة الدراسة',
            'faq_2_suffix' => 'لدى السفارة أو القنصلية الألمانية، أيضاً قمنا بجمع معلومات قيِّمة تجدونها في أسفل الصفحة. علماً بأن هذه المُتطلبات تختلف بحسب نوع التأشيرة و الدولة.',
            'why_study_title' => 'لماذا الدراسة في ألمانيا؟',
            'why_study_desc' => 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي',
            'content_sections' => [],
            'timeline_title' => 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS',
            'timeline_desc' => 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.',
            'timeline_steps' => []
        ];
    }
}

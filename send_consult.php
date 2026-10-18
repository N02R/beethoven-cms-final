<?php
/**
 * send_consult.php - معالجة الاستشارات مع إرسال بريد حقيقي وتخزين آمن (DSGVO)
 */

// تطبيق إعدادات أمان الجلسات والكوكيز الحديثة
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

// التحقق من أن الطلب تم عبر POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 1. التحقق من الموافقة القادمة من الـ Popup
if (!isset($_POST['privacy_consent']) || $_POST['privacy_consent'] !== 'on') {
    echo json_encode(['success' => false, 'message' => 'يجب الموافقة على سياسة الخصوصية'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. التحقق من صحة وتطهير البريد الإلكتروني بشكل آمن وصارم
$raw_email = $_POST['email'] ?? '';
$email = filter_var(trim($raw_email), FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني غير صالح'], JSON_UNESCAPED_UNICODE);
    exit;
}

$file = __DIR__ . '/announcement_config.json';

// قراءة ملف الـ JSON بشكل آمن مع التحقق من سلامة البيانات
$data = [];
if (file_exists($file)) {
    $json_content = file_get_contents($file);
    $decoded = json_decode($json_content, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}

if (!isset($data['consultation_emails']) || !is_array($data['consultation_emails'])) {
    $data['consultation_emails'] = [];
}

// منع التكرار
foreach ($data['consultation_emails'] as $item) {
    if (isset($item['email']) && strcasecmp($item['email'], $email) === 0) {
        echo json_encode(['success' => true, 'message' => 'هذا البريد مسجل مسبقاً، سنتواصل معك قريباً!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 3. تخزين البيانات في ملف الـ JSON مع تشفير IP العميل (حماية الخصوصية DSGVO)
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$data['consultation_emails'][] = [
    'email'         => $email,
    'date'          => date('Y-m-d H:i:s'),
    'ip_hash'       => hash('sha256', $client_ip),
    'consent_given' => true
];

// حفظ البيانات مع تفعيل القفل الحصري (LOCK_EX) لمنع تضارب الكتابة
$json_encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$saved = false;
if ($json_encoded !== false) {
    $saved = file_put_contents($file, $json_encoded, LOCK_EX);
}

if (!$saved) {
    echo json_encode(['success' => false, 'message' => 'خطأ في حفظ البيانات محلياً'], JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================================
// 4. إرسال البريد الإلكتروني الحقيقي (للإدارة وللعميل)
// ==========================================
$admin_email = "info@beethoven-city.com"; // البريد الخاص بك أو بإدارة الموقع
$subject_admin = "طلب استشارة مجانية جديد عبر الموقع";

// تأمين رؤوس البريد الإلكتروني (Email Headers) ضد ثغرات الـ Header Injection
$safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);
$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: noreply@beethoven-city.com" . "\r\n";
$headers .= "Reply-To: " . $safe_email . "\r\n";

// رسالة المدير (مع تأمين مخرجات البريد بـ htmlspecialchars لمنع أي حقن برمجي محتمل)
$admin_message = "
<html>
<head><title>طلب استشارة جديد</title></head>
<body dir='rtl' style='font-family: Tahoma, sans-serif;'>
    <h2 style='color: #0d6efd;'>لديك طلب استشارة مجانية جديد!</h2>
    <p><strong>البريد الإلكتروني للعميل:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>
    <p><strong>تاريخ الطلب:</strong> " . date('Y-m-d H:i:s') . "</p>
    <p>تم حفظ الطلب بنجاح في نظام لوحة التحكم وملف الـ JSON الخاص بالموقع.</p>
</body>
</html>
";

// إرسال الإشعار للمدير
@mail($admin_email, $subject_admin, $admin_message, $headers);

// رسالة تأكيد افتراضية للعميل (Auto-reply)
$subject_client = "تأكيد استلام طلب الاستشارة المجانية - Beethoven City";
$client_message = "
<html>
<head><title>تأكيد الطلب</title></head>
<body dir='rtl' style='font-family: Tahoma, sans-serif;'>
    <h2 style='color: #0d6efd;'>مرحباً بك في Beethoven City Services</h2>
    <p>لقد استلمنا طلبك للحصول على استشارة مجانية بنجاح.</p>
    <p>سيقوم فريق الخبراء لدينا بمراجعة طلبك والتواصل معك عبر هذا البريد الإلكتروني في أقرب وقت ممكن.</p>
    <br>
    <p>شكراً لثقتك بنا.</p>
</body>
</html>
";

// إرسال الرد التلقائي للعميل
@mail($safe_email, $subject_client, $client_message, "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: noreply@beethoven-city.com\r\n");

// الرد النهائي بنجاح العملية
echo json_encode([
    'success' => true, 
    'message' => 'تم إرسال طلبك بنجاح وتم إشعار الإدارة. سنتواصل معك قريباً!'
], JSON_UNESCAPED_UNICODE);
exit;
?>

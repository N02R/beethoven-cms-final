<?php
/**
 * send_consult.php - معالجة الاستشارات مع إرسال بريد حقيقي وتخزين آمن (DSGVO)
 */
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// 1. التحقق من الموافقة القادمة من الـ Popup
if (!isset($_POST['privacy_consent']) || $_POST['privacy_consent'] !== 'on') {
    echo json_encode(['success' => false, 'message' => 'يجب الموافقة على سياسة الخصوصية']);
    exit;
}

// 2. التحقق من صحة البريد الإلكتروني
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني غير صالح']);
    exit;
}

$file = __DIR__ . '/announcement_config.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!isset($data['consultation_emails']) || !is_array($data['consultation_emails'])) {
    $data['consultation_emails'] = [];
}

// منع التكرار
foreach ($data['consultation_emails'] as $item) {
    if (isset($item['email']) && $item['email'] === $email) {
        echo json_encode(['success' => true, 'message' => 'هذا البريد مسجل مسبقاً، سنتواصل معك قريباً!']);
        exit;
    }
}

// 3. تخزين البيانات في ملف الـ JSON
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$data['consultation_emails'][] = [
    'email'         => $email,
    'date'          => date('Y-m-d H:i:s'),
    'ip_hash'       => hash('sha256', $client_ip),
    'consent_given' => true
];

$saved = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if (!$saved) {
    echo json_encode(['success' => false, 'message' => 'خطأ في حفظ البيانات محلياً']);
    exit;
}

// ==========================================
// 4. إرسال البريد الإلكتروني الحقيقي (للإدارة وللعميل)
// ==========================================
$admin_email = "info@beethoven-city.com"; // البريد الخاص بك أو بإدارة الموقع
$subject_admin = "طلب استشارة مجانية جديد عبر الموقع";
$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: noreply@beethoven-city.com" . "\r\n";

// رسالة المدير
$admin_message = "
<html>
<head><title>طلب استشارة جديد</title></head>
<body dir='rtl' style='font-family: Tahoma, sans-serif;'>
    <h2 style='color: #0d6efd;'>لديك طلب استشارة مجانية جديد!</h2>
    <p><strong>البريد الإلكتروني للعميل:</strong> {$email}</p>
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
@mail($email, $subject_client, $client_message, $headers);

// الرد النهائي بنجاح العملية
echo json_encode([
    'success' => true, 
    'message' => 'تم إرسال طلبك بنجاح وتم إشعار الإدارة. سنتواصل معك قريباً!'
]);
exit;

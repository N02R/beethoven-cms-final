<?php
/**
 * send_consult.php - معالجة متوافقة مع القواعد الألمانية الأوروبية (DSGVO / GDPR)
 */
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// 1. التحقق من الموافقة الصريحة على سياسة الخصوصية (الشرط الألماني الإلزامي)
if (!isset($_POST['privacy_consent']) || $_POST['privacy_consent'] !== 'on') {
    echo json_encode(['success' => false, 'message' => 'Die Einwilligung zum Datenschutz ist erforderlich.']);
    exit;
}

// 2. التحقق من صحة البريد الإلكتروني
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Ungültige E-Mail-Adresse.']);
    exit;
}

$file = __DIR__ . '/announcement_config.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!isset($data['consultation_emails']) || !is_array($data['consultation_emails'])) {
    $data['consultation_emails'] = [];
}

// 3. منع التكرار وتوثيق السجل
foreach ($data['consultation_emails'] as $item) {
    if (isset($item['email']) && $item['email'] === $email) {
        // لأسباب ألمانية أمنية، نرد بنجاح عام حتى لا يتم كشف ما إذا كان الإيميل مسجلاً أم لا (Email Enumeration Protection)
        echo json_encode(['success' => true, 'message' => 'Vielen Dank! Falls die Adresse neu ist, wurden Ihre Daten gespeichert.']);
        exit;
    }
}

// 4. تسجيل البيانات مع التوقيت وعنوان الـ IP المشفر (لإثبات تاريخ الموافقة القانوني)
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
// إخفاء جزء من الـ IP لحماية خصوصية المستخدم (Anonymisierung gemäß DSGVO)
$anonymized_ip = preg_replace('/\.\d+$/', '.0', $client_ip);

$data['consultation_emails'][] = [
    'email'       => $email,
    'date'        => date('Y-m-d H:i:s'),
    'ip_hash'     => hash('sha256', $client_ip), // تخزين الـ Hash للأمان بدلاً من الـ IP الصريح
    'consent_given' => true
];

// 5. حفظ الملف بصورة آمنة
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'Erfolgreich gesendet!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Serverfehler beim Speichern.']);
}
exit;

<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// التأكد من الموافقة القادمة من الـ Popup
if (!isset($_POST['privacy_consent']) || $_POST['privacy_consent'] !== 'on') {
    echo json_encode(['success' => false, 'message' => 'يجب الموافقة على سياسة الخصوصية']);
    exit;
}

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

foreach ($data['consultation_emails'] as $item) {
    if (isset($item['email']) && $item['email'] === $email) {
        echo json_encode(['success' => true, 'message' => 'هذا البريد مسجل مسبقاً، سنتواصل معك قريباً!']);
        exit;
    }
}

$data['consultation_emails'][] = [
    'email'         => $email,
    'date'          => date('Y-m-d H:i:s'),
    'consent_given' => true
];

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم الإرسال بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في حفظ البيانات']);
}
exit;

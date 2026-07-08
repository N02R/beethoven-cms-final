<?php
session_start();

// التحقق من أن المستخدم هو المدير فقط
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بالتعديل']);
    exit;
}

// قراءة البيانات المرسلة من JavaScript
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data) {
    // ضعي هنا كود الاتصال بقاعدة البيانات الخاص بكِ
    // مثال (استبدلي البيانات ببيانات الاتصال الفعلية):
    $db = new PDO('mysql:host=localhost;dbname=اسم_قاعدة_بياناتك', 'اسم_المستخدم', 'كلمة_المرور');

    $stmt = $db->prepare("UPDATE site_content SET content = ? WHERE page_key = ? AND section_key = ? AND field_key = ?");
    $result = $stmt->execute([
        $data['content'], 
        $data['page'], 
        $data['section'], 
        $data['field']
    ]);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'فشل التحديث']);
    }
}
?>

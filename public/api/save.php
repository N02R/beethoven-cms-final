<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    exit('غير مصرح');
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo "لم يتم استقبال بيانات!";
    exit;
}

try {
    // تأكدي من مسار الاتصال بقاعدة البيانات الخاص بك
    $db = new PDO('mysql:host=127.0.0.1;dbname=beethoven_db;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("UPDATE site_content SET content = ? WHERE page_key = ? AND section_key = ? AND field_key = ?");
    $result = $stmt->execute([$data['content'], $data['page'], $data['section'], $data['field']]);

    if ($result) {
        echo "تم التحديث بنجاح";
    } else {
        echo "فشل تنفيذ الاستعلام";
    }
} catch (PDOException $e) {
    echo "خطأ قاعدة البيانات: " . $e->getMessage();
}

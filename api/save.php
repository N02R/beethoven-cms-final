<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    exit('غير مسموح');
}

$data = json_decode(file_get_contents('php://input'), true);

// الاتصال بقاعدة البيانات (استخدمي الاتصال الموجود في مشروعك)
$db = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');

$stmt = $db->prepare("UPDATE site_content SET content = ? WHERE page_key = ? AND section_key = ? AND field_key = ?");
$stmt->execute([$data['content'], $data['page'], $data['section'], $data['field']]);

echo json_encode(['status' => 'success']);

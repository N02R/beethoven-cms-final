<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

class GuideBlog1Model {

    /**
     * جلب اتصال قاعدة البيانات بالطريقة المعتمدة الآمنة للمشروع
     */
    private static function getConnection(): PDO {
        // الاعتماد على SiteModel للحصول على اتصال PDO النشط في المشروع لتجنب خطأ عدم وجود الكلاس
        return SiteModel::getPdoConnection() ?? \App\Core\Database::getInstance();
    }

    /**
     * جلب بيانات صفحة المقال من جدول guide_blog1_content المستقل
     */
    public static function getData(): array {
        try {
            $db = self::getConnection();
            $stmt = $db->prepare("SELECT * FROM guide_blog1_content WHERE id = 1 LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return [
                    'hero_img'        => 'assets/img/home/image(0).jpg',
                    'hero_position'   => 'center center',
                    'main_title'      => '',
                    'main_desc'       => '',
                    'notes_title'     => 'ملاحظات هامة جداً',
                    'note_1_bold'     => '',
                    'note_winter'     => '',
                    'note_summer'     => '',
                    'note_2_text'     => '',
                    'note_3_title'    => '',
                    'faq_1'           => '',
                    'faq_2_prefix'    => '2. لمعرفة',
                    'faq_2_url'       => 'contact',
                    'faq_2_link_text' => 'متطلبات تأشيرة الدراسة',
                    'faq_2_suffix'    => ''
                ];
            }

            return $data;
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * تحديث بيانات صفحة المقال مع معالجة رفع صورة الهيرو الجديدة
     */
    public static function updateData(array $postData, ?array $filesData = null): bool {
        try {
            $db = self::getConnection();
            $currentData = self::getData();

            $hero_img = $currentData['hero_img'] ?? 'assets/img/home/image(0).jpg';

            // معالجة رفع صورة الهيرو الجديدة إذا تم اختيارها
            if ($filesData && isset($filesData['hero_img']) && $filesData['hero_img']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $filesData['hero_img']['tmp_name'];
                $fileName = $filesData['hero_img']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = 'hero_guide_' . time() . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../../public/assets/uploads/';
                    
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    
                    if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                        $hero_img = 'assets/uploads/' . $newFileName;
                    }
                }
            }

            $stmt = $db->prepare("UPDATE guide_blog1_content SET 
                hero_img = :hero_img,
                hero_position = :hero_position,
                main_title = :main_title,
                main_desc = :main_desc,
                notes_title = :notes_title,
                note_1_bold = :note_1_bold,
                note_winter = :note_winter,
                note_summer = :note_summer,
                note_2_text = :note_2_text,
                note_3_title = :note_3_title,
                faq_1 = :faq_1,
                faq_2_prefix = :faq_2_prefix,
                faq_2_url = :faq_2_url,
                faq_2_link_text = :faq_2_link_text,
                faq_2_suffix = :faq_2_suffix
                WHERE id = 1");

            return $stmt->execute([
                ':hero_img'        => $hero_img,
                ':hero_position'   => trim($postData['hero_position'] ?? 'center center'),
                ':main_title'      => trim($postData['main_title'] ?? ''),
                ':main_desc'       => trim($postData['main_desc'] ?? ''),
                ':notes_title'     => trim($postData['notes_title'] ?? 'ملاحظات هامة جداً'),
                ':note_1_bold'     => trim($postData['note_1_bold'] ?? ''),
                ':note_winter'     => trim($postData['note_winter'] ?? ''),
                ':note_summer'     => trim($postData['note_summer'] ?? ''),
                ':note_2_text'     => trim($postData['note_2_text'] ?? ''),
                ':note_3_title'    => trim($postData['note_3_title'] ?? ''),
                ':faq_1'           => trim($postData['faq_1'] ?? ''),
                ':faq_2_prefix'    => trim($postData['faq_2_prefix'] ?? '2. لمعرفة'),
                ':faq_2_url'       => trim($postData['faq_2_url'] ?? 'contact'),
                ':faq_2_link_text' => trim($postData['faq_2_link_text'] ?? 'متطلبات تأشيرة الدراسة'),
                ':faq_2_suffix'    => trim($postData['faq_2_suffix'] ?? '')
            ]);

        } catch (\PDOException $e) {
            return false;
        }
    }
}

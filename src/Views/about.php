<?php

declare(strict_types=1);

namespace App\Controllers;

use PDO;

class AboutController
{
    /**
     * عرض صفحة من نحن مع جلب كافة بياناتها من قاعدة البيانات
     */
    public function index(): void
    {
        try {
            $pdo = \App\Config\Database::getConnection();
        } catch (\Exception $e) {
            error_log("Database connection failed in AboutController: " . $e->getMessage());
            die("Database connection failed.");
        }

        // جلب كافة إعدادات صفحة من نحن دفعة واحدة من جدول site_settings
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key LIKE 'about_%' OR setting_key IN ('services', 'team_title', 'team_desc', 'team_items', 'partners_title', 'partners_items')");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        // تجهيز مصفوفة البيانات ($data) لتتوافق تماماً مع الهيكل المطلوب في صفحة about.php
        $data = [
            'about' => json_decode($rawSettings['about_section'] ?? '{}', true),
            
            'services_section_title' => $rawSettings['about_services_title'] ?? 'خدماتنا المميزة',
            'services'               => json_decode($rawSettings['services'] ?? '[]', true),
            
            'team_title'             => $rawSettings['team_title'] ?? 'فريق العمل',
            'team_desc'              => $rawSettings['team_desc'] ?? '',
            'team_members'           => json_decode($rawSettings['team_items'] ?? '[]', true),
            
            'about_counts'           => json_decode($rawSettings['about_counts'] ?? '[]', true),
            
            'partners_title'         => $rawSettings['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا',
            'partners_items'         => json_decode($rawSettings['partners_items'] ?? '[]', true),
        ];

        // التحقق من صلاحيات المدير لعرض زر التعديل (Edit Pen) في حال تسجيل الدخول
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

        $root_path = realpath(__DIR__ . '/../../');
        $view_file = $root_path . '/src/Views/about.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "About View file not found.";
        }
    }
}

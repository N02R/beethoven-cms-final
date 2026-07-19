<?php
namespace App\Services;

class AuditLoggerService {
    private string $logFile;

    public function __construct() {
        // نضع السجلات في مجلد خاص ومحمي خارج نطاق الوصول المباشر
        $this->logFile = __DIR__ . '/../../storage/logs/activity.log';
    }

    /**
     * تسجيل أي نشاط في النظام
     */
    public function log(string $action, string $details = ''): void {
        $username = $_SESSION['username'] ?? 'Guest';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        $logEntry = sprintf("[%s] | User: %s | Action: %s | Details: %s | IP: %s\n", 
            $timestamp, $username, $action, $details, $ip);
        
        // استخدام FILE_APPEND لإضافة سجل جديد دون مسح القديم، مع LOCK_EX لضمان عدم تلف الملف
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

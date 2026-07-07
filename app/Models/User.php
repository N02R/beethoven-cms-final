<?php
namespace App\Models;

use App\Core\Database;

class User {
    public static function authenticate($username, $password) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user; // تسجيل دخول ناجح
        }
        return false;
    }
}
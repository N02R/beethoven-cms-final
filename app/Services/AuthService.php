<?php
namespace App\Services;

class AuthService {
    private string $usersFile = __DIR__ . '/../../storage/users.json';

    public function login(string $username, string $password): bool {
        $users = json_decode(file_get_contents($this->usersFile), true);

        if (isset($users[$username])) {
            // استخدام password_verify الآمن جداً ضد هجمات التخمين
            if (password_verify($password, $users[$username]['password'])) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin';
                return true;
            }
        }
        return false;
    }
}

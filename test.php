<?php
// كلمة المرور التي تريدين اعتمادها
$password = 'Admin123!';

// توليد التشفير الآمن باستخدام PHP
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// طباعة التشفير لتقومي بنسخه
echo "التشفير الصحيح هو: <br><br><code>" . htmlspecialchars($hashedPassword) . "</code>";

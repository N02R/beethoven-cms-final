<?php
session_start();
if (!isset($_SESSION['is_admin'])) die('غير مصرح لك');

use App\Core\CMS;

foreach ($_POST as $name => $value) {
    // الصيغة: section_key (مثال: hero_title)
    $data = explode('_', $name, 2);
    if(count($data) == 2) {
        CMS::update('home', $data[0], $data[1], $value);
    }
}
header('Location: /'); // العودة للموقع بعد الحفظ

namespace App\Models;
use App\Config\Database;
use PDO;

class HomeModel {
    public function getHomeData() {
        $db = Database::getConnection();
        // استعلام لجلب بيانات الصفحة الرئيسية من قاعدة البيانات
        // $stmt = $db->query("SELECT * FROM home_content");
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

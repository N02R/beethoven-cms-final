<?php
// استدعاء قاعدة البيانات
use App\Core\Database;
$db = new Database();

// جلب النصوص من قاعدة البيانات (الصفحة: 'home'، القسم: 'hero')
$title = $db->getContent('home', 'hero', 'title') ?? 'العنوان الافتراضي';
$subtitle = $db->getContent('home', 'hero', 'subtitle') ?? 'الوصف الافتراضي';
?>

<section class="hero py-5" aria-label="قسم البداية">
    <div class="custom-container">
      <div class="hero-container">
        <div class="hero-content">
          
          <h1 class="editable" data-section="hero" data-key="title">
              <?php echo htmlspecialchars($title); ?>
          </h1>
          
          <p class="editable" data-section="hero" data-key="subtitle">
              <?php echo htmlspecialchars($subtitle); ?>
          </p>
          
          <a href="#contact" class="btn btn-lg hero-btn">احجز استشارتك الآن</a>
        </div>
      </div>
    </div>
</section>

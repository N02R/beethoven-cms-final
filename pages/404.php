<?php 
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<?php 
// تعريف ملفات الـ CSS الخاصة بصفحة 404
$page_css = [
    'assets/css/style.css',
    'assets/css/responsive-index.css'
];

// استدعاء الهيدر المشترك

?>

<style>
  .error-404-section {
    text-align: center;
    padding: clamp(60px, 10vw, 140px) clamp(16px, 5vw, 80px);
    background-color: #fff;
  }

  /* --- الرقم الكبير --- */
  .error-number-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: clamp(20px, 3vw, 40px);
  }

  .error-digits {
    font-size: clamp(100px, 18vw, 260px);
    font-weight: 900;
    color: #66aeee;
    line-height: 1;
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: clamp(4px, 1vw, 16px);
  }

  /* --- رائد الفضاء --- */
  .astronaut-wrap {
    position: relative;
    z-index: 2;
  }

  .astronaut-svg {
    width: clamp(65px, 11vw, 145px);
    height: auto;
  }

  /* انيميشن الطفو */
  .float-anim {
    animation: floatAnim 3.5s ease-in-out infinite;
  }

  @keyframes floatAnim {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-20px); }
  }

  /* --- النصوص --- */
  .error-404-title {
    font-size: clamp(24px, 4.5vw, 48px);
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: clamp(10px, 1.5vw, 16px);
  }

  .error-404-desc {
    font-size: clamp(14px, 1.8vw, 18px);
    color: #6c757d;
    line-height: 1.9;
    max-width: 540px;
    margin: 0 auto clamp(28px, 4vw, 52px);
  }

  /* --- الزر --- */
  .btn-go-home {
    display: inline-block;
    padding: clamp(10px, 1.5vw, 16px) clamp(32px, 5vw, 64px);
    border: 1.5px solid #1a6cff;
    border-radius: 6px;
    color: #1a6cff;
    font-family: 'Cairo', sans-serif;
    font-size: clamp(14px, 1.6vw, 18px);
    font-weight: 600;
    text-decoration: none;
    background: transparent;
    transition: background 0.25s ease, color 0.25s ease;
    cursor: pointer;
  }

  .btn-go-home:hover {
    background: #1a6cff;
    color: #fff;
  }

  /* --- ريسبونسف للشاشات الكبيرة جداً (24-27 بوصة) --- */
  @media (min-width: 1920px) {
    .error-404-section {
      padding: 140px 120px;
    }
    .error-404-title {
      font-size: 52px;
    }
    .error-404-desc {
      font-size: 20px;
      max-width: 680px;
    }
    .btn-go-home {
      font-size: 20px;
      padding: 18px 72px;
    }
  }

  /* --- موبايل --- */
  @media (max-width: 576px) {
    .error-digits {
      gap: 2px;
    }
  }
</style>

<!-- ========== محتوى القسم ========== -->
<section class="error-404-section">
  <div class="container-fluid">

    <!-- الرقم مع رائد الفضاء -->
    <div class="error-number-wrap">
      <div class="error-digits">
        <span>4</span>

        <!-- رائد الفضاء بين الأرقام -->
        <div class="astronaut-wrap">
          <div class="float-anim">
            <svg class="astronaut-svg" viewBox="0 0 120 160" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- جسم البدلة -->
              <ellipse cx="60" cy="105" rx="32" ry="38" fill="#E8EDF5" stroke="#C5CED8" stroke-width="2"/>
              <!-- خوذة -->
              <circle cx="60" cy="55" r="30" fill="#E8EDF5" stroke="#C5CED8" stroke-width="2"/>
              <!-- قناع الخوذة -->
              <ellipse cx="60" cy="57" rx="20" ry="18" fill="#4FA3E8" opacity="0.9"/>
              <ellipse cx="53" cy="50" rx="6" ry="5" fill="white" opacity="0.35"/>
              <!-- لوحة الصدر -->
              <rect x="45" y="90" width="30" height="22" rx="5" fill="#D0D8E6" stroke="#B5BFC9" stroke-width="1.5"/>
              <circle cx="54" cy="101" r="3" fill="#1a6cff"/>
              <circle cx="63" cy="101" r="3" fill="#FF5E5E"/>
              <rect x="49" y="107" width="22" height="3" rx="1.5" fill="#B5BFC9"/>
              <!-- الذراع الأيسر -->
              <ellipse cx="28" cy="108" rx="10" ry="18" fill="#E8EDF5" stroke="#C5CED8" stroke-width="1.5" transform="rotate(-15 28 108)"/>
              <!-- الذراع الأيمن (يلوح) -->
              <ellipse cx="92" cy="100" rx="10" ry="18" fill="#E8EDF5" stroke="#C5CED8" stroke-width="1.5" transform="rotate(25 92 100)"/>
              <!-- قفازات -->
              <ellipse cx="98" cy="88" rx="7" ry="6" fill="#D0D8E6" stroke="#B5BFC9" stroke-width="1.5"/>
              <ellipse cx="21" cy="120" rx="7" ry="6" fill="#D0D8E6" stroke="#B5BFC9" stroke-width="1.5"/>
              <!-- الأرجل -->
              <rect x="38" y="136" width="16" height="18" rx="7" fill="#E8EDF5" stroke="#C5CED8" stroke-width="1.5"/>
              <rect x="66" y="136" width="16" height="18" rx="7" fill="#E8EDF5" stroke="#C5CED8" stroke-width="1.5"/>
              <!-- الأحذية -->
              <ellipse cx="46" cy="154" rx="10" ry="5" fill="#C5CED8"/>
              <ellipse cx="74" cy="154" rx="10" ry="5" fill="#C5CED8"/>
              <!-- الهوائي -->
              <line x1="60" y1="25" x2="60" y2="10" stroke="#C5CED8" stroke-width="2" stroke-linecap="round"/>
              <circle cx="60" cy="8" r="3" fill="#1a6cff"/>
            </svg>
          </div>
        </div>
        <!-- /رائد الفضاء -->

        <span>4</span>
      </div>
    </div>
    <!-- /الرقم مع رائد الفضاء -->

    <!-- العنوان -->
    <h1 class="error-404-title">الصفحة غير متوفرة!</h1>

    <!-- الوصف -->
    <p class="error-404-desc">
      هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص..
    </p>

    <!-- زر الرجوع للرئيسية -->
    <a href="index.php" class="btn-go-home">العودة الى الرئيسية</a>

  </div>
</section>
<!-- ========== /نهاية القسم ========== -->



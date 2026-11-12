<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Home View (Gold Standard MVC, DSGVO-Compliant & Security Hardened)
 * المرجع القياسي المعتمد لجميع صفحات العرض في النظام.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// نصوص احتياطية مترجمة للغات الثلاث في حال خلو الأقسام من البيانات
$emptyStateTexts = [
    'de' => [
        'no_services' => 'Derzeit sind keine Dienstleistungen verfügbar.',
        'no_features' => 'Derzeit sind keine Vorteile hinterlegt.',
        'no_videos'   => 'Derzeit sind keine Videos verfügbar.',
        'no_guide'    => 'Derzeit sind keine Leitfaden-Artikel verfügbar.',
        'no_faq'      => 'Derzeit sind keine häufig gestellten Fragen verfügbar.',
        'read_more'   => 'Weiterlesen'
    ],
    'en' => [
        'no_services' => 'No services are currently available.',
        'no_features' => 'No features are currently available.',
        'no_videos'   => 'No review videos are currently available.',
        'no_guide'    => 'No guide articles are currently available.',
        'no_faq'      => 'No frequently asked questions are currently available.',
        'read_more'   => 'Read More'
    ],
    'ar' => [
        'no_services' => 'لا توجد خدمات مضافة حالياً.',
        'no_features' => 'لا توجد مميزات مضافة حالياً.',
        'no_videos'   => 'لا توجد فيديوهات للعرض حالياً.',
        'no_guide'    => 'لا توجد مقالات مضافة في الدليل حالياً.',
        'no_faq'      => 'لا توجد أسئلة شائعة مضافة حالياً.',
        'read_more'   => 'اقرأ المزيد'
    ]
];
$emptyTxt = $emptyStateTexts[$current_lang];

// 1. دالة موحدة لاستخراج بيانات أي قسم متعدد اللغات بأمان تام
$getLocalizedData = function(string $settingKey, mixed $default = []) use ($current_lang, $data, $currentSettings): mixed {
    $raw = $data[$settingKey] ?? ($currentSettings[$settingKey] ?? get_setting($settingKey, $default));
    
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $parsed = is_array($decoded) ? $decoded : $default;
    } else {
        $parsed = is_array($raw) ? $raw : $default;
    }

    if (!is_array($parsed)) {
        return $default;
    }

    if (isset($parsed[$current_lang]) && is_array($parsed[$current_lang])) {
        return $parsed[$current_lang];
    }
    if (isset($parsed['de']) && is_array($parsed['de'])) {
        return $parsed['de'];
    }
    if (isset($parsed['en']) && is_array($parsed['en'])) {
        return $parsed['en'];
    }
    if (isset($parsed['ar']) && is_array($parsed['ar'])) {
        return $parsed['ar'];
    }

    return $parsed;
};

// 2. دالة تنقية الروابط وربطها بتسلسل اللغة الموحد (منع ثغرات XSS و Open Redirect)
$buildLocalizedUrl = function(?string $rawUrl) use ($current_lang): string {
    $url = trim((string)($rawUrl ?? '#'));
    if ($url === '' || $url === '#') {
        return '#';
    }
    // حظر البروتوكولات الخطرة مثل javascript: أو data:
    if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
        return '#';
    }
    // الروابط الخارجية أو الهاتف/البريد تبقى كما هي
    if (preg_match('/^(https?:\/\/|mailto:|tel:|#)/i', $url)) {
        return $url;
    }
    // إزالة أي بادئة لغة قديمة وإضافة بادئة اللغة الحالية
    $cleanPath = preg_replace('/^(ar|en|de)\//i', '', ltrim($url, '/'));
    return '/' . $current_lang . '/' . $cleanPath;
};

// 3. دالة تحويل روابط يوتيوب إلى صيغة YouTube No-Cookie المتوافقة مع معايير الخصوصية الألمانية DSGVO
$sanitizeVideoEmbedUrl = function(?string $rawUrl): string {
    $url = trim((string)($rawUrl ?? ''));
    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }
    $parsed = parse_url($url);
    $host = strtolower($parsed['host'] ?? '');
    $allowedHosts = ['www.youtube.com', 'youtube.com', 'youtu.be', 'www.youtube-nocookie.com', 'youtube-nocookie.com'];
    if (!in_array($host, $allowedHosts, true)) {
        return '';
    }
    // استخراج معرف الفيديو وتحويله إلى نطاق الخصوصية المعتمد في ألمانيا
    if (preg_match('/(?:embed\/|v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?rel=0';
    }
    return str_replace('www.youtube.com', 'www.youtube-nocookie.com', $url);
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= HERO SECTION START ================= -->
<section class="hero py-5 editable-wrapper" aria-label="Hero Section" style="position: relative;">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#heroEditModal" title="<?php echo htmlspecialchars($lang['edit_hero_section'] ?? 'Edit Hero', ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $hero = $getLocalizedData('hero', []);
    
    $hero_title  = $hero['title'] ?? ($hero['hero_title'] ?? 'Beethoven City Services');
    $hero_desc   = $hero['desc'] ?? ($hero['hero_desc'] ?? '');
    $hero_btn    = $hero['btn_text'] ?? ($lang['read_more'] ?? $emptyTxt['read_more']);
    $hero_url    = $buildLocalizedUrl($hero['btn_url'] ?? '#');
    $bg_img_path = $hero['img'] ?? null;
    
    $hero_bg = get_image_url($bg_img_path, '/assets/img/home/home1.png');
    ?>
    
    <div class="hero-container" style="background: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>') center/cover no-repeat;">
      <div class="hero-content">
        <h1><?php echo htmlspecialchars((string)$hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars((string)$hero_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="<?php echo htmlspecialchars($hero_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-lg hero-btn">
          <?php echo htmlspecialchars((string)$hero_btn, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- ================= HERO SECTION END ================= -->

<!-- ================= SERVICES SECTION START ================= -->
<section class="services py-5 editable-wrapper" style="position: relative;">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#servicesEditModal" title="<?php echo htmlspecialchars($lang['edit_services_section'] ?? 'Edit Services', ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $s_data = $getLocalizedData('services', []);
    
    $sec_title = $s_data['section_title'] ?? ($s_data['title'] ?? '');
    $sec_desc  = $s_data['section_subtitle'] ?? ($s_data['desc'] ?? '');
    $services  = $s_data['items'] ?? [];
    ?>

    <?php if (!empty($sec_title)): ?>
      <h2 class="mb-3 sec-title">
          <?php echo htmlspecialchars((string)$sec_title, ENT_QUOTES, 'UTF-8'); ?>
      </h2>
    <?php endif; ?>
    
    <?php if (!empty($sec_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars((string)$sec_desc, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (!empty($services) && is_array($services)): ?>
        <?php foreach ($services as $service): 
          if (!is_array($service)) continue;
          $service_img   = get_image_url($service['img'] ?? null, '/assets/img/home/default.jpg');
          $service_title = $service['title'] ?? '';
          $service_url   = $buildLocalizedUrl($service['url'] ?? '#');
        ?>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="<?php echo htmlspecialchars($service_url, ENT_QUOTES, 'UTF-8'); ?>" class="card-link text-decoration-none d-block">
              <div class="card" style="background: url('<?php echo htmlspecialchars($service_img, ENT_QUOTES, 'UTF-8'); ?>') no-repeat center/cover;">
                <div class="card-info">
                  <h3><?php echo htmlspecialchars((string)$service_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/ArrowLink.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" 
                       alt="Arrow" 
                       style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars($emptyTxt['no_services'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= SERVICES SECTION END ================= -->

<!-- ================= CHOOSE US SECTION START ================= -->
<section class="choose py-5 editable-wrapper" style="position: relative;">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#chooseEditModal" title="<?php echo htmlspecialchars($lang['manage_choose'] ?? 'Edit Features', ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="container-fluid custom-container choose-container">
    <?php
    $c_data    = $getLocalizedData('choose', []);
    $sec_title = $c_data['section_title'] ?? ($c_data['title'] ?? '');
    $sec_desc  = $c_data['section_subtitle'] ?? ($c_data['desc'] ?? '');
    $c_items   = $c_data['items'] ?? [];
    ?>

    <?php if (!empty($sec_title)): ?>
      <h2 class="mb-5 sec-title"><?php echo htmlspecialchars((string)$sec_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>
    
    <?php if (!empty($sec_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars((string)$sec_desc, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <div class="row g-3">
      <?php if (!empty($c_items) && is_array($c_items)): ?>
        <?php foreach ($c_items as $index => $item): 
          if (!is_array($item)) continue;
          $item_img   = get_image_url($item['img'] ?? null, 'assets/img/home/Grouphome1.svg');
          $item_title = $item['title'] ?? '';
          $item_desc  = $item['desc'] ?? '';
          $item_url   = $buildLocalizedUrl($item['url'] ?? '#');
          
          if (empty($item_title)) continue;
          
          $is_active  = ($index === 1) ? 'active' : ''; 
        ?>
          <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card choose-card <?php echo $is_active; ?>">
              <div class="card-body">
                <a href="<?php echo htmlspecialchars($item_url, ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?>" />
                </a>
                <h5 class="card-title"><?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars((string)$item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars($emptyTxt['no_features'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= CHOOSE US SECTION END ================= -->

<!-- ================= REVIEWS SECTION START ================= -->
<section class="reviews py-5 editable-wrapper" style="position: relative;">
    <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#reviewsEditModal" title="<?php echo htmlspecialchars($lang['manage_customer_videos'] ?? 'Edit Reviews', ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>

    <div class="reviews-bg">
        <div class="custom-container">
            <?php 
            $r_data    = $getLocalizedData('reviews_items', []);
            $sec_title = $r_data['section_title'] ?? ($r_data['title'] ?? '');
            ?>
            <?php if (!empty($sec_title)): ?>
              <h2 class="py-5 text-center sec-title">
                  <?php echo htmlspecialchars((string)$sec_title, ENT_QUOTES, 'UTF-8'); ?>
              </h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="custom-container">
        <div class="reviews-carousel-wrapper">
            <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="carousel-inner">
                    <?php 
                    $reviews_videos = $r_data['items'] ?? [];
                    if (!is_array($reviews_videos)) {
                        $reviews_videos = [];
                    }

                    $valid_videos = [];
                    foreach ($reviews_videos as $v) {
                        if (is_array($v) && !empty($v['url'])) {
                            $safeEmbed = $sanitizeVideoEmbedUrl($v['url']);
                            if ($safeEmbed !== '') {
                                $valid_videos[] = ['url' => $safeEmbed];
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($valid_videos)): ?>
                        <?php foreach ($valid_videos as $index => $item): ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <div class="video-wrapper">
                                    <div class="video-container">
                                        <iframe src="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                loading="lazy" 
                                                referrerpolicy="strict-origin-when-cross-origin" 
                                                allowfullscreen 
                                                title="Customer Review Video"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <p><?php echo htmlspecialchars($emptyTxt['no_videos'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (count($valid_videos) > 1): ?>
                <div class="dots mt-4">
                    <?php foreach ($valid_videos as $index => $item): ?>
                        <span class="dot <?php echo ($index === 0) ? 'active' : ''; ?>" 
                              data-bs-target="#carousel-reviews" 
                              data-bs-slide-to="<?php echo $index; ?>">
                        </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</section>
<!-- ================= REVIEWS SECTION END ================= -->

<!-- ================= GUIDE HOME SECTION START ================= -->
<section class="guide py-2" style="position: relative;">
  <div class="custom-container">
    <?php 
        $lang_guide_data = $getLocalizedData('guide', []);

        $guide_title    = $lang_guide_data['title'] ?? ($data['guide_title'] ?? '');
        $guide_desc     = $lang_guide_data['desc'] ?? ($data['guide_desc'] ?? '');
        $read_more_text = $lang_guide_data['read_more_btn'] ?? ($lang['read_more'] ?? $emptyTxt['read_more']);
        $guide_items    = $lang_guide_data['items'] ?? ($data['guide_items'] ?? []);

        if (!is_array($guide_items)) {
            $guide_items = [];
        }
    ?>
    
    <?php if (!empty($guide_title)): ?>
      <h2 class="mb-2 pt-5 sec-title"><?php echo htmlspecialchars((string)$guide_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>
    <?php if (!empty($guide_desc)): ?>
        <p class="main-p"><?php echo nl2br(htmlspecialchars((string)$guide_desc, ENT_QUOTES, 'UTF-8')); ?></p>
    <?php endif; ?>

    <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
      <div class="carousel-inner" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (!empty($guide_items)): ?>
          <?php $chunks = array_chunk($guide_items, 3); ?>
          <?php foreach ($chunks as $index => $slide_items): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <div class="row g-4">
                <?php foreach ($slide_items as $item): 
                  if (!is_array($item)) continue;

                  $item_title = $item['title'] ?? '';
                  $item_desc  = $item['desc'] ?? '';
                  $item_img   = get_image_url($item['img'] ?? null);
                  $arrow_img  = get_image_url('assets/img/ArrowLeft.svg.webp');
                  $final_url  = $buildLocalizedUrl($item['url'] ?? ($item['link'] ?? '#'));
                ?>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100">
                      <div class="card-body d-flex flex-column">
                        <?php if (!empty($item_img)): ?>
                          <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid guide-img">
                        <?php endif; ?>
                        <h5 class="card-title fw-bold mt-3"><?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars((string)$item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                        <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn fw-bold mt-auto d-flex align-items-center gap-2">
                          <?php echo htmlspecialchars((string)$read_more_text, ENT_QUOTES, 'UTF-8'); ?>
                          <img src="<?php echo htmlspecialchars($arrow_img, ENT_QUOTES, 'UTF-8'); ?>" alt="arrow" class="<?php echo ($current_dir === 'ltr' ? 'ms-2' : 'me-2'); ?>" width="18" style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                        </a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="carousel-item active">
            <div class="col-12 text-center text-muted py-4">
              <p><?php echo htmlspecialchars($emptyTxt['no_guide'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($chunks) && count($chunks) > 1): ?>
        <div class="dots mt-4">
          <?php foreach ($chunks as $index => $slide): ?>
            <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-target="#carousel-guide" data-bs-slide-to="<?php echo $index; ?>"></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= GUIDE HOME SECTION END ================= -->

<!-- ================= FAQ SECTION START ================= -->
<section class="popular py-5 editable-wrapper" style="position: relative;">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#faqEditModal" title="<?php echo htmlspecialchars($lang['manage_faq'] ?? 'Edit FAQ', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-pencil-fill"></i></button>
  <?php endif; ?>

  <div class="container-fluid custom-container" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
    <?php 
        $faq_data  = $getLocalizedData('faq', []);
        $faq_title = $faq_data['title'] ?? ($data['faq_title'] ?? '');
        $faq_items = $faq_data['items'] ?? ($data['faq_items'] ?? []);

        if (!is_array($faq_items)) {
            $faq_items = [];
        }
    ?>

    <?php if (!empty($faq_title)): ?>
      <h2 class="sec-title mb-5"><?php echo htmlspecialchars((string)$faq_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>
    
    <div class="accordion mb-5" id="accordionExample">
      <?php if (!empty($faq_items)): ?>
        <?php foreach ($faq_items as $index => $item): 
            if (!is_array($item)) continue;
            $faq_question = $item['question'] ?? '';
            $faq_answer   = $item['answer'] ?? '';
        ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $index; ?>">
              <button class="accordion-button <?php echo ($index !== 0) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo ($index === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                <?php echo htmlspecialchars((string)$faq_question, ENT_QUOTES, 'UTF-8'); ?>
              </button>
            </h2>
            <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo ($index === 0) ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body"><?php echo nl2br(htmlspecialchars((string)$faq_answer, ENT_QUOTES, 'UTF-8')); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars($emptyTxt['no_faq'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= FAQ SECTION END ================= -->

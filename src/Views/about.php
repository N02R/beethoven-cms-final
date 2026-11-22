<?php
declare(strict_types=1);

/**
 * Beethoven CMS - About View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في home.php مع توحيد الصور المشتركة عبر اللغات.
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

// نصوص احتياطية مترجمة للغات الثلاث في حال خلو الحقول أو الأقسام
$i18nDefaults = [
    'de' => [
        'about_title'    => 'Über uns',
        'read_more'      => 'Mehr erfahren',
        'vision_title'   => 'Unsere Vision',
        'message_title'  => 'Unsere Mission',
        'services_title' => 'Unsere Dienstleistungen',
        'team_title'     => 'Unser Team',
        'partners_title' => 'Unsere Partner in und außerhalb Deutschlands',
        'no_services'    => 'Derzeit sind keine Dienstleistungen verfügbar.',
        'no_team'        => 'Derzeit sind keine Teammitglieder hinterlegt.',
        'no_counts'      => 'Derzeit sind keine Statistiken verfügbar.',
        'no_partners'    => 'Derzeit sind keine Partnerlogos hinterlegt.',
        'edit_about'     => 'Über-uns-Bereich bearbeiten',
        'edit_services'  => 'Dienstleistungen bearbeiten',
        'edit_team'      => 'Team bearbeiten',
        'edit_counts'    => 'Statistiken bearbeiten',
        'edit_partners'  => 'Partner bearbeiten'
    ],
    'en' => [
        'about_title'    => 'About Us',
        'read_more'      => 'Read More',
        'vision_title'   => 'Our Vision',
        'message_title'  => 'Our Mission',
        'services_title' => 'Our Featured Services',
        'team_title'     => 'Our Team',
        'partners_title' => 'Our Partners Inside and Outside Germany',
        'no_services'    => 'No services are currently available.',
        'no_team'        => 'No team members are currently available.',
        'no_counts'      => 'No statistics are currently available.',
        'no_partners'    => 'No partner logos are currently available.',
        'edit_about'     => 'Edit About Section',
        'edit_services'  => 'Edit About Services',
        'edit_team'      => 'Edit Team Section',
        'edit_counts'    => 'Edit Statistics',
        'edit_partners'  => 'Edit Partners'
    ],
    'ar' => [
        'about_title'    => 'من نحن',
        'read_more'      => 'قراءة المزيد',
        'vision_title'   => 'رؤية الشركة',
        'message_title'  => 'رسالة الشركة',
        'services_title' => 'خدماتنا المميزة',
        'team_title'     => 'فريق العمل',
        'partners_title' => 'شركاؤنا داخل وخارج ألمانيا',
        'no_services'    => 'لا توجد خدمات مضافة حالياً.',
        'no_team'        => 'لا يوجد أعضاء مضافون حالياً.',
        'no_counts'      => 'لا توجد إحصائيات مضافة حالياً.',
        'no_partners'    => 'لا توجد شعارات شركاء مضافة حالياً.',
        'edit_about'     => 'تعديل قسم من نحن',
        'edit_services'  => 'تعديل خدمات من نحن',
        'edit_team'      => 'تعديل فريق العمل',
        'edit_counts'    => 'تعديل الإحصائيات',
        'edit_partners'  => 'تعديل الشركاء'
    ]
];
$txt = $i18nDefaults[$current_lang];

// تهيئة متغيرات البيانات والإعدادات بأمان تام
$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

// 0. دالة مساعدة لفك الإعداد الخام كـ Array دون فقدان المفاتيح المشتركة مثل shared_items_img
$decodeRawSetting = function(string $settingKey, mixed $default = []) use ($data, $currentSettings): mixed {
    $raw = $data[$settingKey] ?? ($currentSettings[$settingKey] ?? (function_exists('get_setting') ? get_setting($settingKey, $default) : $default));
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : $default;
    }
    return is_array($raw) ? $raw : $default;
};

// 1. دالة موحدة لاستخراج بيانات أي قسم متعدد اللغات بأمان تام
$getLocalizedData = function(string $settingKey, mixed $default = []) use ($current_lang, $decodeRawSetting): mixed {
    $parsed = $decodeRawSetting($settingKey, $default);

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

// 2. دالة تنقية الروابط وربطها ببادئة اللغة الحالية (منع XSS و Open Redirect)
$buildLocalizedUrl = function(?string $rawUrl) use ($current_lang): string {
    $url = trim((string)($rawUrl ?? '#'));
    if ($url === '' || $url === '#') {
        return '#';
    }
    if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
        return '#';
    }
    if (preg_match('/^(https?:\/\/|mailto:|tel:|#)/i', $url)) {
        return $url;
    }
    $cleanPath = preg_replace('/^(ar|en|de)\//i', '', ltrim($url, '/'));
    return '/' . $current_lang . '/' . $cleanPath;
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= 1. ABOUT SECTION START ================= -->
<section class="about py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutEditModal" title="<?php echo htmlspecialchars($txt['edit_about'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php 
      $abRaw = $decodeRawSetting('about_section', []);
      $abRaw = is_array($abRaw) ? $abRaw : [];
      $ab    = $getLocalizedData('about_section', []);
      $ab    = is_array($ab) ? $ab : [];
      
      $ab_title       = $ab['title'] ?? $txt['about_title'];
      $ab_desc        = $ab['desc'] ?? '';
      $ab_btn_text    = $ab['btn_text'] ?? $txt['read_more'];
      $ab_btn_url     = $buildLocalizedUrl($ab['btn_url'] ?? '#');
      
      $vision_title   = $ab['vision_title'] ?? $txt['vision_title'];
      $vision_desc    = $ab['vision_desc'] ?? '';
      $message_title  = $ab['message_title'] ?? $txt['message_title'];
      $message_desc   = $ab['message_desc'] ?? '';

      $sharedMainImg    = $abRaw['shared_main_img'] ?? ($ab['main_img'] ?? ($abRaw['de']['main_img'] ?? ($abRaw['en']['main_img'] ?? ($abRaw['ar']['main_img'] ?? null))));
      $sharedSubImg     = $abRaw['shared_sub_img'] ?? ($ab['sub_img'] ?? ($abRaw['de']['sub_img'] ?? ($abRaw['en']['sub_img'] ?? ($abRaw['ar']['sub_img'] ?? null))));
      $sharedVisionIcon = $abRaw['shared_vision_icon'] ?? ($ab['vision_icon'] ?? ($abRaw['de']['vision_icon'] ?? ($abRaw['en']['vision_icon'] ?? ($abRaw['ar']['vision_icon'] ?? null))));
      $sharedMsgIcon    = $abRaw['shared_message_icon'] ?? ($ab['message_icon'] ?? ($abRaw['de']['message_icon'] ?? ($abRaw['en']['message_icon'] ?? ($abRaw['ar']['message_icon'] ?? null))));

      $about_main_img = get_image_url($sharedMainImg, '/assets/img/about/about1.jpg');
      $about_sub_img  = get_image_url($sharedSubImg, '/assets/img/about/about2.png');
      $vision_icon    = get_image_url($sharedVisionIcon, '/assets/img/about/CompanyVision.svg.webp');
      $message_icon   = get_image_url($sharedMsgIcon, '/assets/img/about/CompanyMessage.svg.webp');
      ?>

      <div class="row align-items-center g-5">
        
        <!-- العمود الأول: النصوص -->
        <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$ab_title, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p class="about-par mb-4"><?php echo nl2br(htmlspecialchars((string)$ab_desc, ENT_QUOTES, 'UTF-8')); ?></p>
          
          <div class="row g-3 mb-4">
            <!-- رؤية الشركة -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2 d-flex align-items-center gap-2">
                    <span class="icon-wrap flex-shrink-0">
                      <img src="<?php echo htmlspecialchars($vision_icon, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$vision_title, ENT_QUOTES, 'UTF-8'); ?>">
                    </span>
                    <span><?php echo htmlspecialchars((string)$vision_title, ENT_QUOTES, 'UTF-8'); ?></span>
                  </div>
                  <p class="card-text"><?php echo htmlspecialchars((string)$vision_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
              </div>
            </div>

            <!-- رسالة الشركة -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2 d-flex align-items-center gap-2">
                    <span class="icon-wrap flex-shrink-0">
                      <img src="<?php echo htmlspecialchars($message_icon, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$message_title, ENT_QUOTES, 'UTF-8'); ?>">
                    </span>
                    <span><?php echo htmlspecialchars((string)$message_title, ENT_QUOTES, 'UTF-8'); ?></span>
                  </div>
                  <p class="card-text"><?php echo htmlspecialchars((string)$message_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
              </div>
            </div>
          </div>

          <a href="<?php echo htmlspecialchars($ab_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-about">
            <?php echo htmlspecialchars((string)$ab_btn_text, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </div>

        <!-- العمود الثاني: الصور المتراكبة -->
        <div class="col-lg-6 position-relative">
          <div class="image-stack">
            <img src="<?php echo htmlspecialchars($about_main_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$ab_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid main-img">
            <div class="sub-img-wrapper">
              <img src="<?php echo htmlspecialchars($about_sub_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$ab_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid sub-img">
              <div class="dots-bg"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- ================= ABOUT SECTION END ================= -->

<!-- ================= 2. ABOUT SERVICES SECTION START ================= -->
<section class="services py-5 editable-wrapper">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutServicesEditModal" title="<?php echo htmlspecialchars($txt['edit_services'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $sRaw      = $decodeRawSetting('about_services', []);
      $sRaw      = is_array($sRaw) ? $sRaw : [];
      $sharedSrv = is_array($sRaw['shared_items_img'] ?? null) ? $sRaw['shared_items_img'] : [];
      $s_data    = $getLocalizedData('about_services', []);
      $s_data    = is_array($s_data) ? $s_data : [];
      
      $sec_title = $s_data['section_title'] ?? ($s_data['title'] ?? $txt['services_title']);
      $sec_desc  = $s_data['section_subtitle'] ?? ($s_data['desc'] ?? '');
      $services  = is_array($s_data['items'] ?? null) ? $s_data['items'] : [];
      ?>

      <h2 class="mb-3 sec-title"><?php echo htmlspecialchars((string)$sec_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      
      <?php if (!empty($sec_desc)): ?>
          <p class="mb-5 text-muted" style="max-width: 700px;">
              <?php echo htmlspecialchars((string)$sec_desc, ENT_QUOTES, 'UTF-8'); ?>
          </p>
      <?php endif; ?>

      <div class="row g-4">
        <?php if (!empty($services)): ?>
          <?php foreach ($services as $sIdx => $service): 
            if (!is_array($service)) continue;
            $rawSrvImg     = $sharedSrv[$sIdx] ?? ($service['img'] ?? null);
            $service_img   = get_image_url($rawSrvImg, '/assets/img/home/education.jpg');
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
            <p><?php echo htmlspecialchars($txt['no_services'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- ================= ABOUT SERVICES SECTION END ================= -->

<!-- ================= 3. TEAM SECTION START ================= -->
<section class="team py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#teamEditModal" title="<?php echo htmlspecialchars($txt['edit_team'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $teamRaw     = $decodeRawSetting('team', []);
      $teamRaw     = is_array($teamRaw) ? $teamRaw : [];
      $sharedTeam  = is_array($teamRaw['shared_items_img'] ?? null) ? $teamRaw['shared_items_img'] : [];
      $team_data   = $getLocalizedData('team', []);
      $team_data   = is_array($team_data) ? $team_data : [];
      
      $team_title = $team_data['title'] ?? $txt['team_title'];
      $team_desc  = $team_data['desc'] ?? '';
      $team_items = is_array($team_data['items'] ?? null) ? $team_data['items'] : [];
      ?>
      
      <div class="team-text mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars((string)$team_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($team_desc)): ?>
          <p class="description main-p"><?php echo htmlspecialchars((string)$team_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>
      
      <div class="swiper-container-wrapper">
        <div class="swiper mySwiper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
          <div class="swiper-wrapper">
            <?php if (!empty($team_items)): ?>
              <?php foreach ($team_items as $tIdx => $member): 
                if (!is_array($member)) continue;
                $m_name    = $member['name'] ?? '';
                $m_role    = $member['role'] ?? '';
                $rawMImg   = $sharedTeam[$tIdx] ?? ($member['img'] ?? null);
                $m_img     = get_image_url($rawMImg, '/assets/img/team/member1.jpg');
              ?>
                <div class="swiper-slide">
                  <div class="team-card">
                    <img src="<?php echo htmlspecialchars($m_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$m_name, ENT_QUOTES, 'UTF-8'); ?>" />
                    <div class="info">
                      <h5><?php echo htmlspecialchars((string)$m_name, ENT_QUOTES, 'UTF-8'); ?></h5>
                      <p><?php echo htmlspecialchars((string)$m_role, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="swiper-slide w-100 text-center py-4">
                <p class="text-muted"><?php echo htmlspecialchars($txt['no_team'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="swiper-nav-wrapper">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ================= TEAM SECTION END ================= -->

<!-- ================= 4. COUNTS SECTION START ================= -->
<section class="count editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#countsEditModal" title="<?php echo htmlspecialchars($txt['edit_counts'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $countsRaw      = $decodeRawSetting('about_counts', []);
      $countsRaw      = is_array($countsRaw) ? $countsRaw : [];
      $sharedCounts   = is_array($countsRaw['shared_items_img'] ?? null) ? $countsRaw['shared_items_img'] : [];
      $sharedNumbers  = is_array($countsRaw['shared_items_number'] ?? null) ? $countsRaw['shared_items_number'] : [];
      $counts_data    = $getLocalizedData('about_counts', []);
      $counts_data    = is_array($counts_data) ? $counts_data : [];
      $counts_items   = is_array($counts_data['items'] ?? null) ? $counts_data['items'] : [];
      ?>
      <div class="row g-4">
        <?php if (!empty($counts_items)): ?>
          <?php foreach ($counts_items as $cIdx => $c): 
              if (!is_array($c)) continue;
              $c_num    = (string)($sharedNumbers[$cIdx] ?? ($c['number'] ?? ''));
              $c_title  = (string)($c['title'] ?? '');
              $rawCImg  = $sharedCounts[$cIdx] ?? ($c['img'] ?? '');
              $c_img    = get_image_url($rawCImg, '');
          ?>
            <div class="col-lg-3 col-md-6">
              <div class="count-card">
                <?php if (!empty($c_img)): ?>
                  <div class="count-img">
                    <img src="<?php echo htmlspecialchars($c_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($c_title, ENT_QUOTES, 'UTF-8'); ?>">
                  </div>
                <?php endif; ?>
                <div class="count-info">
                  <span><?php echo htmlspecialchars($c_num, ENT_QUOTES, 'UTF-8'); ?></span>
                  <p><?php echo htmlspecialchars($c_title, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted py-4">
            <p><?php echo htmlspecialchars($txt['no_counts'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- ================= COUNTS SECTION END ================= -->

<!-- ================= 5. PARTNERS SECTION START ================= -->
<section class="partenar py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#partnersEditModal" title="<?php echo htmlspecialchars($txt['edit_partners'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $partnersRaw    = $decodeRawSetting('partners', []);
      $partnersRaw    = is_array($partnersRaw) ? $partnersRaw : [];
      $sharedPartners = is_array($partnersRaw['shared_items_img'] ?? null) ? $partnersRaw['shared_items_img'] : [];
      $partners_data  = $getLocalizedData('partners', []);
      $partners_title = is_array($partners_data) ? ($partners_data['title'] ?? $txt['partners_title']) : $txt['partners_title'];
      $partners_items = is_array($partners_data) ? ($partners_data['items'] ?? []) : [];
      
      if (empty($partners_items)) {
          $old_items = function_exists('get_setting') ? get_setting('partners_items', []) : [];
          $partners_items = is_string($old_items) ? (json_decode($old_items, true) ?? []) : $old_items;
      }
      if (!is_array($partners_items)) {
          $partners_items = [];
      }
      ?>
      <h2 class="sec-title mb-5"><?php echo htmlspecialchars((string)$partners_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      
      <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
        <?php if (!empty($partners_items)): ?>
          <?php foreach ($partners_items as $pIdx => $p): 
            if (!is_array($p)) continue;
            $rawPImg = $sharedPartners[$pIdx] ?? ($p['img'] ?? null);
            $p_img   = get_image_url($rawPImg, '');
            if (empty($p_img)) continue;
          ?>
            <div class="col">
              <div class="partner-item">
                <img src="<?php echo htmlspecialchars($p_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Partner" class="img-fluid" />
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted py-3">
            <p><?php echo htmlspecialchars($txt['no_partners'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- ================= PARTNERS SECTION END ================= -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة من نحن للمشرف فقط
$about_modals_file = __DIR__ . '/admin/admin_about_modals.php';
if ($is_admin_active && is_file($about_modals_file)) { 
    include_once $about_modals_file; 
} 
?>

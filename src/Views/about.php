<?php
/**
 * صفحة من نحن - About Page View
 * يتلقى المتغيرات $data و $is_admin من AboutController
 */

// استخراج البيانات وتجهيزها للاستخدام السهل في العرض
$about         = $data['about'] ?? [];
$teamTitle     = $data['team_title'] ?? 'فريق العمل';
$teamDesc      = $data['team_desc'] ?? '';
$teamMembers   = $data['team_members'] ?? [];
$counts        = $data['about_counts'] ?? [];
$partnersTitle = $data['partners_title'] ?? 'شركاؤنا';
$partnersItems = $data['partners_items'] ?? [];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($about['title'] ?? 'من نحن') ?> - بيتهوفن سيتي</title>
    <!-- روابط الـ CSS المشتركة أو الهيدر -->
</head>
<body>

    <!-- استدعاء الهيدر العام للموقع -->
    <?php 
    // include __DIR__ . '/partials/header.php'; 
    ?>

    <!-- قسم من نحن الرئيسي -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <?php if (!empty($about['main_img'])): ?>
                        <img src="<?= htmlspecialchars($about['main_img']) ?>" alt="About Main" class="img-fluid rounded shadow">
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <h1 class="mb-3"><?= htmlspecialchars($about['title'] ?? '') ?></h1>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($about['desc'] ?? '')) ?></p>
                    
                    <?php if (!empty($about['btn_text']) && !empty($about['btn_url'])): ?>
                        <a href="<?= htmlspecialchars($about['btn_url']) ?>" class="btn btn-primary">
                            <?= htmlspecialchars($about['btn_text']) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- الرؤية والرسالة -->
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100 shadow-sm">
                        <?php if (!empty($about['vision_icon'])): ?>
                            <img src="<?= htmlspecialchars($about['vision_icon']) ?>" alt="Vision" width="50" class="mb-3">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($about['vision_title'] ?? 'رؤيتنا') ?></h3>
                        <p><?= htmlspecialchars($about['vision_desc'] ?? '') ?></p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100 shadow-sm">
                        <?php if (!empty($about['message_icon'])): ?>
                            <img src="<?= htmlspecialchars($about['message_icon']) ?>" alt="Message" width="50" class="mb-3">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($about['message_title'] ?? 'رسالتنا') ?></h3>
                        <p><?= htmlspecialchars($about['message_desc'] ?? '') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم الإحصائيات (Counts) -->
    <?php if (!empty($counts)): ?>
    <section class="counts-section bg-light py-4">
        <div class="container">
            <div class="row text-center">
                <?php foreach ($counts as $count): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <?php if (!empty($count['img'])): ?>
                            <img src="<?= htmlspecialchars($count['img']) ?>" alt="Icon" width="40" class="mb-2">
                        <?php endif; ?>
                        <h2 class="fw-bold"><?= htmlspecialchars($count['number'] ?? '0') ?></h2>
                        <p class="text-muted"><?= htmlspecialchars($count['title'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- قسم فريق العمل -->
    <?php if (!empty($teamMembers)): ?>
    <section class="team-section py-5">
        <div class="container text-center">
            <h2 class="mb-2"><?= htmlspecialchars($teamTitle) ?></h2>
            <p class="text-muted mb-5"><?= htmlspecialchars($teamDesc) ?></p>
            <div class="row">
                <?php foreach ($teamMembers as $member): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <?php if (!empty($member['img'])): ?>
                                <img src="<?= htmlspecialchars($member['img']) ?>" class="card-img-top" alt="Team Member">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($member['name'] ?? '') ?></h5>
                                <p class="text-muted small"><?= htmlspecialchars($member['job'] ?? '') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- قسم الشركاء -->
    <?php if (!empty($partnersItems)): ?>
    <section class="partners-section py-5 bg-white">
        <div class="container text-center">
            <h3 class="mb-4"><?= htmlspecialchars($partnersTitle) ?></h3>
            <div class="row align-items-center justify-content-center">
                <?php foreach ($partnersItems as $partner): ?>
                    <div class="col-md-2 col-4 mb-3">
                        <?php if (!empty($partner['img'])): ?>
                            <img src="<?= htmlspecialchars($partner['img']) ?>" alt="Partner" class="img-fluid grayscale">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- زر التحكم السريع للمدير (إن وجد) -->
    <?php if (isset($is_admin) && $is_admin): ?>
        <div style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
            <a href="index.php?url=admin/settings" class="btn btn-dark shadow rounded-pill px-4 py-2">
                ✏️ تعديل هذه الصفحة
            </a>
        </div>
    <?php endif; ?>

    <!-- استدعاء الفوتر العام للموقع -->
    <?php 
    // include __DIR__ . '/partials/footer.php'; 
    ?>

</body>
</html>

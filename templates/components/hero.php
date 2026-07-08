<section class="hero py-5">
  <div class="custom-container">
    
    <h1 class="editable" 
        data-section="hero" 
        data-key="title" 
        style="padding: 10px; cursor: pointer;">
        <?php echo $content['hero_title'] ?? 'العنوان الرئيسي هنا'; ?>
    </h1>

    <p class="editable" 
       data-section="hero" 
       data-key="subtitle"
       style="padding: 10px; cursor: pointer;">
       <?php echo $content['hero_subtitle'] ?? 'الوصف هنا'; ?>
    </p>
      
    <?php if (isset($_SESSION['is_admin'])): ?>
      <div class="mt-3 p-3 bg-light rounded">
         <span class="text-muted small">وضع التحرير مفعل</span>
         <a href="/logout" class="btn btn-sm btn-danger">خروج من الإدارة</a>
      </div>
    <?php endif; ?>

  </div>
</section>

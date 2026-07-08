<section class="hero py-5">
  <div class="custom-container">
    <form action="/admin/save-all" method="POST">
      
      <h1><?php echo \App\Core\CMS::editable('home', 'hero', 'title'); ?></h1>
      <p><?php echo \App\Core\CMS::editable('home', 'hero', 'subtitle', 'textarea'); ?></p>
      
      <div class="mt-3">
        <?php if (isset($_SESSION['is_admin'])): ?>
          <button type="submit" class="btn btn-success">حفظ التغييرات</button>
          <a href="/admin/login?logout=1" class="btn btn-danger">خروج</a>
        <?php endif; ?>
      </div>

    </form>
  </div>
</section>

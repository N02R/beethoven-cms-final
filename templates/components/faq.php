<section class="popular py-5">
  <div class="container-fluid custom-container">
    <h2 class="sec-title mb-5"><?php echo \App\Core\CMS::get('home', 'faq', 'main_title'); ?></h2>
    
    <div class="accordion mb-5" id="accordionExample">
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?php echo $i; ?>">
            <button class="accordion-button <?php echo ($i > 1) ? 'collapsed' : ''; ?>" type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i; ?>"
                    aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $i; ?>">
              <?php echo \App\Core\CMS::get('home', 'faq', 'q' . $i); ?>
            </button>
          </h2>
          <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse <?php echo ($i == 1) ? 'show' : ''; ?>" 
               aria-labelledby="heading<?php echo $i; ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <?php echo \App\Core\CMS::get('home', 'faq', 'a' . $i); ?>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>

    <div class="call bg-white shadow-sm py-5 px-4">
      <h5 class="mb-4"><?php echo \App\Core\CMS::get('home', 'faq', 'form_title'); ?></h5>
      <form class="form mx-auto" action="send_inquiry.php" method="POST">
        <input type="text" name="question" placeholder="<?php echo \App\Core\CMS::get('home', 'faq', 'form_placeholder'); ?>" required>
        <button type="submit"><img src="assets/img/home/send-2.svg" alt="إرسال"></button>
      </form>
    </div>
  </div>
</section>

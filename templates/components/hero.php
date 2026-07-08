<section class="hero">
    <h1>
        <?php 
        // استدعاء النص من قاعدة البيانات
        echo \App\Core\CMS::get('home', 'hero', 'title'); 
        ?>
    </h1>
</section>

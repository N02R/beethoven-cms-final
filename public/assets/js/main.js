document.addEventListener("DOMContentLoaded", () => {

  /* ================= 1. Hover Section (Why BCS) ================= */
  const cards = document.querySelectorAll("section.choose .choose-card");
  const activeCard = document.querySelector("section.choose .choose-card.active");
  if (cards.length) {
    cards.forEach((card) => {
      card.addEventListener("mouseenter", () => activeCard?.classList.remove("active"));
      card.addEventListener("mouseleave", () => activeCard?.classList.add("active"));
    });
  }

  /* ================= 2. Active Link Header ================= */
  const currentPath = window.location.pathname;
  document.querySelectorAll("#main-header .nav-link").forEach(link => {
    try {
      const linkPath = new URL(link.href).pathname;
      link.classList.toggle("active", currentPath === linkPath);
    } catch (e) {
      // تجاهل الروابط الوهمية التي لا تحتوى على رابط صحيح
    }
  });

  /* ================= 3. Carousel Dot Control & Touch Swipe ================= */
  initCarouselDots();
  initReviewsSwipe();

  /* ================= 4. Animated Counters ================= */
  const counters = document.querySelectorAll(".count-info span");
  counters.forEach(counter => {
    const targetText = counter.textContent.trim();
    let target = parseInt(targetText.replace(/\D/g, ""));
    if (isNaN(target)) return;
    
    let increment = Math.ceil(target / 200);
    let current = 0;
    
    const updateCounter = () => {
      if (current < target) {
        current += increment;
        counter.textContent = current + (targetText.replace(/\d/g, ""));
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = targetText;
      }
    };
    updateCounter();
  });

  /* ================= 5. WordPress Settings & Menu Fetch ================= */
  initWordPressData();

  /* ================= 6. Cookie Banner Consent (GDPR) ================= */
  initCookieBanner();

});

/* ================= Helper Functions ================= */

// تفعيل السحب باللمس في كاروسيل التقييمات والدليل بدون حركة تلقائية
function initReviewsSwipe() {
  const carouselIds = ['carousel-reviews', 'carousel-guide'];
  if (typeof bootstrap === 'undefined' || !bootstrap.Carousel) return;

  carouselIds.forEach((id) => {
    const carouselEl = document.getElementById(id);
    if (!carouselEl || carouselEl.dataset.swipeAttached === 'true') return;
    carouselEl.dataset.swipeAttached = 'true';

    const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
      interval: false,
      ride: false,
      touch: true
    });
    carouselInstance.pause();

    let startX = 0;
    let startY = 0;
    let isTracking = false;
    const swipeThreshold = 50;

    carouselEl.addEventListener('touchstart', function (e) {
      if (!e.touches || e.touches.length === 0) return;
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
      isTracking = true;
    }, { passive: true });

    carouselEl.addEventListener('touchend', function (e) {
      if (!isTracking || !e.changedTouches || e.changedTouches.length === 0) return;
      isTracking = false;

      const endX = e.changedTouches[0].clientX;
      const endY = e.changedTouches[0].clientY;
      const diffX = endX - startX;
      const diffY = endY - startY;

      if (Math.abs(diffX) < swipeThreshold || Math.abs(diffX) <= Math.abs(diffY)) {
        return;
      }

      const dirSource = carouselEl.getAttribute('dir')
        || carouselEl.querySelector('.carousel-inner')?.getAttribute('dir')
        || document.dir
        || 'ltr';
      const isRtl = dirSource.toLowerCase() === 'rtl';

      if (diffX < 0) {
        if (isRtl) {
          carouselInstance.prev();
        } else {
          carouselInstance.next();
        }
      } else {
        if (isRtl) {
          carouselInstance.next();
        } else {
          carouselInstance.prev();
        }
      }
    }, { passive: true });
  });
}

// دالة الكاروسيل
function initCarouselDots() {
  const allDots = document.querySelectorAll('.dot');
  allDots.forEach(dot => {
    const targetId = dot.dataset.bsTarget;
    const slideIndex = parseInt(dot.dataset.bsSlideTo);
    const targetCarousel = document.querySelector(targetId);

    if (!targetCarousel) return;

    let carouselInstance = bootstrap.Carousel.getInstance(targetCarousel);
    if (!carouselInstance) {
      carouselInstance = new bootstrap.Carousel(targetCarousel, { interval: false, ride: false });
    }
    carouselInstance.pause();

    dot.addEventListener('click', () => carouselInstance.to(slideIndex));

    if (!targetCarousel.dataset.listenerAttached) {
      targetCarousel.addEventListener('slid.bs.carousel', function (e) {
        const relatedDots = document.querySelectorAll(`.dot[data-bs-target="${targetId}"]`);
        relatedDots.forEach(d => d.classList.remove('active'));
        relatedDots[e.to]?.classList.add('active');
      });
      targetCarousel.dataset.listenerAttached = 'true';
    }
  });
}

// دالة الهيدر عند التمرير (Sticky Header)
const header = document.querySelector('header');
const scrollThreshold = 50;
if (header) {
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > scrollThreshold);
  });
}

// جلب إعدادات وقائمة WordPress
async function initWordPressData() {
  try {
    if (typeof WordPressAPI !== 'undefined' && WordPressAPI.getSettings) {
      const settings = await WordPressAPI.getSettings();
      if (settings) {
        const title = document.getElementById("site-title");
        if (title) title.textContent = settings.name;
        
        const logos = [
          document.getElementById("site-logo"),
          document.getElementById("site-logo-mobile"),
          document.getElementById("site-logo-mobile-offcanvas")
        ];
        logos.forEach(img => {
          if (img && settings.logo) img.src = settings.logo;
        });
      }
    }
  } catch (err) {
    console.log("Settings Error:", err);
  }

  try {
    const res = await fetch("http://172.16.2.102:8000/wp-json/bcs/v1/menu");
    const menu = await res.json();
    
    const desktop = document.getElementById("main-menu-desktop");
    const mobile = document.getElementById("main-menu-mobile");
    
    const html = menu.map(item => `
      <li class="nav-item">
        <a class="nav-link" href="${item.url}">${item.title}</a>
      </li>
    `).join("");
    
    if (desktop) desktop.innerHTML = html;
    if (mobile) mobile.innerHTML = html;
  } catch (err) {
    console.log("Menu Error:", err);
  }
}

// دالة شريط الكوكيز المتوافقة تجارياً مع معايير الشركات الألمانية
function initCookieBanner() {
  const cookieBanner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('accept-cookies');
  const rejectBtn = document.getElementById('reject-cookies');

  // استخدام sessionStorage لضمان ظهور الشريط في كل جلسة جديدة
  const consent = sessionStorage.getItem('cookie_consent');

  if (!consent) {
    if (cookieBanner) {
      cookieBanner.style.display = 'block';
    }
    disableTrackingScripts();
  } else if (consent === 'accepted') {
    enableTrackingScripts();
  } else {
    disableTrackingScripts();
  }

  if (acceptBtn) {
    acceptBtn.addEventListener('click', function() {
      sessionStorage.setItem('cookie_consent', 'accepted');
      if (cookieBanner) cookieBanner.style.display = 'none';
      enableTrackingScripts();
    });
  }

  if (rejectBtn) {
    rejectBtn.addEventListener('click', function() {
      sessionStorage.setItem('cookie_consent', 'rejected');
      if (cookieBanner) cookieBanner.style.display = 'none';
      disableTrackingScripts();
    });
  }
}

// دالة تفعيل أدوات التتبع (مثل Google Analytics أو الإعلانات)
function enableTrackingScripts() {
  console.log("تم تفعيل الكوكيز الاختيارية وأدوات التتبع بناءً على موافقة المستخدم الصريحة.");
}

// دالة تعطيل/حظر أدوات التتبع
function disableTrackingScripts() {
  console.log("تم حظر أدوات التتبع والكوكيز الاختيارية التزاماً بقرار المستخدم وقوانين الخصوصية الألمانية.");
}

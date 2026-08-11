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
  const currentFile = currentPath.split("/").pop() || "index.html";

  document.querySelectorAll("#main-header .nav-link").forEach(link => {
    try {
      const linkPath = new URL(link.href).pathname;
      link.classList.toggle("active", currentPath === linkPath);
    } catch (e) {
      // تجاهل الروابط الوهمية التي لا تحتوى على رابط صحيح
    }
  });

  /* ================= 3. Language Dropdown Logic ================= */
  const langItems = document.querySelectorAll('.dropdown-item');
  langItems.forEach(item => {
    const text = item.textContent.trim();
    if (currentFile.includes('-en')) {
      if (text === 'العربية') {
        item.setAttribute('href', currentFile.replace('-en', ''));
      }
    } else {
      if (text === 'English') {
        item.setAttribute('href', currentFile.replace('.html', '-en.html'));
      }
    }
    if (item.getAttribute('href') === currentFile) {
      item.classList.add('active');
    }
  });

  /* ================= 4. Carousel Dot Control ================= */
  initCarouselDots();

  /* ================= 5. Animated Counters ================= */
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

  /* ================= 6. WordPress Settings & Menu Fetch ================= */
  initWordPressData();

  /* ================= 7. Cookie Banner Consent (GDPR) ================= */
  initCookieBanner();

});

/* ================= Helper Functions ================= */

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
  
  // مثال: إذا كان لديك كود لـ Google Analytics يتم وضعه هنا:
  /*
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'YOUR-GA-ID');
  */
}

// دالة تعطيل/حظر أدوات التتبع
function disableTrackingScripts() {
  console.log("تم حظر أدوات التتبع والكوكيز الاختيارية التزاماً بقرار المستخدم وقوانين الخصوصية الألمانية.");
}

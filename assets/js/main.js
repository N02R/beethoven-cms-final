document.addEventListener("DOMContentLoaded", () => {

  /* ===== Hover Section (Why BCS) ===== */
  const cards = document.querySelectorAll("section.choose .choose-card");
  const activeCard = document.querySelector("section.choose .choose-card.active");
  if (cards.length) {
    cards.forEach((card) => {
      card.addEventListener("mouseenter", () => activeCard?.classList.remove("active"));
      card.addEventListener("mouseleave", () => activeCard?.classList.add("active"));
    });
  }

  /* ===== Active Link Header ===== */
  const navLinks = document.querySelectorAll("#main-header .nav-link");
  const currentFile = window.location.pathname.split("/").pop() || "index.html";

  navLinks.forEach(link => {
    const href = link.getAttribute("href");
    if (href === currentFile || (href === "index.html" && (currentFile === "" || currentFile === "index.html"))) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });


// ===== Active link highlight =====
const currentPath = window.location.pathname;
document.querySelectorAll("#main-header .nav-link").forEach(link => {
  const linkPath = new URL(link.href).pathname;
  link.classList.toggle("active", currentPath === linkPath);
});


  /* ===== Language Dropdown Logic ===== */
  const langItems = document.querySelectorAll('.dropdown-item');
  langItems.forEach(item => {
    const text = item.textContent.trim();
    if (currentFile.includes('-en')) {
      if (text === 'العربية') {
        const arabicPage = currentFile.replace('-en', '');
        item.setAttribute('href', arabicPage);
      }
    } else {
      if (text === 'English') {
        const englishPage = currentFile.replace('.html', '-en.html');
        item.setAttribute('href', englishPage);
      }
    }
    if (item.getAttribute('href') === currentFile) {
      item.classList.add('active');
    }
  });

  /* ===== Carousel Dot Control ===== */
  initCarouselDots();

});

// ===================== Carousel Dot Control =====================
function initCarouselDots() {
  const allDots = document.querySelectorAll('.dot');

  allDots.forEach(dot => {
    const targetId = dot.dataset.bsTarget;
    const slideIndex = parseInt(dot.dataset.bsSlideTo);
    const targetCarousel = document.querySelector(targetId);

    if (!targetCarousel) return;

    // تأكد إن الكاروسيل initialized أولاً
    let carouselInstance = bootstrap.Carousel.getInstance(targetCarousel);
    if (!carouselInstance) {
      carouselInstance = new bootstrap.Carousel(targetCarousel, {
        interval: false,
        ride: false
      });
    }

    // Dot click
    dot.addEventListener('click', () => {
      carouselInstance.to(slideIndex);
    });

    // Update active dot on slide
    if (!targetCarousel.dataset.listenerAttached) {
      targetCarousel.addEventListener('slid.bs.carousel', function (e) {
        const relatedDots = document.querySelectorAll(`.dot[data-bs-target="${targetId}"]`);
        relatedDots.forEach(d => d.classList.remove('active'));
        relatedDots[e.to].classList.add('active');
      });
      targetCarousel.dataset.listenerAttached = 'true';
    }
  });
}

// الحصول على الهيدر
const header = document.querySelector('header');

// ارتفاع الـ scroll الذي يبدأ عنده التأثير
const scrollThreshold = 50;

window.addEventListener('scroll', () => {
  if (window.scrollY > scrollThreshold) {
    // إضافة class عند scroll للأسفل
    header.classList.add('scrolled');
  } else {
    // إزالة class عند الرجوع للأعلى
    header.classList.remove('scrolled');
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".count-info span");
  
  counters.forEach(counter => {
    const targetText = counter.textContent.trim(); // مثال: "700K+"
    let target = parseInt(targetText.replace(/\D/g, "")); // يزيل الحروف
    let increment = Math.ceil(target / 200); // سرعة العد
    
    let current = 0;
    const updateCounter = () => {
      if (current < target) {
        current += increment;
        counter.textContent = current + (targetText.replace(/\d/g, "")); // يحافظ على + أو K
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = targetText; // يصل للقيمة النهائية
      }
    };
    updateCounter();
  });
});


document.addEventListener("DOMContentLoaded", async () => {
  
  /* ================= SETTINGS ================= */
  const settings = await WordPressAPI.getSettings();
  
  if (settings) {
    
    // Title
    const title = document.getElementById("site-title");
    if (title) title.textContent = settings.name;
    
    // Logo (كل الأماكن)
    const logos = [
      document.getElementById("site-logo"),
      document.getElementById("site-logo-mobile"),
      document.getElementById("site-logo-mobile-offcanvas")
    ];
    
    logos.forEach(img => {
      if (img && settings.logo) {
        img.src = settings.logo;
      }
    });
  }
  
  /* ================= MENU ================= */
  try {
    const res = await fetch("http://172.16.2.102:8000/wp-json/bcs/v1/menu");
    const menu = await res.json();
    
    const desktop = document.getElementById("main-menu-desktop");
    const mobile = document.getElementById("main-menu-mobile");
    
    const html = menu.map(item => `
      <li class="nav-item">
        <a class="nav-link" href="${item.url}">
          ${item.title}
        </a>
      </li>
    `).join("");
    
    if (desktop) desktop.innerHTML = html;
    if (mobile) mobile.innerHTML = html;
    
  } catch (err) {
    console.log("Menu Error:", err);
  }
  
});



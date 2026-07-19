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

  /* ===== Language Dropdown Logic ===== */
  const langItems = document.querySelectorAll('.dropdown-item');
  const currentFile = window.location.pathname.split("/").pop() || "index.html";
  
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
    // ملاحظة: الكلاس active هنا يتم إضافته فقط إذا تطابق الرابط
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

    let carouselInstance = bootstrap.Carousel.getInstance(targetCarousel);
    if (!carouselInstance) {
      carouselInstance = new bootstrap.Carousel(targetCarousel, {
        interval: false,
        ride: false
      });
    }

    dot.addEventListener('click', () => {
      carouselInstance.to(slideIndex);
    });

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
const scrollThreshold = 50;

window.addEventListener('scroll', () => {
  if (window.scrollY > scrollThreshold) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".count-info span");
  
  counters.forEach(counter => {
    const targetText = counter.textContent.trim(); 
    let target = parseInt(targetText.replace(/\D/g, "")); 
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
});

document.addEventListener("DOMContentLoaded", async () => {
  /* ================= SETTINGS & MENU ================= */
  // الكود الخاص بالـ fetch تم تركه كما هو كما طلبتِ
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

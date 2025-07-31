// Navigation functionality for sidebar
const navButtons = document.querySelectorAll(".nav-item");
const contentSections = document.querySelectorAll(".content-section");

navButtons.forEach((button) => {
  button.addEventListener("click", () => {
    // Remove active class from all nav buttons
    navButtons.forEach((btn) => {
      btn.classList.remove("bg-primary", "text-white");
      btn.classList.add("text-gray-700", "hover:bg-primary/10", "hover:text-primary");
    });

    // Add active class to clicked button
    button.classList.add("bg-primary", "text-white");
    button.classList.remove("text-gray-700", "hover:bg-primary/10", "hover:text-primary");

    // Hide all content sections
    contentSections.forEach((content) => {
      content.classList.add("hidden");
    });

    // Show corresponding content
    const targetId = button.id.replace("nav-", "content-");
    const targetContent = document.getElementById(targetId);
    if (targetContent) {
      // Sembunyikan semua section
      contentSections.forEach((content) => {
        content.classList.add("hidden");
      });
      // Tampilkan section yang dipilih
      targetContent.classList.remove("hidden");
    }
  });
});

// Smooth transitions for content switching
function switchContent(targetContent) {
  targetContent.style.opacity = "0";
  targetContent.style.transform = "translateY(20px)";
  setTimeout(() => {
    targetContent.style.opacity = "1";
    targetContent.style.transform = "translateY(0)";
  }, 100);
}

// Mark All Read functionality for notifications
document.addEventListener("DOMContentLoaded", function () {
  const markAllReadBtn = document.querySelector("#content-notifications .bg-blue-100");
  if (markAllReadBtn) {
    markAllReadBtn.addEventListener("click", function () {
      // Find all unread notifications (with .relative and .bg-red-500 dot)
      const notificationList = document.querySelectorAll("#content-notifications .space-y-4 > div");
      notificationList.forEach(function (notif) {
        // If notification has the red dot, treat as unread
        const redDot = notif.querySelector(".absolute.bg-red-500");
        if (redDot) {
          // Remove red dot
          redDot.remove();
          // Add opacity-75 and border-gray-200, bg-gray-50/50
          notif.classList.remove("border-blue-200", "bg-blue-50/50", "border-green-200", "bg-green-50/50", "relative");
          notif.classList.add("border-gray-200", "bg-gray-50/50", "opacity-75");
          // Change button to 'Read' badge if exists
          const btn = notif.querySelector("button");
          if (btn) {
            btn.outerHTML = '<span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">Read</span>';
          }
        }
      });
      // Set unread badge to 0
      const unreadBadge = document.querySelector("#content-notifications .bg-red-100");
      if (unreadBadge) {
        unreadBadge.textContent = "0 unread";
      }
    });
  }
});
document.addEventListener("DOMContentLoaded", () => {
  // Navbar functionality
  const navbar = document.querySelector("nav");
  const navbarToggle = document.getElementById("navbar-toggle");

  if (navbarToggle) {
    navbarToggle.addEventListener("click", () => {
      navbar.classList.toggle("hidden");
    });
  }

  // Search functionality
  const searchTrigger = document.querySelector(".search-trigger");
  const searchContainer = document.querySelector(".search-container");
  let isSearchOpen = false;

  if (searchTrigger && searchContainer) {
    searchTrigger.addEventListener("click", () => {
      isSearchOpen = !isSearchOpen;
      if (isSearchOpen) {
        searchContainer.style.width = "240px";
        searchContainer.querySelector("input").focus();
      } else {
        searchContainer.style.width = "0";
      }
    });
  }

  // Mobile menu functionality
  const mobileMenuButton = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
  }

  // Handle scroll events for navbar
  const header = document.querySelector("header");
  let lastScroll = 0;

  // Carousel functionality
  const carousels = document.querySelectorAll(".carousel-container");

  carousels.forEach((carousel) => {
    const slides = carousel.querySelectorAll(".carousel-slides img");
    const dots = carousel.querySelectorAll(".carousel-dot");
    let currentSlide = 0;
    let autoplayInterval;

    // Function to show specific slide
    const showSlide = (index) => {
      if (!slides[index] || !dots[index]) return; // Cegah error jika slide/dot tidak ada
      slides.forEach((slide) => slide.classList.add("opacity-0"));
      dots.forEach((dot) => {
        dot.classList.remove("opacity-100");
        dot.classList.add("opacity-50");
      });

      slides[index].classList.remove("opacity-0");
      dots[index].classList.remove("opacity-50");
      dots[index].classList.add("opacity-100");
    };

    // Initialize dots click handlers
    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        currentSlide = index;
        showSlide(currentSlide);
        resetAutoplay();
      });
    });

    // Autoplay functionality
    const nextSlide = () => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    };

    const resetAutoplay = () => {
      clearInterval(autoplayInterval);
      autoplayInterval = setInterval(nextSlide, 3000); // Change slide every 3 seconds
    };

    // Initialize carousel
    showSlide(0);
    resetAutoplay();

    // Pause autoplay on hover
    carousel.addEventListener("mouseenter", () => clearInterval(autoplayInterval));
    carousel.addEventListener("mouseleave", resetAutoplay);
  });

  // Handle testimonial slider functionality
  let currentTestimonialSlide = 0;
  const testimonialSlider = document.getElementById("testimonialSlider");
  const testimonialSlides = testimonialSlider ? testimonialSlider.querySelectorAll("div.bg-white") : [];
  const totalTestimonialSlides = testimonialSlides.length;

  const nextButton = document.getElementById("next");
  const prevButton = document.getElementById("prev");

  function showTestimonialSlide(index) {
    if (!testimonialSlider || totalTestimonialSlides === 0) return;
    if (index >= totalTestimonialSlides) {
      currentTestimonialSlide = 0;
    } else if (index < 0) {
      currentTestimonialSlide = totalTestimonialSlides - 1;
    } else {
      currentTestimonialSlide = index;
    }
    // Ambil lebar card dan gap antar card
    const cardWidth = testimonialSlides[0].offsetWidth;
    const gap = 16; // gap-4 = 1rem = 16px
    const offset = -(currentTestimonialSlide * (cardWidth + gap));
    testimonialSlider.style.transform = `translateX(${offset}px)`;
  }

  // Next button click event for testimonial slider
  if (nextButton) {
    nextButton.addEventListener("click", () => {
      showTestimonialSlide(currentTestimonialSlide + 1);
    });
  }
  // Previous button click event for testimonial slider
  if (prevButton) {
    prevButton.addEventListener("click", () => {
      showTestimonialSlide(currentTestimonialSlide - 1);
    });
  }

  // Inisialisasi posisi awal
  showTestimonialSlide(0);

  // Dropdown filter functionality
  const dropdownButtons = document.querySelectorAll("[data-collapse-toggle]");
  dropdownButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Get target dropdown
      const targetId = button.getAttribute("data-collapse-toggle");
      const dropdown = document.getElementById(targetId);

      // Get arrow icon
      const arrow = button.querySelector("svg");

      // Toggle dropdown
      if (dropdown.classList.contains("hidden")) {
        // Show current dropdown
        dropdown.classList.remove("hidden");
        dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        if (arrow) arrow.style.transform = "rotate(180deg)";
      } else {
        // Hide current dropdown
        dropdown.classList.add("hidden");
        dropdown.style.maxHeight = "0";
        if (arrow) arrow.style.transform = "rotate(0)";
      }
    });
  });

  // Clear filters functionality
  const clearFiltersBtn = document.querySelector(".border-primary");
  if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener("click", (e) => {
      e.preventDefault();

      // Get all checkboxes in the sidebar
      const checkboxes = document.querySelectorAll('aside input[type="checkbox"]');

      // Uncheck all checkboxes
      checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
      });

      // Optional: Close all open dropdowns
      const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
      const arrows = document.querySelectorAll("[data-collapse-toggle] svg");

      dropdowns.forEach((dropdown) => {
        dropdown.classList.add("hidden");
        dropdown.style.maxHeight = "0";
      });

      arrows.forEach((arrow) => {
        arrow.style.transform = "rotate(0)";
      });
    });
  }

  // Cart functionality
  const cartButton = document.getElementById("cartButton");
  const cartSidebar = document.getElementById("cartSidebar");
  const cartSidebarOverlay = document.getElementById("cartSidebarOverlay");
  const closeCartSidebar = document.getElementById("closeCartSidebar");

  function openCart() {
    cartSidebarOverlay.classList.remove("hidden");
    cartSidebar.classList.remove("translate-x-full");
    document.body.style.overflow = "hidden"; // Prevent body scroll
  }

  function closeCart() {
    cartSidebar.classList.add("translate-x-full");
    setTimeout(() => {
      cartSidebarOverlay.classList.add("hidden");
    }, 300);
    document.body.style.overflow = ""; // Restore body scroll
  }

  // Event listeners
  cartButton.addEventListener("click", openCart);
  closeCartSidebar.addEventListener("click", closeCart);
  cartSidebarOverlay.addEventListener("click", closeCart);

  // Close cart with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeCart();
    }
  });

  // Quantity controls
  document.addEventListener("click", function (e) {
    if (e.target.closest("button")) {
      const button = e.target.closest("button");
      const quantitySpan = button.parentElement.querySelector("span");

      if (button.innerHTML.includes('line x1="5"')) {
        // Minus button
        let quantity = parseInt(quantitySpan.textContent);
        if (quantity > 1) {
          quantitySpan.textContent = quantity - 1;
        }
      } else if (button.innerHTML.includes('line x1="12"')) {
        // Plus button
        let quantity = parseInt(quantitySpan.textContent);
        quantitySpan.textContent = quantity + 1;
      }
    }
  });
});

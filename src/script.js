document.addEventListener("DOMContentLoaded", () => {
    // Search functionality
    const searchTrigger = document.querySelector(".search-trigger");
    const searchContainer = document.querySelector(".search-container");
    let isSearchOpen = false;

    searchTrigger.addEventListener("click", () => {
      isSearchOpen = !isSearchOpen;
      if (isSearchOpen) {
        searchContainer.style.width = "240px";
        searchContainer.querySelector("input").focus();
      } else {
        searchContainer.style.width = "0";
      }
    });

    // Mobile menu functionality
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    mobileMenuButton.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });

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
    const testimonialSlides = document.querySelectorAll("#testimonialSlider > div");
    const totalTestimonialSlides = testimonialSlides.length;

    const nextButton = document.getElementById("next");
    const prevButton = document.getElementById("prev");

    function showTestimonialSlide(index) {
      if (index >= totalTestimonialSlides) {
        currentTestimonialSlide = 0; // Loop back to the first slide
      } else if (index < 0) {
        currentTestimonialSlide = totalTestimonialSlides - 1; // Go to the last slide
      } else {
        currentTestimonialSlide = index;
      }
      const offset = -currentTestimonialSlide * (testimonialSlides[0].offsetWidth + 16); // 16px is the margin between cards
      document.getElementById("testimonialSlider").style.transform = `translateX(${offset}px)`;
    }

    // Next button click event for testimonial slider
    nextButton.addEventListener("click", () => {
      showTestimonialSlide(currentTestimonialSlide + 1);
    });

    // Previous button click event for testimonial slider
    prevButton.addEventListener("click", () => {
      showTestimonialSlide(currentTestimonialSlide - 1);
    });
  });
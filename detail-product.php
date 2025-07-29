<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GreenNest | plant store</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <!-- memasukan navbar -->
    <?php include './component/navbar.php'; ?>

    <main class="pt-16">
      <div id="content">
        <!-- detail product -->
        <section>
          <div class="mx-auto container px-8 md:px-20">
            <div class="py-10">
              <div class="flex flex-wrap -mx-4 justify-between">
                <!-- Product Images Section -->
                <div class="w-full md:w-1/2 mb-8">
                  <div class="flex gap-4">
                    <!-- Thumbnails -->
                    <div class="flex flex-col gap-y-4">
                      <img src="./src/img/main-img-product.png" alt="Thumbnail 1" class="size-16 sm:size-16 object-cover rounded-full cursor-pointer opacity-60 hover:opacity-100 transition duration-300" onclick="changeImage(this.src)" />
                      <img
                        src="./src/img/main-img-product (1).png"
                        alt="Thumbnail 2"
                        class="size-16 sm:size-16 object-cover rounded-full cursor-pointer opacity-60 hover:opacity-100 transition duration-300"
                        onclick="changeImage(this.src)" />
                      <img
                        src="./src/img/main-img-product (2).png"
                        alt="Thumbnail 3"
                        class="size-16 sm:size-16 object-cover rounded-full cursor-pointer opacity-60 hover:opacity-100 transition duration-300"
                        onclick="changeImage(this.src)" />
                    </div>

                    <!-- Main Image -->
                    <div class="flex-1">
                      <img src="./src/img/main-img-product.png" alt="Product" class="w-full h-auto rounded-lg shadow-md" id="mainImage" />
                    </div>
                  </div>

                  <!-- about shiping and guaranted -->
                  <div class="flex gap-4 mt-8 justify-end md:pl-20 items-center">
                    <div class="text-center flex flex-col items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="42" height="32" viewBox="0 0 42 32" fill="none">
                        <path d="M34 27C35.6569 27 37 25.6569 37 24C37 22.3431 35.6569 21 34 21C32.3431 21 31 22.3431 31 24C31 25.6569 32.3431 27 34 27Z" stroke="#45671E" stroke-width="2" />
                        <path d="M12 27C13.6569 27 15 25.6569 15 24C15 22.3431 13.6569 21 12 21C10.3431 21 9 22.3431 9 24C9 25.6569 10.3431 27 12 27Z" stroke="#45671E" stroke-width="2" />
                        <path
                          d="M31.375 24H27M27 24V9H33.783C33.8515 9.00009 33.9192 9.01425 33.982 9.0416C34.0448 9.06895 34.1013 9.10891 34.148 9.159L40.865 16.356C40.9516 16.4485 40.9998 16.5703 41 16.697V23.5C41 23.6326 40.9473 23.7598 40.8536 23.8536C40.7598 23.9473 40.6326 24 40.5 24H37.5M27 24H15M27 24V5.5C27 5.36739 26.9473 5.24021 26.8536 5.14645C26.7598 5.05268 26.6326 5 26.5 5H5.5C5.36739 5 5.24021 5.05268 5.14645 5.14645C5.05268 5.24021 5 5.36739 5 5.5V23.5C5 23.6326 5.05268 23.7598 5.14645 23.8536C5.24021 23.9473 5.36739 24 5.5 24H9.077"
                          stroke="#45671E"
                          stroke-width="2" />
                        <path d="M11 8.5H3C2.72386 8.5 2.5 8.72386 2.5 9C2.5 9.27614 2.72386 9.5 3 9.5H11C11.2761 9.5 11.5 9.27614 11.5 9C11.5 8.72386 11.2761 8.5 11 8.5Z" fill="#45671E" stroke="#45671E" />
                        <path d="M9 12.5H1C0.723858 12.5 0.5 12.7239 0.5 13C0.5 13.2761 0.723858 13.5 1 13.5H9C9.27614 13.5 9.5 13.2761 9.5 13C9.5 12.7239 9.27614 12.5 9 12.5Z" fill="#45671E" stroke="#45671E" />
                        <path d="M11 16.5H3C2.72386 16.5 2.5 16.7239 2.5 17C2.5 17.2761 2.72386 17.5 3 17.5H11C11.2761 17.5 11.5 17.2761 11.5 17C11.5 16.7239 11.2761 16.5 11 16.5Z" fill="#45671E" stroke="#45671E" />
                      </svg>
                      <p class="text-sm">Free Shipping</p>
                      <p class="text-xs">Get free standard shipping when you spend $150 or more. <a href="#" class="underline">Learn More</a></p>
                    </div>
                    <hr class="w-0.5 h-28 bg-secondary bg-opacity-30" />
                    <div class="text-center flex flex-col items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" fill="none">
                        <g clip-path="url(#clip0_68_2252)">
                          <path
                            d="M31.1451 16.758C31.2561 16.6688 31.3456 16.5558 31.4072 16.4274C31.4687 16.299 31.5006 16.1584 31.5006 16.016C31.5006 15.8736 31.4687 15.733 31.4072 15.6046C31.3456 15.4762 31.2561 15.3632 31.1451 15.274L28.8091 13.286C28.4941 13.033 28.3991 12.591 28.5251 12.213L29.5671 9.34C29.6117 9.20528 29.6272 9.06258 29.6123 8.92143C29.5975 8.78028 29.5527 8.64392 29.4809 8.52145C29.4092 8.39898 29.3122 8.29323 29.1963 8.21124C29.0804 8.12925 28.9484 8.07292 28.8091 8.046L25.7791 7.478C25.5803 7.44845 25.3964 7.35581 25.2543 7.21375C25.1123 7.07169 25.0196 6.88772 24.9901 6.689L24.4541 3.691C24.3591 3.091 23.7281 2.744 23.1601 2.933L20.2881 3.975C19.9081 4.101 19.4671 4.006 19.2141 3.691L17.2261 1.355C17.1369 1.24419 17.0239 1.15479 16.8956 1.09337C16.7673 1.03194 16.6268 1.00006 16.4846 1.00006C16.3423 1.00006 16.2019 1.03194 16.0735 1.09337C15.9452 1.15479 15.8323 1.24419 15.7431 1.355L13.7861 3.691C13.5331 4.006 13.0911 4.101 12.7131 3.975L9.84007 2.933C9.24007 2.743 8.64107 3.091 8.54607 3.691L7.97807 6.721C7.94852 6.91972 7.85588 7.10369 7.71382 7.24575C7.57176 7.38781 7.3878 7.48045 7.18907 7.51L4.19107 8.046C3.59107 8.141 3.24407 8.772 3.43307 9.34L4.47507 12.213C4.60107 12.591 4.50607 13.033 4.19107 13.286L1.85507 15.274C1.74409 15.3632 1.65453 15.4762 1.59299 15.6046C1.53146 15.733 1.49951 15.8736 1.49951 16.016C1.49951 16.1584 1.53146 16.299 1.59299 16.4274C1.65453 16.5558 1.74409 16.6688 1.85507 16.758L4.19107 18.746C4.50607 18.998 4.60107 19.44 4.47507 19.819L3.43307 22.691C3.38667 22.8237 3.37003 22.965 3.38436 23.1048C3.39868 23.2447 3.44361 23.3797 3.51595 23.5002C3.58829 23.6208 3.68628 23.7239 3.80296 23.8023C3.91963 23.8807 4.05214 23.9325 4.19107 23.954L7.22107 24.49C7.63107 24.553 7.94707 24.869 8.01007 25.28L8.54607 28.31C8.64107 28.909 9.27207 29.256 9.84007 29.067L12.7131 28.025C13.0911 27.899 13.5331 27.994 13.7861 28.309L15.7741 30.645C15.8633 30.756 15.9763 30.8455 16.1047 30.9071C16.2331 30.9686 16.3737 31.0006 16.5161 31.0006C16.6585 31.0006 16.7991 30.9686 16.9275 30.9071C17.0559 30.8455 17.1689 30.756 17.2581 30.645L19.2461 28.309C19.4981 27.994 19.9401 27.899 20.3191 28.025L23.1911 29.067C23.3258 29.1117 23.4685 29.1271 23.6096 29.1122C23.7508 29.0974 23.8872 29.0526 24.0096 28.9809C24.1321 28.9091 24.2378 28.8121 24.3198 28.6962C24.4018 28.5804 24.4582 28.4484 24.4851 28.309L25.0221 25.279C25.0516 25.0803 25.1443 24.8963 25.2863 24.7543C25.4284 24.6122 25.6124 24.5195 25.8111 24.49L28.8411 23.954C29.4411 23.859 29.7881 23.228 29.5981 22.66L28.5251 19.788C28.3991 19.408 28.4941 18.967 28.8091 18.714L31.1451 16.758Z"
                            stroke="#45671E"
                            stroke-width="2" />
                          <path d="M10.5 15.425L14.743 19.667L22.521 11.889" stroke="#45671E" stroke-width="2" />
                        </g>
                        <defs>
                          <clipPath id="clip0_68_2252">
                            <rect width="32" height="32" fill="white" transform="translate(0.5)" />
                          </clipPath>
                        </defs>
                      </svg>
                      <p class="text-sm">Guarantee</p>
                      <p class="text-xs">If your plant dies within 30 days, we'll replace it for free.<a href="#" class="underline"> Learn More</a></p>
                    </div>
                  </div>
                </div>

                <!-- Product Details -->
                <div class="w-full md:w-1/2 md:pl-10">
                  <!-- Product Title and Price -->
                  <div class="flex justify-between items-start mb-6">
                    <h2 class="text-lg md:text-3xl text-primary">Snake Plant Laurentii</h2>
                    <div class="text-end">
                      <span class="text-end text-sm md:text-2xl font-medium mr-0.5 md:mr-2 text-primary">$349.99</span>
                      <span class="text-gray-500 line-through md:text-base text-xs">$399.99</span>
                    </div>
                  </div>

                  <!-- Product Description -->
                  <p class="text-gray-700 mb-6 md:text-base text-sm">With its striking dark green leaves, bold white veins, and sculptural shape, this plant brings instant drama to any space.</p>

                  <!-- Stock Info -->
                  <p class="text-gray-600 mb-6 md:text-base text-sm">Stock: 999</p>

                  <!-- Color Selection -->
                  <div class="mb-8">
                    <h3 class="text-base font-medium mb-3 text-primary md:text-base text-sm">CHOOSE POT COLOR:</h3>
                    <div class="flex space-x-3">
                      <button class="w-10 h-10 bg-[#626A70] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#626A70]"></button>
                      <button class="w-10 h-10 bg-[#C78356] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C78356]"></button>
                      <button class="w-10 h-10 bg-[#B7C7CD] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B7C7CD]"></button>
                      <button class="w-10 h-10 bg-[#3B5D7D] rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B5D7D]"></button>
                    </div>
                  </div>

                  <!-- Quantity and Add to Cart -->
                  <div class="flex space-x-4 mb-8 items-center">
                    <div class="flex items-center border border-gray-300 rounded-full">
                      <button class="px-4 py-2.5 text-primary hover:bg-gray-100 transition-colors rounded-l-full" onclick="decrementQuantity()">-</button>
                      <div class="w-8 flex justify-center items-center px-2">
                        <input
                          type="number"
                          id="quantity"
                          name="quantity"
                          min="1"
                          value="1"
                          class="w-full text-center focus:outline-none text-primary bg-transparent [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                          readonly />
                      </div>
                      <button class="px-4 py-2.5 text-primary hover:bg-gray-100 transition-colors rounded-r-full" onclick="incrementQuantity()">+</button>
                    </div>
                    <a
                      href="./checkout-form.php"
                      class="bg-primary flex-1 flex gap-2 items-center justify-center text-white px-6 py-2.5 rounded-full hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 transition-colors">
                      Add to Cart
                    </a>

                    <!-- Like Button with Heart Icon and Count -->
                    <button id="like-btn" class="flex items-center space-x-1 rounded-full bg-pink-200 text-white px-3 py-2.5 text-sm font-medium transition duration-200">
                      <!-- Heart Icon -->
                      <svg id="like-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                      <!-- Like Count -->
                      <span id="like-count" class="font-semibold text-base text-white">10</span>
                    </button>
                  </div>

                  <script>
                    document.addEventListener("DOMContentLoaded", function () {
                      const likeBtn = document.getElementById("like-btn");
                      const likeCount = document.getElementById("like-count");
                      const likeIcon = document.getElementById("like-icon");

                      let liked = false;
                      const initialCount = parseInt(likeCount.textContent, 10) || 0;

                      likeBtn.addEventListener("click", function () {
                        liked = !liked;

                        if (liked) {
                          // Aktif: merah solid
                          likeIcon.setAttribute("fill", "#ef4444"); // red-500
                          likeIcon.setAttribute("stroke", "#ef4444");
                          likeCount.classList.remove("text-white");
                          likeCount.classList.add("text-red-500");
                        } else {
                          // Nonaktif: putih outline
                          likeIcon.setAttribute("fill", "none");
                          likeIcon.setAttribute("stroke", "white");
                          likeCount.classList.remove("text-red-500");
                          likeCount.classList.add("text-white");
                        }

                        // Update count
                        likeCount.textContent = liked ? initialCount + 1 : initialCount;
                      });
                    });
                  </script>

                  <!-- Product Accordion Container -->
                  <div class="border-t border-gray-200">
                    <!-- Detail & Care Section -->
                    <div class="border-b border-gray-200">
                      <button type="button" class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 hover:bg-gray-100 group" aria-controls="dropdown-1" data-collapse-toggle="dropdown-1">
                        <span class="flex-1 text-left whitespace-nowrap text-black font-medium md:text-base text-sm">DETAIL & CARE</span>
                        <svg sidebar-toggle-item class="w-6 h-6 transition-transform duration-300" fill="none" stroke="#8E8E8E" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                      </button>
                      <div id="dropdown-1" class="hidden overflow-hidden transition-all duration-300" style="max-height: 0">
                        <ul class="py-2 space-y-2">
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Snake Plant Laurentii (Dracaena trifasciata 'Laurentii')</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Low maintenance and highly adaptable</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Air-purifying capabilities</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Tolerates low light conditions</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Drought resistant</p>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <!-- What's Included Section -->
                    <div class="border-b border-gray-200">
                      <button type="button" class="flex items-center w-full p-4 text-base font-normal text-gray-900 transition duration-75 hover:bg-gray-100 group" aria-controls="dropdown-2" data-collapse-toggle="dropdown-2">
                        <span class="flex-1 text-left whitespace-nowrap text-black font-medium md:text-base text-sm">WHAT'S INCLUDED</span>
                        <svg sidebar-toggle-item class="w-6 h-6 transition-transform duration-300" fill="none" stroke="#8E8E8E" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                      </button>
                      <div id="dropdown-2" class="hidden overflow-hidden transition-all duration-300" style="max-height: 0">
                        <ul class="py-2 space-y-2">
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• 1x Snake Plant Laurentii in your chosen size</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Ceramic pot in your selected color</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• Detailed care instructions</p>
                          </li>
                          <li class="pl-11 py-2">
                            <p class="text-gray-700">• 30-day plant health guarantee</p>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- dropdown script -->
            <!-- Move this script just before closing body tag -->
            <script>
              // Get all dropdown buttons
              const dropdownButtons = document.querySelectorAll("[data-collapse-toggle]");

              dropdownButtons.forEach((button) => {
                button.addEventListener("click", () => {
                  // Get target dropdown
                  const targetId = button.getAttribute("data-collapse-toggle");
                  const dropdown = document.getElementById(targetId);

                  // Get arrow icon
                  const arrow = button.querySelector("svg");

                  // Close all other dropdowns
                  dropdownButtons.forEach((otherButton) => {
                    if (otherButton !== button) {
                      const otherId = otherButton.getAttribute("data-collapse-toggle");
                      const otherDropdown = document.getElementById(otherId);
                      const otherArrow = otherButton.querySelector("svg");

                      otherDropdown.classList.add("hidden");
                      otherDropdown.style.maxHeight = "0";
                      otherArrow.style.transform = "rotate(0deg)";
                    }
                  });

                  // Toggle current dropdown
                  if (dropdown.classList.contains("hidden")) {
                    dropdown.classList.remove("hidden");
                    dropdown.style.maxHeight = `${dropdown.scrollHeight}px`;
                    arrow.style.transform = "rotate(180deg)";
                  } else {
                    dropdown.style.maxHeight = "0";
                    setTimeout(() => {
                      dropdown.classList.add("hidden");
                    }, 300); // Match transition duration
                    arrow.style.transform = "rotate(0deg)";
                  }
                });
              });
            </script>

            <!-- scrupt quantity -->
            <script>
              function changeImage(src) {
                document.getElementById("mainImage").src = src;
              }

              function incrementQuantity() {
                const input = document.getElementById("quantity");
                input.value = parseInt(input.value) + 1;
              }

              function decrementQuantity() {
                const input = document.getElementById("quantity");
                if (parseInt(input.value) > 1) {
                  input.value = parseInt(input.value) - 1;
                }
              }
            </script>
          </div>
        </section>

        <!-- description -->
        <section class="bg-[#F0F8E7]">
          <div class="mx-auto container px-2 md:px-16">
            <div class="md:flex md:flex-row flex-col-reverse md:space-x-10 space-y-6 py-4">
              <!-- Text Section -->
              <div class="md:w-1/2 space-y-6 pt-10">
                <h1 class="text-xl md:text-2xl">Description</h1>
                <div class="space-y-4">
                  <p>
                    Snake Plant Laurentii is a tough yet elegant houseplant, recognized for its tall, upright foliage with deep green centers and golden-yellow outlines. It grows well in both bright spots and dim corners, making it a
                    versatile choice for any room, whether at home or at work.
                  </p>
                  <p>Originating from tropical West Africa, this plant is well-loved for its ability to purify the air and its impressive durability. It's an excellent choice for plant beginners or anyone with a busy lifestyle.</p>
                </div>
                <div class="space-y-1.5">
                  <p>Additional Resources:</p>
                  <ul class="list-disc pl-4">
                    <li>View Snake Plant Care Guide</li>
                  </ul>
                </div>
                <div class="space-y-1.5">
                  <h1 class="text-xl md:text-2xl">Botanical Name</h1>
                  <p class="italic font-extralight">Sansevieria trifasciata 'Laurentii'</p>
                </div>
                <div class="space-y-1.5">
                  <h1 class="text-xl md:text-2xl">Common Name(s)</h1>
                  <p>Snake Plant, Mother-in-law's Tongue, Saint George's Sword, Viper’s Bowstring Hemp</p>
                </div>
              </div>
              <!-- Image Section -->
              <div class="md:w-1/2 flex justify-center md:justify-start">
                <img src="./src/img/main-img-product.png" alt="" class="w-full md:w-auto" />
              </div>
            </div>
          </div>
        </section>

        <!-- section comment and ratings -->
        <section class="mx-auto container px-2 md:px-16 py-16">
          <div class="md:flex md:flex-col gap-8">
            <!-- Rating Header -->
            <div class="md:flex items-start justify-between flex-row md:flex-wrap">
              <!-- Column 1: Overall Rating -->
              <div class="md:flex md:space-y-0 space-y-5 gap-6 w-full sm:w-auto">
                <div>
                  <div class="flex items-center gap-2">
                    <h1 class="text-3xl font-medium">5.0</h1>
                    <!-- Rating stars -->
                    <div class="flex items-center space-x-1">
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                  </div>
                  <p class="text-black text-sm">Based on 22 Reviews</p>
                </div>

                <!-- Column 2: Rating Bars -->
                <div class="flex gap-2">
                  <!-- Stars Rating Column -->
                  <div>
                    <div class="flex items-center space-x-1">
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                    <div class="flex items-center space-x-1">
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                    <div class="flex items-center space-x-1">
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                    <div class="flex items-center space-x-1">
                      <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                  </div>

                  <!-- Bar Chart Column -->
                  <div class="flex-1 space-y-2 w-[200px]">
                    <!-- Tambahkan fixed width -->
                    <!-- 5 stars -->
                    <div class="flex items-center gap-2">
                      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-secondary h-full" style="width: 73%"></div>
                      </div>
                      <span class="text-sm text-gray-600 min-w-[30px]">(16)</span>
                    </div>

                    <!-- 4 stars -->
                    <div class="flex items-center gap-2">
                      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-secondary h-full" style="width: 9%"></div>
                      </div>
                      <span class="text-sm text-gray-600 min-w-[30px]">(2)</span>
                    </div>

                    <!-- 3 stars -->
                    <div class="flex items-center gap-2">
                      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-secondary h-full" style="width: 9%"></div>
                      </div>
                      <span class="text-sm text-gray-600 min-w-[30px]">(2)</span>
                    </div>

                    <!-- 2 stars -->
                    <div class="flex items-center gap-2">
                      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-secondary h-full" style="width: 4.5%"></div>
                      </div>
                      <span class="text-sm text-gray-600 min-w-[30px]">(1)</span>
                    </div>

                    <!-- 1 star -->
                    <div class="flex items-center gap-2">
                      <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-secondary h-full" style="width: 4.5%"></div>
                      </div>
                      <span class="text-sm text-gray-600 min-w-[30px]">(1)</span>
                    </div>
                  </div>
                </div>

                <!-- Column 3: Review Images -->
                <div class="flex items-start space-x-3 md:space-x-2 w-full sm:w-auto mt-4 sm:mt-0">
                  <img class="md:w-20 md:h-14 w-28 object-cover" src="./src/img/image-coment1.png" alt="" />
                  <img class="md:w-20 md:h-14 w-28 object-cover" src="./src/img/image-coment1 (1).png" alt="" />
                  <img class="md:w-20 md:h-14 w-28 object-cover" src="./src/img/image-coment1 (2).png" alt="" />
                </div>
              </div>

              <!-- Write Review Button -->
              <div class="mt-6 md:mt-0 flex justify-center">
                <button class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 transition-colors">Write a Review</button>
              </div>
            </div>

            <div class="flex items-center text-center border-b-2 justify-start space-x-2 text-secondary py-2 border-secondary w-[90px]">
              <p>Reviews</p>
              <p>22</p>
            </div>

            <!-- Review Comments Section -->
            <div class="mt-8">
              <!-- comments component -->

              <?php include('./component/card-comment.php') ?>
              <?php include('./component/card-comment.php') ?>
              <?php include('./component/card-comment.php') ?>
              <?php include('./component/card-comment.php') ?>
              <?php include('./component/card-comment.php') ?>

              <div class="flex justify-center items-center">
                <button class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 transition-colors">Load More Reviews</button>
              </div>
            </div>
          </div>
        </section>

        <!-- section people also browsed -->
        <section class="py-12">
          <div class="mx-auto px-2 md:px-16 container">
            <div class="flex justify-between items-center mb-6 text-black">
              <h2 class="md:text-2xl">People aslo browsed</h2>
              <a href="./list-product.php" class="hover:underline md:text-base text-sm">SHOP ALL</a>
            </div>

            <div class="relative">
              <div class="cards-container">
                <?php include('./component/card-slider.php'); ?>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
    <!-- footer -->
    <?php include('./component/footer.php'); ?>
  </body>
</html>

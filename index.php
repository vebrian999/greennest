<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
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

    <main class="">
      <div id="content">
        <!-- hero section -->
        <section class="mx-auto">
          <div class="relative h-[625px] md:h-[560px] text-white overflow-hidden">
            <div class="absolute inset-0">
              <img src="./src/img/hero-image.png" alt="Background Image" class="object-cover object-center w-full h-full" />
              <div class="absolute inset-0 blur-md bg-[#8E8E8E33] opacity-50"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4">
              <h1 class="text-3xl md:text-5xl leading-tight mb-4">Start Your Plant Journey Today</h1>
              <p class="text-base md:text-xl px-4 md:px-72 text-[#F7F6F8] mb-8">Discover easy-care plants, expert tips, and stylish pots — everything you need to grow your own green sanctuary.</p>
              <a href="#" class="bg-secondary text-white py-2.5 px-6 rounded-full text-base md:text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">Explore Starter Plants</a>
            </div>
          </div>
        </section>

        <!-- Best Sellers Section -->
        <section class="py-12">
          <div class="mx-auto md:px-16 px-2 container">
            <div class="flex justify-between items-center mb-6 text-black">
              <h2 class="md:text-2xl text-xl">Best Sellers</h2>
              <a href="./list-product.php" class="hover:underline md:text-base text-sm">SHOP ALL</a>
            </div>

            <div class="relative">
              <div class="cards-container">

                <?php include './component/card-slider.php'; ?>

              </div>
            </div>
        </section>

        <!-- New Arrivals Section -->
        <section class="py-12 bg-[#F0F8E7] bg-opacity-60">
          <div class="mx-auto md:px-16 px-2 container">
            <div class="text-black flex justify-between items-center mb-6">
              <h2 class="md:text-2xl text-xl">New Arrivals</h2>
              <a href="./list-product.php" class="hover:underline md:text-base text-sm">SHOP ALL</a>
            </div>

            <div class="relative">
              <div class="cards-container">

                <?php include './component/card-slider.php'; ?>
  
              </div>
            </div>
        </section>

        <!-- Most Gifted Section -->
        <section class="py-12">
          <div class="mx-auto md:px-16 px-2 container">
            <div class="flex justify-between items-center mb-6 text-black">
              <h2 class="md:text-2xl text-xl">Most Gifted</h2>
              <a href="./list-product.php" class="hover:underline md:text-base text-sm">SHOP ALL</a>
            </div>

            <div class="relative">
              <div class="cards-container">
                <?php include './component/card-slider.php'; ?>
              </div>
            </div>
        </section>

        <section class="py-12">
          <div class="mx-auto px-2 md:px-16 container">
            <div class="mb-6">
              <h2 class="md:text-2xl text-xl text-black">More Ways to Find Your Perfect Plant</h2>
            </div>

            <div class="md:flex justify-center items-center md:space-x-10 md:space-y-0 space-y-10 text-center">
              <div>
                <a href="#" class="">
                  <img src="./src/img/category-img (1).png" alt="" />
                  <a class="underline" href="#"> Pet-Friendly </a>
                </a>
              </div>
              <div>
                <a href="#">
                  <img src="./src/img/category-img (2).png" alt="" />
                  <a class="underline" href="#"> Low-Maintenance </a>
                </a>
              </div>
              <div>
                <a href="#">
                  <img src="./src/img/category-img (3).png" alt="" />
                  <a class="underline" href="#"> Cacti & Succulents </a>
                </a>
              </div>
              <div>
                <a href="#">
                  <img src="./src/img/category-img (4).png" alt="" />
                  <a class="underline" href="#"> Gifts </a>
                </a>
              </div>
            </div>
          </div>
        </section>

        <section class="py-12 mx-auto container px-2 md:px-16">
          <div class="relative overflow-hidden bg-white py-20">
            <!-- Decorative background image and gradient -->
            <div aria-hidden="true" class="absolute inset-0">
              <div class="absolute inset-0 overflow-hidden">
                <img src="./src/img/banner-promo.png" alt="" class="h-full w-full rounded-2xl object-cover object-center" />
              </div>
              <div class="absolute inset-0 bg-white bg-opacity-15"></div>
              <div class="absolute inset-0 bg-gradient-to-t opacity-95 from-white via-white"></div>
            </div>

            <!-- Callout -->
            <div aria-labelledby="sale-heading" class="relative mx-auto flex max-w-7xl flex-col items-center px-4 pt-32 text-center sm:px-6 lg:px-8">
              <div class="mx-auto max-w-2xl lg:max-w-none">
                <h2 id="sale-heading" class="text-3xl font-bold tracking-tight text-primary sm:text-4xl lg:text-5xl">Get 50% Off on Our Best Plant Collection!</h2>
                <p class="mx-auto mt-4 max-w-xl text-xl text-primary">Most of our plants are limited stock and go fast. Grab your favorite greens before they’re gone!</p>
                <a href="#" class="mt-6 inline-block w-full rounded-3xl border border-transparent bg-primary px-8 py-3 font-medium text-white sm:w-auto">Check Now to Claim the Promo</a>
              </div>
            </div>
          </div>
        </section>

        <article class="py-12">
          <div class="md:flex mx-auto px-2 md:px-16 container">
            <!-- Wrapper dengan flex-col-reverse untuk mobile -->
            <div class="flex flex-col-reverse md:flex-row md:space-x-7">
              <!-- Text content -->
              <div class="space-y-10 md:w-2/3 mt-8 md:mt-0">
                <div class="space-y-1">
                  <p>Care Tips & Guides</p>
                  <h1 class="text-2xl">Plant Care 101: Your Green Guide to Happy Plants</h1>
                </div>
                <div class="space-y-12">
                  <p>
                    Whether you're a new plant parent or looking to level up your indoor jungle game, mastering the basics of plant care is the first step to happy, thriving greenery. From watering wisdom to sunlight strategies, we've
                    rounded up essential tips to help your plants live their best lives.
                  </p>
                  <p>Learn how to read your plant's signals, avoid common mistakes, and build a care routine that works for both you and your leafy companions.</p>
                </div>
                <div class="">
                  <a href="#" class="bg-primary text-white py-3 px-6 rounded-full text-base transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg block w-full md:inline-block md:w-auto text-center"
                    >Read Full Article</a
                  >
                </div>
              </div>
              <!-- Image section -->
              <div class="md:w-full">
                <img src="./src/img/article-img.png" alt="" />
              </div>
            </div>
          </div>
        </article>

        <!-- Testimonial Section -->
        <section class="py-12 bg-[#F0F8E7] bg-opacity-60">
          <div class="mx-auto container px-2 md:px-16">
            <div class="text-center mb-12">
              <h2 class="text-2xl text-primary font-medium mb-4">What Our Customers Say</h2>
              <p class="text-gray-600 max-w-2xl mx-auto">Real experiences from our happy plant parents</p>
            </div>

            <!-- Slider Wrapper -->
            <div class="relative">
              <div class="overflow-hidden" id="sliderWrapper">
                <div class="flex transition-transform duration-300 ease-in-out" id="testimonialSlider">
                  <!-- Testimonial Cards -->
                  <div class="bg-white rounded-lg shadow-sm flex-none w-[450px] h-[278px] mx-4 flex">
                    <!-- Content Section (left side) -->
                    <div class="px-4 flex-1 flex flex-col justify-center">
                      <div>
                        <div class="mb-4 flex items-center space-x-2">
                          <h4 class="text-lg font-medium">CR7 is goat</h4>
                          <span class="text-sm text-gray-500">Verified Buyer</span>
                        </div>

                        <!-- Rating Stars -->
                        <div class="flex mb-3">
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

                        <!-- Testimony -->
                        <p class="text-gray-600 text-xs mb-4 line-clamp-3">"The perfect plant for beginners! It looks amazing and hardly needs any care. Totally brightens up my space."</p>
                      </div>

                      <!-- Product Info Section - removed extra margins and padding -->
                      <div>
                        <hr class="h-0.5 bg-gray-200 mb-4" />
                        <div class="flex items-center gap-3">
                          <img src="./src/img/plant (1).png" alt="Snake Plant" class="w-12 h-16 object-cover" />
                          <span class="text-sm text-gray-700">Snake Plant Laurentii</span>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Image (right side) -->
                    <div class="w-[200px]">
                      <img src="./src/img/plant (1).png" alt="Customer" class="w-full h-full object-cover rounded-r-lg" />
                    </div>
                  </div>

                  <!-- card2 -->
                  <div class="bg-white rounded-lg shadow-sm flex-none w-[450px] h-[278px] mx-4 flex">
                    <!-- Content Section (left side) -->
                    <div class="px-4 flex-1 flex flex-col justify-center">
                      <div>
                        <div class="mb-4 flex items-center space-x-2">
                          <h4 class="text-lg font-medium">CR7 is goat</h4>
                          <span class="text-sm text-gray-500">Verified Buyer</span>
                        </div>

                        <!-- Rating Stars -->
                        <div class="flex mb-3">
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

                        <!-- Testimony -->
                        <p class="text-gray-600 text-xs mb-4 line-clamp-3">"The perfect plant for beginners! It looks amazing and hardly needs any care. Totally brightens up my space."</p>
                      </div>

                      <!-- Product Info Section - removed extra margins and padding -->
                      <div>
                        <hr class="h-0.5 bg-gray-200 mb-4" />
                        <div class="flex items-center gap-3">
                          <img src="./src/img/plant (1).png" alt="Snake Plant" class="w-12 h-16 object-cover" />
                          <span class="text-sm text-gray-700">Snake Plant Laurentii</span>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Image (right side) -->
                    <div class="w-[200px]">
                      <img src="./src/img/plant (1).png" alt="Customer" class="w-full h-full object-cover rounded-r-lg" />
                    </div>
                  </div>

                  <div class="bg-white flex rounded-lg shadow-sm flex-none w-[450px] h-[278px] mx-4">
                    <!-- Content Section (left side) -->
                    <div class="px-4 flex-1 flex flex-col justify-center">
                      <div>
                        <div class="mb-4 flex items-center space-x-2">
                          <h4 class="text-lg font-medium">CR7 is goat</h4>
                          <span class="text-sm text-gray-500">Verified Buyer</span>
                        </div>

                        <!-- Rating Stars -->
                        <div class="flex mb-3">
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

                        <!-- Testimony -->
                        <p class="text-gray-600 text-xs mb-4 line-clamp-3">"The perfect plant for beginners! It looks amazing and hardly needs any care. Totally brightens up my space."</p>
                      </div>

                      <!-- Product Info Section - removed extra margins and padding -->
                      <div>
                        <hr class="h-0.5 bg-gray-200 mb-4" />
                        <div class="flex items-center gap-3">
                          <img src="./src/img/plant (1).png" alt="Snake Plant" class="w-12 h-16 object-cover" />
                          <span class="text-sm text-gray-700">Snake Plant Laurentii</span>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Image (right side) -->
                    <div class="w-[200px]">
                      <img src="./src/img/plant (1).png" alt="Customer" class="w-full h-full object-cover rounded-r-lg" />
                    </div>
                  </div>

                  <!-- card3 -->
                  <div class="bg-white rounded-lg shadow-sm flex-none w-[450px] h-[278px] mx-4 flex">
                    <!-- Content Section (left side) -->
                    <div class="px-4 flex-1 flex flex-col justify-center">
                      <div>
                        <div class="mb-4 flex items-center space-x-2">
                          <h4 class="text-lg font-medium">CR7 is goat</h4>
                          <span class="text-sm text-gray-500">Verified Buyer</span>
                        </div>

                        <!-- Rating Stars -->
                        <div class="flex mb-3">
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

                        <!-- Testimony -->
                        <p class="text-gray-600 text-xs mb-4 line-clamp-3">"The perfect plant for beginners! It looks amazing and hardly needs any care. Totally brightens up my space."</p>
                      </div>

                      <!-- Product Info Section - removed extra margins and padding -->
                      <div>
                        <hr class="h-0.5 bg-gray-200 mb-4" />
                        <div class="flex items-center gap-3">
                          <img src="./src/img/plant (1).png" alt="Snake Plant" class="w-12 h-16 object-cover" />
                          <span class="text-sm text-gray-700">Snake Plant Laurentii</span>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Image (right side) -->
                    <div class="w-[200px]">
                      <img src="./src/img/plant (1).png" alt="Customer" class="w-full h-full object-cover rounded-r-lg" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Navigation Buttons -->
              <button id="prev" class="absolute left-1 top-1/2 transform -translate-y-1/2 text-3xl bg-gray-300 px-2 rounded-full text-primary opacity-50 hover:opacity-100">&#10094;</button>
              <button id="next" class="absolute -right-2 top-1/2 transform -translate-y-1/2 text-3xl bg-gray-300 px-2 rounded-full text-primary opacity-50 hover:opacity-100">&#10095;</button>
            </div>
          </div>
        </section>

        <!-- Subscribe Section -->
        <section class="py-12">
          <div class="mx-auto container px-2 md:px-16">
            <div class="relative rounded-2xl overflow-hidden py-32">
              <!-- Blurred Background Image -->
              <div class="absolute inset-0">
                <img src="./src/img/newslatter-img.png" alt="Background Image" class="w-full h-full object-cover blur-sm brightness-75" />
              </div>
              <!-- Content -->
              <div class="relative z-10 text-white flex flex-col md:flex-row items-start md:items-center space-y-8 md:space-y-0 px-4 lg:px-16">
                <!-- Main Text and Form -->
                <div class="flex-1 md:w-2/3 mr-10">
                  <h2 class="text-2xl md:text-5xl font-bold mb-4 whitespace-nowrap">Subscribe to our <span class="text-secondary">newsletter.</span></h2>
                  <p class="mb-6 text-sm md:pr-36">Stay connected with the latest plant care tips, seasonal promotions, and new arrivals from GreenNest. Join our green-loving community and let your inbox bloom with inspiration!</p>

                  <form class="flex space-x-4">
                    <input type="email" placeholder="Enter your email" class="py-2.5 px-4 placeholder:italic rounded-3xl text-gray-700 w-3/4 focus:outline-none" required />
                    <button class="bg-primary-color text-white py-3 px-2 md:px-6 rounded-3xl bg-secondary">Subscribe</button>
                  </form>
                </div>
                <!-- Icons and Descriptions -->
                <div class="flex flex-col ml-0 md:w-2/5 space-y-4 md:space-y-0 md:space-x-8 md:flex-row">
                  <div class="flex flex-col items-start space-y-2">
                    <div class="bg-white bg-opacity-50 p-4 rounded-xl flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none">
                        <path
                          d="M19.9231 19.9231C19.9231 20.709 19.2859 21.3462 18.5 21.3462C17.7141 21.3462 17.0769 20.709 17.0769 19.9231C17.0769 19.1371 17.7141 18.5 18.5 18.5C19.2859 18.5 19.9231 19.1371 19.9231 19.9231Z"
                          fill="#73AC32" />
                        <path
                          d="M9.96154 25.6154C10.7475 25.6154 11.3846 24.9782 11.3846 24.1923C11.3846 23.4064 10.7475 22.7692 9.96154 22.7692C9.17559 22.7692 8.53846 23.4064 8.53846 24.1923C8.53846 24.9782 9.17559 25.6154 9.96154 25.6154Z"
                          fill="#73AC32" />
                        <path
                          d="M11.3846 28.4615C11.3846 29.2475 10.7475 29.8846 9.96154 29.8846C9.17559 29.8846 8.53846 29.2475 8.53846 28.4615C8.53846 27.6756 9.17559 27.0385 9.96154 27.0385C10.7475 27.0385 11.3846 27.6756 11.3846 28.4615Z"
                          fill="#73AC32" />
                        <path
                          d="M14.2308 25.6154C15.0167 25.6154 15.6538 24.9782 15.6538 24.1923C15.6538 23.4064 15.0167 22.7692 14.2308 22.7692C13.4448 22.7692 12.8077 23.4064 12.8077 24.1923C12.8077 24.9782 13.4448 25.6154 14.2308 25.6154Z"
                          fill="#73AC32" />
                        <path
                          d="M15.6538 28.4615C15.6538 29.2475 15.0167 29.8846 14.2308 29.8846C13.4448 29.8846 12.8077 29.2475 12.8077 28.4615C12.8077 27.6756 13.4448 27.0385 14.2308 27.0385C15.0167 27.0385 15.6538 27.6756 15.6538 28.4615Z"
                          fill="#73AC32" />
                        <path
                          d="M18.5 25.6154C19.2859 25.6154 19.9231 24.9782 19.9231 24.1923C19.9231 23.4064 19.2859 22.7692 18.5 22.7692C17.7141 22.7692 17.0769 23.4064 17.0769 24.1923C17.0769 24.9782 17.7141 25.6154 18.5 25.6154Z"
                          fill="#73AC32" />
                        <path
                          d="M19.9231 28.4615C19.9231 29.2475 19.2859 29.8846 18.5 29.8846C17.7141 29.8846 17.0769 29.2475 17.0769 28.4615C17.0769 27.6756 17.7141 27.0385 18.5 27.0385C19.2859 27.0385 19.9231 27.6756 19.9231 28.4615Z"
                          fill="#73AC32" />
                        <path
                          d="M22.7692 25.6154C23.5552 25.6154 24.1923 24.9782 24.1923 24.1923C24.1923 23.4064 23.5552 22.7692 22.7692 22.7692C21.9833 22.7692 21.3462 23.4064 21.3462 24.1923C21.3462 24.9782 21.9833 25.6154 22.7692 25.6154Z"
                          fill="#73AC32" />
                        <path
                          d="M24.1923 28.4615C24.1923 29.2475 23.5552 29.8846 22.7692 29.8846C21.9833 29.8846 21.3462 29.2475 21.3462 28.4615C21.3462 27.6756 21.9833 27.0385 22.7692 27.0385C23.5552 27.0385 24.1923 27.6756 24.1923 28.4615Z"
                          fill="#73AC32" />
                        <path
                          d="M27.0385 25.6154C27.8244 25.6154 28.4615 24.9782 28.4615 24.1923C28.4615 23.4064 27.8244 22.7692 27.0385 22.7692C26.2525 22.7692 25.6154 23.4064 25.6154 24.1923C25.6154 24.9782 26.2525 25.6154 27.0385 25.6154Z"
                          fill="#73AC32" />
                        <path
                          d="M24.1923 19.9231C24.1923 20.709 23.5552 21.3462 22.7692 21.3462C21.9833 21.3462 21.3462 20.709 21.3462 19.9231C21.3462 19.1371 21.9833 18.5 22.7692 18.5C23.5552 18.5 24.1923 19.1371 24.1923 19.9231Z"
                          fill="#73AC32" />
                        <path
                          d="M27.0385 21.3462C27.8244 21.3462 28.4615 20.709 28.4615 19.9231C28.4615 19.1371 27.8244 18.5 27.0385 18.5C26.2525 18.5 25.6154 19.1371 25.6154 19.9231C25.6154 20.709 26.2525 21.3462 27.0385 21.3462Z"
                          fill="#73AC32" />
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M8.53846 0C9.3244 0 9.96154 0.637133 9.96154 1.42308V4.26923H27.0385V1.42308C27.0385 0.637133 27.6756 0 28.4615 0C29.2475 0 29.8846 0.637133 29.8846 1.42308V4.26923H31.3077C34.4515 4.26923 37 6.81776 37 9.96154V31.3077C37 34.4515 34.4515 37 31.3077 37H5.69231C2.54853 37 0 34.4515 0 31.3077V9.96154C0 6.81776 2.54853 4.26923 5.69231 4.26923H7.11538V1.42308C7.11538 0.637133 7.75252 0 8.53846 0ZM34.1538 17.0769C34.1538 15.505 32.8796 14.2308 31.3077 14.2308H5.69231C4.12042 14.2308 2.84615 15.505 2.84615 17.0769V31.3077C2.84615 32.8796 4.12042 34.1538 5.69231 34.1538H31.3077C32.8796 34.1538 34.1538 32.8796 34.1538 31.3077V17.0769Z"
                          fill="#73AC32" />
                      </svg>
                    </div>
                    <div class="text-left">
                      <h3 class="text-lg font-semibold">Weekly Article Content</h3>
                      <p class="text-sm">Helpful guides and inspiration to keep your indoor jungle thriving — no matter your plant experience.</p>
                    </div>
                  </div>
                  <div class="flex flex-col items-start space-y-2">
                    <div class="bg-white bg-opacity-50 p-4 rounded-xl flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="29" height="35" viewBox="0 0 29 35" fill="none">
                        <path
                          d="M11.4938 6.05312C11.4938 4.71211 10.4067 3.625 9.06564 3.625C7.72462 3.625 6.63751 4.71211 6.63751 6.05312L6.6375 10.6781M11.4938 6.05312L11.4938 3.74062C11.4938 2.39961 12.5809 1.3125 13.9219 1.3125C15.2629 1.3125 16.35 2.39961 16.35 3.74062L16.35 6.05312M11.4938 6.05312L11.6094 15.1875M16.35 16.3437V6.05312M16.35 6.05312C16.35 4.71211 17.4372 3.625 18.7782 3.625C20.1192 3.625 21.2063 4.71211 21.2063 6.05312V22.125M6.6375 10.6781C6.6375 9.33711 5.55039 8.25 4.20937 8.25C2.86836 8.25 1.78125 9.33711 1.78125 10.6781V23.2812C1.78125 29.0285 6.44029 33.6875 12.1875 33.6875H15.2984C17.445 33.6875 19.5037 32.8348 21.0215 31.3169L23.6919 28.6465C25.2098 27.1287 26.0625 25.07 26.0625 22.9234L26.0672 19.8032C26.0695 19.5364 26.1706 19.278 26.372 19.0766C27.3202 18.1284 27.3202 16.591 26.372 15.6428C25.4237 14.6945 23.8863 14.6945 22.9381 15.6428C21.7944 16.7864 21.2182 18.288 21.211 19.7839M6.6375 10.6781V17.5M16.3037 24.1569C16.9048 23.5559 17.5887 23.0882 18.3187 22.7538C19.233 22.335 20.2196 22.1254 21.2063 22.125M21.2092 22.125H21.2063"
                          stroke="#73AC32"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round" />
                      </svg>
                    </div>
                    <div class="text-left">
                      <h3 class="text-lg font-semibold">No Spam</h3>
                      <p class="text-sm">Only curated updates, plant tips, and special offers — no clutter and no junk.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End Of Subscribe Section -->
      </div>
    </main>

      <!-- memasukan navbar -->
    <?php include './component/footer.php'; ?>
  </body>
  <script src="./src/script.js"></script>
</html>

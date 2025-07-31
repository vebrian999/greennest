<?php
require_once __DIR__ . '/config/db.php'; // Tambahkan baris ini
session_start();
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;

// Query Best Seller
$bestSellerStmt = $conn->prepare("SELECT p.*, pi.image_url,
    (SELECT ROUND(AVG(r.rating),1) FROM reviews r WHERE r.product_id = p.id) as avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) as review_count,
    (SELECT COUNT(*) FROM product_likes l WHERE l.product_id = p.id) as like_count
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    WHERE p.is_best_seller = 1 OR p.product_label = 'BEST SELLER'
    ORDER BY p.created_at DESC
    LIMIT 10");
$bestSellerStmt->execute();
$bestSellerProducts = $bestSellerStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Query New Arrival (produk 30 hari terakhir)
$newArrivalStmt = $conn->prepare("SELECT p.*, pi.image_url,
    (SELECT ROUND(AVG(r.rating),1) FROM reviews r WHERE r.product_id = p.id) as avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) as review_count,
    (SELECT COUNT(*) FROM product_likes l WHERE l.product_id = p.id) as like_count
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY p.created_at DESC
    LIMIT 10");
$newArrivalStmt->execute();
$newArrivalProducts = $newArrivalStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Query Popular (review_count >= 10 atau like_count >= 10)
$popularStmt = $conn->prepare("SELECT p.*, pi.image_url,
    (SELECT ROUND(AVG(r.rating),1) FROM reviews r WHERE r.product_id = p.id) as avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) as review_count,
    (SELECT COUNT(*) FROM product_likes l WHERE l.product_id = p.id) as like_count
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    HAVING review_count >= 10 OR like_count >= 10
    ORDER BY p.created_at DESC
    LIMIT 10");
$popularStmt->execute();
$popularProducts = $popularStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Ambil 1 artikel terbaru dari tabel articles
$articleStmt = $conn->prepare("SELECT * FROM articles ORDER BY created_at DESC LIMIT 1");
$articleStmt->execute();
$article = $articleStmt->get_result()->fetch_assoc();
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
              <div class="cards-container flex gap-4 overflow-x-auto">

                <?php foreach ($bestSellerProducts as $cardProduct): ?>
                  <?php include './component/card.php'; ?>
                <?php endforeach; ?>
              </div>
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
              <div class="cards-container flex gap-4 overflow-x-auto">
                <?php foreach ($newArrivalProducts as $cardProduct): ?>
                  <?php include './component/card.php'; ?>
                <?php endforeach; ?>
              </div>
            </div>
        </section>

        <!-- Most Gifted Section -->
        <section class="py-12">
          <div class="mx-auto md:px-16 px-2 container">
            <div class="flex justify-between items-center mb-6 text-black">
              <h2 class="md:text-2xl text-xl">Popular</h2>
              <a href="./list-product.php" class="hover:underline md:text-base text-sm">SHOP ALL</a>
            </div>

            <div class="relative">
              <div class="cards-container flex gap-4 overflow-x-auto">
                <?php foreach ($popularProducts as $cardProduct): ?>
                  <?php include './component/card.php'; ?>
                <?php endforeach; ?>
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
            <div class="flex flex-col-reverse md:flex-row md:space-x-7">
              <!-- Text content -->
              <div class="space-y-10 md:w-2/3 mt-8 md:mt-0">
                <div class="space-y-1">
                  <p><?php echo htmlspecialchars($article['category'] ?? 'Care Tips & Guides'); ?></p>
                  <h1 class="text-2xl"><?php echo htmlspecialchars($article['title'] ?? 'Plant Care 101: Your Green Guide to Happy Plants'); ?></h1>
                </div>
                <div class="space-y-12">
                  <p>
                    <?php echo nl2br(htmlspecialchars($article['excerpt'] ?? 'Whether you\'re a new plant parent or looking to level up your indoor jungle game, mastering the basics of plant care is the first step to happy, thriving greenery.')); ?>
                  </p>
                  <p>
                  <?php
    // Ambil paragraf pertama dari content, batasi 50 kata
    $content = $article['content'] ?? '';
    $paragraphs = preg_split('/\r\n|\r|\n/', $content);
    $second = isset($paragraphs[0]) ? $paragraphs[0] : '';
    $words = explode(' ', $second);
    if (count($words) > 30) {
      $second = implode(' ', array_slice($words, 0, 30)) . '...';
    }
    echo nl2br(htmlspecialchars(trim($second)));
  ?>
                  </p>
                </div>
                <div>
                  <a href="./detail-article.php?id=<?php echo $article['id'] ?? 1; ?>" class="bg-primary text-white py-3 px-6 rounded-full text-base transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg block w-full md:inline-block md:w-auto text-center">
                    Read Full Article
                  </a>
                </div>
              </div>
              <!-- Image section -->
              <div class="md:w-full">
                <img src="<?php echo !empty($article['image_url']) ? htmlspecialchars($article['image_url']) : './src/img/article-img.png'; ?>" alt="" />
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

                  <!-- card4 -->
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

                  <!-- card4 -->
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

                  <!-- card4 -->
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

                  <!-- card4 -->
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

                  <!-- card4 -->
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

                  <!-- card4 -->
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
                      </svg>
                    </div>
                    <div class="flex flex-col items-start">
                      <span class="text-lg font-semibold">Free Shipping</span>
                      <span class="text-sm text-gray-500">On orders over $50</span>
                    </div>
                  </div>
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
                      </svg>
                    </div>
                    <div class="flex flex-col items-start">
                      <span class="text-lg font-semibold">24/7 Support</span>
                      <span class="text-sm text-gray-500">We're here to help</span>
                    </div>
                  </div>
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
                      </svg>
                    </div>
                    <div class="flex flex-col items-start">
                      <span class="text-lg font-semibold">Secure Payment</span>
                      <span class="text-sm text-gray-500">100% secure checkout</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>

    <!-- memasukan footer -->
    <?php include './component/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.0.0/swiper-bundle.min.js"></script>
    <script src="./src/script.js"></script>
  </body>
</html>
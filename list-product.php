<?php
session_start();
require_once 'config/db.php';

$where = [];
$params = [];
$types = '';

// PLANT TYPE
if (!empty($_GET['plant_type'])) {
    $plantTypes = $_GET['plant_type'];
    $in = implode(',', array_fill(0, count($plantTypes), '?'));
    $where[] = "p.category_name IN ($in)";
    $params = array_merge($params, $plantTypes);
    $types .= str_repeat('s', count($plantTypes));
}

// PRICE RANGE
if (!empty($_GET['price_range'])) {
    $priceWhere = [];
    foreach ($_GET['price_range'] as $range) {
        if ($range == 'under_20') $priceWhere[] = 'p.price < 20';
        if ($range == '20_50') $priceWhere[] = '(p.price >= 20 AND p.price <= 50)';
        if ($range == '50_100') $priceWhere[] = '(p.price > 50 AND p.price <= 100)';
        if ($range == 'above_100') $priceWhere[] = 'p.price > 100';
    }
    if ($priceWhere) $where[] = '(' . implode(' OR ', $priceWhere) . ')';
}

// PLANT SIZE
if (!empty($_GET['plant_size'])) {
    $sizes = $_GET['plant_size'];
    $in = implode(',', array_fill(0, count($sizes), '?'));
    $where[] = "p.plant_size IN ($in)";
    $params = array_merge($params, $sizes);
    $types .= str_repeat('s', count($sizes));
}

// PET FRIENDLY
if (!empty($_GET['pet_friendly'])) {
    $pf = $_GET['pet_friendly'];
    $in = implode(',', array_fill(0, count($pf), '?'));
    $where[] = "p.pet_friendly IN ($in)";
    $params = array_merge($params, $pf);
    $types .= str_repeat('s', count($pf));
}

// PRODUCT LABELS
if (!empty($_GET['product_label'])) {
    $labels = $_GET['product_label'];
    $labelWhere = [];
    foreach ($labels as $label) {
        if ($label == 'NEW ARRIVAL') {
            $labelWhere[] = "p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        } elseif ($label == 'POPULAR') {
            $labelWhere[] = "((SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) >= 10 OR (SELECT COUNT(*) FROM product_likes l WHERE l.product_id = p.id) >= 10)";
        } elseif ($label == 'LIMITED STOCK') {
            $labelWhere[] = "(p.stock > 0 AND p.stock < 50)";
        } elseif ($label == 'OUT OF STOCK') {
            $labelWhere[] = "(p.stock <= 0)";
        } else {
            $labelWhere[] = "p.product_label = ?";
            $params[] = $label;
            $types .= 's';
        }
    }
    if ($labelWhere) $where[] = '(' . implode(' OR ', $labelWhere) . ')';
}

// DIFFICULTY
if (!empty($_GET['difficulty'])) {
    $diff = $_GET['difficulty'];
    $in = implode(',', array_fill(0, count($diff), '?'));
    $where[] = "p.difficulty IN ($in)";
    $params = array_merge($params, $diff);
    $types .= str_repeat('s', count($diff));
}

// RATING (ambil rata-rata dari tabel reviews)
$ratingFilter = '';
if (!empty($_GET['rating'])) {
    $ratingWhere = [];
    foreach ($_GET['rating'] as $r) {
        if ($r == '5') $ratingWhere[] = 'avg_rating = 5';
        if ($r == '4') $ratingWhere[] = 'avg_rating >= 4';
        if ($r == '3') $ratingWhere[] = 'avg_rating >= 3';
    }
    if ($ratingWhere) $ratingFilter = '(' . implode(' OR ', $ratingWhere) . ')';
}

// SQL Query
$sql = "SELECT p.*, pi.image_url,
            (SELECT ROUND(AVG(r.rating),1) FROM reviews r WHERE r.product_id = p.id) as avg_rating,
            (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) as review_count,
            (SELECT COUNT(*) FROM product_likes l WHERE l.product_id = p.id) as like_count
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1";

if ($where || $ratingFilter) {
    $sql .= " WHERE ";
    $filters = [];
    if ($where) $filters[] = implode(' AND ', $where);
    if ($ratingFilter) $filters[] = $ratingFilter;
    $sql .= implode(' AND ', $filters);
}
$sql .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
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

        <?php include('./component/cart-sidebar.php'); ?>
    
    <!-- hero section -->
    <section class="md:pt-16">
      <div class="relative h-[625px] md:h-[560px] text-white overflow-hidden">
        <div class="absolute inset-0">
          <img src="./src/img/hero-image (2).png" alt="Background Image" class="object-cover object-center w-full h-full" />
          <div class="absolute inset-0 blur-md bg-[#8E8E8E33] opacity-50"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4 md:px-0">
          <h1 class="text-3xl md:text-5xl leading-tight mb-4 px-4 md:px-0">Grow Your Green Escape</h1>
          <p class="text-base md:text-xl px-4 md:px-72 text-[#F7F6F8] mb-8">Find beginner-friendly plants, expert advice, and stylish pots to transform your space into a lush oasis.</p>
          <a href="#" class="bg-secondary text-white py-2 md:py-2.5 px-4 md:px-6 rounded-full text-base md:text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg"> Explore Starter Plants </a>
        </div>
      </div>
    </section>
    <main class="pt-16 lg:px-16 flex gap-8 container px-2 mx-auto">
      <!-- Sidebar -->
      <aside class="fixed inset-0 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 bg-white md:w-64 md:flex-shrink-0 z-40" id="sidebar" aria-label="Sidebar">
       
        <!-- aside component -->
        <?php include './component/aside-filter.php'; ?>

      </aside>
    

      <!-- Main Content -->
      <div id="content" class="flex-1">
        <section>
          <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <?php foreach ($products as $product): ?>
              <?php
                // Kirim data produk ke card-main.php
                $cardProduct = $product;
                include './component/card-main.php';
              ?>
            <?php endforeach; ?>
          </div>
        </section>

      </div>
    </main>

              <!-- memasukan navbar -->
    <?php include './component/footer.php'; ?>  

    <!-- Add button to toggle filter on mobile -->
    <button id="filter-toggle" class="fixed bottom-4 right-4 bg-primary text-white p-4 rounded-full shadow-lg md:hidden z-50">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
      </svg>
    </button>

    <script>
      // Toggle filter sidebar on mobile
      const filterToggle = document.getElementById("filter-toggle");
      const sidebar = document.getElementById("sidebar");

      filterToggle.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-full");
      });

      // Close sidebar when clicking outside
      document.addEventListener("click", (e) => {
        if (!sidebar.contains(e.target) && !filterToggle.contains(e.target)) {
          sidebar.classList.add("-translate-x-full");
        }
      });

      // Close sidebar when screen resized to desktop
      window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
          // md breakpoint
          sidebar.classList.remove("-translate-x-full");
        }
      });
    </script>


<script src="./src/script.js"></script>
  </body>
</html>

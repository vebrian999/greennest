<?php
require_once '../config/db.php';
session_start();
// Pastikan session is_admin benar-benar 1 (admin)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}
// Statistik utama
function getCount($conn, $table) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
}
$userCount = getCount($conn, 'users');
$productCount = getCount($conn, 'products');
$orderCount = getCount($conn, 'orders');
$articleCount = getCount($conn, 'articles');
$reviewCount = getCount($conn, 'reviews');

// Statistik orders
$res = $conn->query("SELECT status, SUM(total_amount) as revenue, COUNT(*) as total FROM orders GROUP BY status");
$pendingOrders = 0;
$completedOrders = 0;
$totalRevenue = 0.00;
while ($row = $res->fetch_assoc()) {
    if ($row['status'] === 'pending') $pendingOrders = $row['total'];
    if ($row['status'] === 'completed') $completedOrders = $row['total'];
    $totalRevenue += floatval($row['revenue']);
}

// Statistik artikel per kategori
$categories = [
    'Jenis Tanaman',
    'Panduan & Perawatan',
    'Inspirasi & Dekorasi',
    'Lingkungan & Edukasi',
    'Komersial & Tren',
    'Eksperimen & DIY',
    'Tanaman Hias',
    'Tanaman Buah',
    'Tanaman Herbal',
    'Tanaman Outdoor'
];
$categoryCounts = array_fill_keys($categories, 0);
$res = $conn->query("SELECT category, COUNT(*) as total FROM articles GROUP BY category");
while ($row = $res->fetch_assoc()) {
    if (isset($categoryCounts[$row['category']])) {
        $categoryCounts[$row['category']] = $row['total'];
    }
}

// Top Selling Product
$topProduct = null;
$res = $conn->query("
    SELECT p.name, SUM(oi.quantity) as sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY sold DESC
    LIMIT 1
");
if ($row = $res->fetch_assoc()) {
    $topProduct = $row;
}

// Out of Stock Products
$res = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock <= 0");
$outOfStock = $res->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin | GreenNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#45671E',
                        secondary: '#73AC32',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg" id="sidebar">
        <div class="flex items-center justify-between h-16 px-6 bg-primary">
            <h1 class="text-xl font-bold text-white">GreenNest Admin</h1>
            <button class="lg:hidden text-white" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mt-8">
            <div class="px-6 py-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>
            </div>
            <ul class="mt-2 space-y-1">
                <li><a href="index.php" class="flex items-center px-6 py-3 bg-primary text-white"><i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>Dashboard</a></li>
                <li><a href="orders.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>Orders</a></li>
                <li><a href="products.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-seedling w-5 h-5 mr-3"></i>Products</a></li>
                <li><a href="users.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-users w-5 h-5 mr-3"></i>Users</a></li>
                <li><a href="articles.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-newspaper w-5 h-5 mr-3"></i>Articles</a></li>
                <li><a href="reviews.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-star w-5 h-5 mr-3"></i>Reviews</a></li>
            </ul>
            <div class="px-6 py-2 mt-8">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</p>
            </div>
            <ul class="mt-2 space-y-1">
                <li><a href="settings.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-cog w-5 h-5 mr-3"></i>Settings</a></li>
                <li><a href="logout.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-4 left-4 z-40">
        <button class="bg-primary text-white p-2 rounded-md shadow-lg" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">GreenNest Admin Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Overview & quick access to all management features</p>
                </div>
            </div>
        </header>
        <div class="px-6 py-6">
            <!-- Stats Cards: 5 kolom x 2 baris, card lebih kecil -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <!-- Baris 1 -->
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Users</p>
                        <p class="text-xl font-bold text-gray-900"><?= $userCount ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100">
                        <i class="fas fa-seedling text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Products</p>
                        <p class="text-xl font-bold text-gray-900"><?= $productCount ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-100">
                        <i class="fas fa-shopping-cart text-yellow-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Orders</p>
                        <p class="text-xl font-bold text-gray-900"><?= $orderCount ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-primary/10">
                        <i class="fas fa-newspaper text-primary text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Articles</p>
                        <p class="text-xl font-bold text-gray-900"><?= $articleCount ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100">
                        <i class="fas fa-star text-pink-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Reviews</p>
                        <p class="text-xl font-bold text-gray-900"><?= $reviewCount ?></p>
                    </div>
                </div>
                <!-- Baris 2 -->
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-100">
                        <i class="fas fa-fire text-indigo-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Top Selling Product</p>
                        <p class="text-base font-bold text-gray-900"><?= $topProduct ? htmlspecialchars($topProduct['name']) : '-' ?></p>
                        <p class="text-xs text-gray-500"><?= $topProduct ? $topProduct['sold'].' sold' : '' ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100">
                        <i class="fas fa-box-open text-red-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Out of Stock</p>
                        <p class="text-xl font-bold text-red-600"><?= $outOfStock ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Pending Orders</p>
                        <p class="text-xl font-bold text-gray-900"><?= $pendingOrders ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Completed Orders</p>
                        <p class="text-xl font-bold text-gray-900"><?= $completedOrders ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-primary/10">
                        <i class="fas fa-dollar-sign text-primary text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Total Revenue</p>
                        <p class="text-xl font-bold text-gray-900">$<?= number_format($totalRevenue, 2) ?></p>
                    </div>
                </div>
            </div>

            <!-- Artikel per kategori -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
                <h2 class="text-lg font-semibold mb-4 text-primary">Articles per Category</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <?php foreach ($categoryCounts as $cat => $count): ?>
                    <div class="bg-gray-50 rounded-lg px-3 py-2 text-center border">
                        <span class="block text-xs text-gray-500"><?= $cat ?></span>
                        <span class="block text-lg font-bold text-primary"><?= $count ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>




        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>
<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Handle Add/Edit/Delete
$error = '';
$editProduct = null;

// Tambah produk
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $botanical_name = trim($_POST['botanical_name'] ?? '');
    $common_names = trim($_POST['common_names'] ?? '');
    $detail_care = trim($_POST['detail_care'] ?? '');
    $whats_included = trim($_POST['whats_included'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $price_old = floatval($_POST['price_old'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $plant_size = trim($_POST['plant_size'] ?? '');
    $pet_friendly = trim($_POST['pet_friendly'] ?? '');
    $difficulty = trim($_POST['difficulty'] ?? '');
    $product_label = trim($_POST['product_label'] ?? '');
    $category_name = trim($_POST['category_name'] ?? '');

    // Upload image
    $product_image = '';
    if (!empty($_FILES['product_image']['name'])) {
        $targetDir = "../uploads/products/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES["product_image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $product_image = "uploads/products/" . $fileName;
        }
    }

    if ($name && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products 
            (name, description, botanical_name, common_names, detail_care, whats_included, price, price_old, stock, plant_size, pet_friendly, difficulty, product_label, category_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'ssssssddisssss',
            $name, $description, $botanical_name, $common_names, $detail_care, $whats_included,
            $price, $price_old, $stock, $plant_size, $pet_friendly, $difficulty, $product_label, $category_name
        );
        $stmt->execute();
        $newProductId = $stmt->insert_id;

        // Save image to product_images table
        if ($product_image) {
            $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 1)");
            $stmtImg->bind_param('is', $newProductId, $product_image);
            $stmtImg->execute();
        }
    } else {
        $error = 'Name and price required!';
    }
}

// Edit produk
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $botanical_name = trim($_POST['botanical_name'] ?? '');
    $common_names = trim($_POST['common_names'] ?? '');
    $detail_care = trim($_POST['detail_care'] ?? '');
    $whats_included = trim($_POST['whats_included'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $price_old = floatval($_POST['price_old'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $plant_size = trim($_POST['plant_size'] ?? '');
    $pet_friendly = trim($_POST['pet_friendly'] ?? '');
    $difficulty = trim($_POST['difficulty'] ?? '');
    $product_label = trim($_POST['product_label'] ?? '');
    $category_name = trim($_POST['category_name'] ?? '');

    // Upload image
    $product_image = '';
    if (!empty($_FILES['product_image']['name'])) {
        $targetDir = "../uploads/products/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES["product_image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $product_image = "uploads/products/" . $fileName;
        }
    }

    if ($id && $name && $price > 0) {
        $stmt = $conn->prepare("UPDATE products SET 
            name=?, description=?, botanical_name=?, common_names=?, detail_care=?, whats_included=?, price=?, price_old=?, stock=?, plant_size=?, pet_friendly=?, difficulty=?, product_label=?, category_name=? 
            WHERE id=?");
        $stmt->bind_param(
            'ssssssddisssssi',
            $name, $description, $botanical_name, $common_names, $detail_care, $whats_included,
            $price, $price_old, $stock, $plant_size, $pet_friendly, $difficulty, $product_label, $category_name, $id
        );
        $stmt->execute();

        // Update image if uploaded
        if ($product_image) {
            // Set all images to not main
            $conn->query("UPDATE product_images SET is_main=0 WHERE product_id=$id");
            // Insert new image as main
            $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 1)");
            $stmtImg->bind_param('is', $id, $product_image);
            $stmtImg->execute();
        }
    } else {
        $error = 'Name and price required!';
    }
}

// Hapus produk
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

// Ambil data produk
$result = $conn->query("SELECT p.*, 
    (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as product_image 
    FROM products p ORDER BY p.id DESC");

// Statistik Produk
$totalProducts = 0;
$totalStock = 0;
$outOfStock = 0;
$totalPrice = 0;
while ($row = $result->fetch_assoc()) {
    $totalProducts++;
    $totalStock += $row['stock'];
    if ($row['stock'] == 0) $outOfStock++;
    $totalPrice += $row['price'];
    $products[] = $row;
}
$avgPrice = $totalProducts ? ($totalPrice / $totalProducts) : 0;

// Jika ingin edit, ambil data produk
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editProduct = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Products | GreenNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <!-- Sidebar (copy dari orders.php) -->
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
                <li>
                    <a href="index.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="orders.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="products.php" class="flex items-center px-6 py-3 bg-primary text-white transition-colors">
                        <i class="fas fa-seedling w-5 h-5 mr-3"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="users.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="articles.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-newspaper w-5 h-5 mr-3"></i>
                        Articles
                    </a>
                </li>
                <li>
                    <a href="reviews.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-star w-5 h-5 mr-3"></i>
                        Reviews
                    </a>
                </li>
            </ul>
            <div class="px-6 py-2 mt-8">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</p>
            </div>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="settings.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        Logout
                    </a>
                </li>
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
                    <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage all products in your store</p>
                </div>
            </div>
        </header>

        <div class="px-6 py-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-seedling text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Products</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $totalProducts ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-boxes text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Stock</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $totalStock ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $outOfStock ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-dollar-sign text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Avg. Price</p>
                            <p class="text-2xl font-bold text-gray-900">$<?= number_format($avgPrice, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

         <!-- Form Add/Edit -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-7xl mx-auto mb-8 px-6 py-4">
    <h2 class="text-lg font-semibold mb-3 text-primary"><?= $editProduct ? 'Edit Product' : 'Add Product' ?></h2>
    <?php if ($error): ?>
        <div class="mb-3 text-red-500 text-center text-sm"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="space-y-6">
        <?php if ($editProduct): ?>
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Baris pertama -->
            <div>
                <label class="block text-xs font-medium mb-1">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($editProduct['name'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Botanical Name</label>
                <input type="text" name="botanical_name" value="<?= htmlspecialchars($editProduct['botanical_name'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Common Names</label>
                <input type="text" name="common_names" value="<?= htmlspecialchars($editProduct['common_names'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm">
            </div>

            <!-- Baris kedua -->
            <div>
                <label class="block text-xs font-medium mb-1">Plant Size</label>
                <input type="text" name="plant_size" value="<?= htmlspecialchars($editProduct['plant_size'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Price</label>
                <input type="number" name="price" step="0.01" value="<?= $editProduct['price'] ?? '' ?>" class="w-full border px-2 py-1 rounded text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Old Price</label>
                <input type="number" name="price_old" step="0.01" value="<?= $editProduct['price_old'] ?? '' ?>" class="w-full border px-2 py-1 rounded text-sm">
            </div>

            <!-- Baris ketiga -->
            <div>
                <label class="block text-xs font-medium mb-1">Stock</label>
                <input type="number" name="stock" value="<?= $editProduct['stock'] ?? '' ?>" class="w-full border px-2 py-1 rounded text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Pet Friendly</label>
                <select name="pet_friendly" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">-</option>
                    <option value="YES" <?= (isset($editProduct['pet_friendly']) && $editProduct['pet_friendly'] == 'YES') ? 'selected' : '' ?>>YES</option>
                    <option value="NO" <?= (isset($editProduct['pet_friendly']) && $editProduct['pet_friendly'] == 'NO') ? 'selected' : '' ?>>NO</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Difficulty</label>
                <select name="difficulty" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">-</option>
                    <option value="NO-FUSS" <?= (isset($editProduct['difficulty']) && $editProduct['difficulty'] == 'NO-FUSS') ? 'selected' : '' ?>>NO-FUSS</option>
                    <option value="MODERATE" <?= (isset($editProduct['difficulty']) && $editProduct['difficulty'] == 'MODERATE') ? 'selected' : '' ?>>MODERATE</option>
                    <option value="EASY" <?= (isset($editProduct['difficulty']) && $editProduct['difficulty'] == 'EASY') ? 'selected' : '' ?>>EASY</option>
                </select>
            </div>

            <!-- Baris keempat -->
            <div>
                <label class="block text-xs font-medium mb-1">Product Label</label>
                <select name="product_label" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">-</option>
                    <option value="BEST SELLER" <?= (isset($editProduct['product_label']) && $editProduct['product_label'] == 'BEST SELLER') ? 'selected' : '' ?>>Best Seller</option>
                    <option value="NEW ARRIVAL" <?= (isset($editProduct['product_label']) && $editProduct['product_label'] == 'NEW ARRIVAL') ? 'selected' : '' ?>>New Arrival</option>
                    <option value="LIMITED STOCK" <?= (isset($editProduct['product_label']) && $editProduct['product_label'] == 'LIMITED STOCK') ? 'selected' : '' ?>>Limited Stock</option>
                    <option value="OUT OF STOCK" <?= (isset($editProduct['product_label']) && $editProduct['product_label'] == 'OUT OF STOCK') ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Plant Type</label>
                <select name="category_name" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">-</option>
                    <option value="Indoor Plants" <?= (isset($editProduct['category_name']) && $editProduct['category_name'] == 'Indoor Plants') ? 'selected' : '' ?>>Indoor Plants</option>
                    <option value="Outdoor Plants" <?= (isset($editProduct['category_name']) && $editProduct['category_name'] == 'Outdoor Plants') ? 'selected' : '' ?>>Outdoor Plants</option>
                    <option value="Succulent" <?= (isset($editProduct['category_name']) && $editProduct['category_name'] == 'Succulent') ? 'selected' : '' ?>>Succulent</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Photo</label>
                <input type="file" name="product_image" accept="image/*" class="w-full border px-2 py-1 rounded text-sm">
                <?php if (!empty($editProduct['product_image'])): ?>
                    <img src="../<?= $editProduct['product_image'] ?>" alt="Product Image" class="mt-2 w-16 h-16 object-cover rounded">
                <?php endif; ?>
            </div>

            <!-- Baris kelima - textarea -->
            <div>
                <label class="block text-xs font-medium mb-1">Description</label>
                <textarea name="description" class="w-full border px-2 py-1 rounded text-sm" rows="3"><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">Detail Care</label>
                <textarea name="detail_care" class="w-full border px-2 py-1 rounded text-sm" rows="3"><?= htmlspecialchars($editProduct['detail_care'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1">What's Included</label>
                <textarea name="whats_included" class="w-full border px-2 py-1 rounded text-sm" rows="3"><?= htmlspecialchars($editProduct['whats_included'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-primary text-white py-2 rounded font-semibold hover:bg-green-600">
                <?= $editProduct ? 'Update Product' : 'Add Product' ?>
            </button>
            <?php if ($editProduct): ?>
                <div class="mt-2 text-center">
                    <a href="products.php" class="text-primary hover:underline text-xs">Cancel Edit</a>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

            </div>

            <!-- Filter and Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Stock:</label>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="stockFilter">
                                <option value="">All</option>
                                <option value="available">Available</option>
                                <option value="out">Out of Stock</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Label:</label>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="labelFilter">
                                <option value="">All</option>
                                <option value="BEST SELLER">Best Seller</option>
                                <option value="NEW ARRIVAL">New Arrival</option>
                                <option value="LIMITED STOCK">Limited Stock</option>
                                <option value="OUT OF STOCK">Out of Stock</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Plant Type:</label>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="typeFilter">
                                <option value="">All</option>
                                <option value="Indoor Plants">Indoor Plants</option>
                                <option value="Outdoor Plants">Outdoor Plants</option>
                                <option value="Succulent">Succulent</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Price:</label>
                            <input type="number" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-24" id="minPrice" placeholder="Min">
                            <span class="text-gray-500">-</span>
                            <input type="number" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-24" id="maxPrice" placeholder="Max">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Search:</label>
                            <input type="text" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="searchInput" placeholder="Product name...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Product</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Photo</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Price</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Stock</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Label</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Plant Type</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $row): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 font-mono text-sm font-medium">#<?= $row['id'] ?></td>
                                <td class="py-4 px-6 font-medium"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="py-4 px-6">
                                    <?php if ($row['product_image']): ?>
                                        <img src="../<?= $row['product_image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-16 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">$<?= number_format($row['price'], 2) ?></td>
                                <td class="py-4 px-6"><?= $row['stock'] ?></td>
                                <td class="py-4 px-6"><?= htmlspecialchars($row['product_label'] ?? '-') ?></td>
                                <td class="py-4 px-6"><?= htmlspecialchars($row['category_name'] ?? '-') ?></td>
                                <td class="py-4 px-6">
                                    <a href="products.php?edit=<?= $row['id'] ?>" class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors mr-2">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        // Produk data ke JS
        const productsData = <?= json_encode($products) ?>;

        function renderProducts(filtered) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';
            filtered.forEach(row => {
                tbody.innerHTML += `
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-mono text-sm font-medium">#${row.id}</td>
                        <td class="py-4 px-6 font-medium">${row.name}</td>
                        <td class="py-4 px-6">
                            ${row.product_image ? `<img src="../${row.product_image}" alt="${row.name}" class="w-16 h-16 object-cover rounded">` : `<span class="text-gray-400">-</span>`}
                        </td>
                        <td class="py-4 px-6">$${parseFloat(row.price).toFixed(2)}</td>
                        <td class="py-4 px-6">${row.stock}</td>
                        <td class="py-4 px-6">${row.product_label || '-'}</td>
                        <td class="py-4 px-6">${row.category_name || '-'}</td>
                        <td class="py-4 px-6">
                            <a href="products.php?edit=${row.id}" class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors mr-2">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="${row.id}">
                                <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
            });
        }

        function filterProducts() {
            const stock = document.getElementById('stockFilter').value;
            const label = document.getElementById('labelFilter').value;
            const type = document.getElementById('typeFilter').value;
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            const search = document.getElementById('searchInput').value.toLowerCase();
            let filtered = productsData;

            if (stock === 'available') {
                filtered = filtered.filter(p => p.stock > 0);
            } else if (stock === 'out') {
                filtered = filtered.filter(p => p.stock == 0);
            }
            if (label) {
                filtered = filtered.filter(p => (p.product_label || '').toUpperCase() === label.toUpperCase());
            }
            if (type) {
                filtered = filtered.filter(p => (p.category_name || '').toLowerCase() === type.toLowerCase());
            }
            filtered = filtered.filter(p => parseFloat(p.price) >= minPrice && parseFloat(p.price) <= maxPrice);
            if (search) {
                filtered = filtered.filter(p => (p.name || '').toLowerCase().includes(search));
            }
            renderProducts(filtered);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('stockFilter').addEventListener('change', filterProducts);
            document.getElementById('labelFilter').addEventListener('change', filterProducts);
            document.getElementById('typeFilter').addEventListener('change', filterProducts);
            document.getElementById('minPrice').addEventListener('input', filterProducts);
            document.getElementById('maxPrice').addEventListener('input', filterProducts);
            document.getElementById('searchInput').addEventListener('input', filterProducts);
        });
    </script>
</body>
</html>
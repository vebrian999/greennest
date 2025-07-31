<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Contoh: proses update password admin
$success = '';
$error = '';
if (isset($_POST['update_password'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $admin_id = $_SESSION['admin_id'] ?? 1;

    $res = $conn->query("SELECT password FROM users WHERE id='$admin_id' AND is_admin=1 LIMIT 1");
    $row = $res->fetch_assoc();
    if (!$row || !password_verify($old, $row['password'])) {
        $error = 'Old password is incorrect!';
    } elseif ($new !== $confirm) {
        $error = 'New password and confirmation do not match!';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hash' WHERE id='$admin_id'");
        $success = 'Password updated successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Settings | GreenNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../src/img/favicon.ico" type="image/x-icon" />
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
                <li><a href="index.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>Dashboard</a></li>
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
                <li><a href="settings.php" class="flex items-center px-6 py-3 bg-primary text-white"><i class="fas fa-cog w-5 h-5 mr-3"></i>Settings</a></li>
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
                    <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage your admin account and site settings</p>
                </div>
            </div>
        </header>
        <div class="px-6 py-6">
            <div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-lg font-semibold mb-4 text-primary flex items-center">
                    <i class="fas fa-user-cog mr-2"></i>Change Admin Password
                </h2>
                <?php if ($success): ?>
                    <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200"><?= $success ?></div>
                <?php elseif ($error): ?>
                    <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200"><?= $error ?></div>
                <?php endif; ?>
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Old Password</label>
                        <input type="password" name="old_password" required class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" required class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="confirm_password" required class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="update_password" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-save mr-1"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tambahkan pengaturan lain di bawah sini jika diperlukan -->
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
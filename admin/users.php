<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Handle Add/Edit/Delete
$error = '';
$editUser = null;

// Tambah user
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $password = trim($_POST['password'] ?? '');

    // Cek email sudah ada
    $cek = $conn->prepare("SELECT id FROM users WHERE email=?");
    $cek->bind_param('s', $email);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows > 0) {
        $error = 'Email already exists!';
    } elseif ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, is_admin, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssis', $name, $email, $is_admin, $hash);
        $stmt->execute();
    } else {
        $error = 'Name, email, and password required!';
    }
}

// Edit user
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $password = trim($_POST['password'] ?? '');

    if ($id && $name && $email) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, is_admin=?, password=? WHERE id=?");
            $stmt->bind_param('ssisi', $name, $email, $is_admin, $hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, is_admin=? WHERE id=?");
            $stmt->bind_param('ssii', $name, $email, $is_admin, $id);
        }
        $stmt->execute();
    } else {
        $error = 'Name and email required!';
    }
}

// Hapus user
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

// Ambil data user
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Jika ingin edit, ambil data user
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editUser = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Users | GreenNest</title>
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
                    <a href="products.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fas fa-seedling w-5 h-5 mr-3"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="users.php" class="flex items-center px-6 py-3 bg-primary text-white transition-colors">
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
                    <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage all users in your store</p>
                </div>
            </div>
        </header>

        <div class="px-6 py-6">
            <!-- Form Add/Edit -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full mb-8 px-6 py-4">
                <h2 class="text-lg font-semibold mb-3 text-primary"><?= $editUser ? 'Edit User' : 'Add User' ?></h2>
                <?php if ($error): ?>
                    <div class="mb-3 text-red-500 text-center text-sm"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post" class="space-y-3">
                    <?php if ($editUser): ?>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
                    <?php else: ?>
                        <input type="hidden" name="action" value="add">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium mb-1">Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($editUser['name'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1">Password <?= $editUser ? '(leave blank to keep)' : '' ?></label>
                            <input type="password" name="password" class="w-full border px-2 py-1 rounded text-sm" <?= $editUser ? '' : 'required' ?>>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1">Is Admin</label>
                            <input type="checkbox" name="is_admin" value="1" <?= (!empty($editUser['is_admin'])) ? 'checked' : '' ?>>
                            <span class="text-sm">Admin</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded font-semibold hover:bg-green-600">
                        <?= $editUser ? 'Update User' : 'Add User' ?>
                    </button>
                    <?php if ($editUser): ?>
                        <div class="mt-2 text-center">
                            <a href="users.php" class="text-primary hover:underline text-xs">Cancel Edit</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Name</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Email</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Role</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $row): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 font-mono text-sm font-medium">#<?= $row['id'] ?></td>
                                <td class="py-4 px-6 font-medium"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="py-4 px-6"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="py-4 px-6">
                                    <?= !empty($row['is_admin']) ? 'Admin' : 'User' ?>
                                </td>
                                <td class="py-4 px-6">
                                    <a href="users.php?edit=<?= $row['id'] ?>" class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors mr-2">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this user?')">
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
    </script>
</body>
</html>
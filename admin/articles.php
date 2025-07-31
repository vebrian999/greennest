<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$error = '';
$editArticle = null;

// Tambah artikel
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $author_id = $_SESSION['user_id'] ?? 1;
    $created_at = date('Y-m-d H:i:s');

    // Upload image
    $image_url = '';
    if (!empty($_FILES['image_url']['name'])) {
        $targetDir = "../uploads/artikel/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES["image_url"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFile)) {
            $image_url = "uploads/artikel/" . $fileName;
        }
    }

    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, image_url, created_at, category, excerpt) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssissss', $title, $content, $author_id, $image_url, $created_at, $category, $excerpt);
        $stmt->execute();
    } else {
        $error = 'Title and content required!';
    }
}

// Edit artikel
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');

    // Upload image
    $image_url = '';
    if (!empty($_FILES['image_url']['name'])) {
        $targetDir = "../uploads/artikel/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES["image_url"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFile)) {
            $image_url = "uploads/artikel/" . $fileName;
        }
    }

    if ($id && $title && $content) {
        if ($image_url) {
            $stmt = $conn->prepare("UPDATE articles SET title=?, content=?, category=?, excerpt=?, image_url=? WHERE id=?");
            $stmt->bind_param('sssssi', $title, $content, $category, $excerpt, $image_url, $id);
        } else {
            $stmt = $conn->prepare("UPDATE articles SET title=?, content=?, category=?, excerpt=? WHERE id=?");
            $stmt->bind_param('ssssi', $title, $content, $category, $excerpt, $id);
        }
        $stmt->execute();
    } else {
        $error = 'Title and content required!';
    }
}

// Hapus artikel
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM articles WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

// Ambil data artikel
$result = $conn->query("SELECT a.*, u.name as author_name FROM articles a LEFT JOIN users u ON a.author_id = u.id ORDER BY a.id DESC");
$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

// Jika ingin edit, ambil data artikel
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editArticle = $res->fetch_assoc();
}

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

// Hitung total artikel
$totalArticles = count($articles);

// Hitung total artikel per kategori
$categoryCounts = array_fill_keys($categories, 0);
foreach ($articles as $row) {
    if (isset($categoryCounts[$row['category']])) {
        $categoryCounts[$row['category']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Articles | GreenNest</title>
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
                <li><a href="index.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>Dashboard</a></li>
                <li><a href="orders.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>Orders</a></li>
                <li><a href="products.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-seedling w-5 h-5 mr-3"></i>Products</a></li>
                <li><a href="users.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-users w-5 h-5 mr-3"></i>Users</a></li>
                <li><a href="articles.php" class="flex items-center px-6 py-3 bg-primary text-white"><i class="fas fa-newspaper w-5 h-5 mr-3"></i>Articles</a></li>
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
                    <h1 class="text-2xl font-bold text-gray-900">Articles Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage all articles and blog posts</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search articles..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="searchInput">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-leaf text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Articles</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $totalArticles ?></p>
                        </div>
                    </div>
                </div>
                <?php foreach ($categoryCounts as $cat => $count): ?>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-primary/10 rounded-full">
                            <i class="fas fa-folder-open text-primary"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600"><?= $cat ?></p>
                            <p class="text-2xl font-bold text-gray-900"><?= $count ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Form Add/Edit -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-3 text-primary"><?= $editArticle ? 'Edit Article' : 'Add Article' ?></h2>
                    <?php if ($error): ?>
                        <div class="mb-3 text-red-500 text-center text-sm"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" enctype="multipart/form-data" class="space-y-3">
                        <?php if ($editArticle): ?>
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= $editArticle['id'] ?>">
                        <?php else: ?>
                            <input type="hidden" name="action" value="add">
                        <?php endif; ?>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium mb-1">Title</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($editArticle['title'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Category</label>
                                <select name="category" class="w-full border px-2 py-1 rounded text-sm" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat ?>" <?= (isset($editArticle['category']) && $editArticle['category'] == $cat) ? 'selected' : '' ?>>
                                            <?= $cat ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Excerpt</label>
                                <input type="text" name="excerpt" value="<?= htmlspecialchars($editArticle['excerpt'] ?? '') ?>" class="w-full border px-2 py-1 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Image</label>
                                <input type="file" name="image_url" accept="image/*" class="w-full border px-2 py-1 rounded text-sm">
                                <?php if (!empty($editArticle['image_url'])): ?>
                                    <img src="../<?= $editArticle['image_url'] ?>" alt="Article Image" class="mt-2 w-16 h-16 object-cover rounded">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1">Content</label>
                            <textarea name="content" class="w-full border px-2 py-1 rounded text-sm" rows="6" required><?= htmlspecialchars($editArticle['content'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-2 rounded font-semibold hover:bg-green-600">
                            <?= $editArticle ? 'Update Article' : 'Add Article' ?>
                        </button>
                        <?php if ($editArticle): ?>
                            <div class="mt-2 text-center">
                                <a href="articles.php" class="text-primary hover:underline text-xs">Cancel Edit</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Articles Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Title</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Author</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Image</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Created At</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $row): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 font-mono text-sm font-medium">#<?= $row['id'] ?></td>
                                <td class="py-4 px-6 font-medium"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="py-4 px-6"><?= htmlspecialchars($row['category'] ?? '-') ?></td>
                                <td class="py-4 px-6"><?= htmlspecialchars($row['author_name'] ?? '-') ?></td>
                                <td class="py-4 px-6">
                                    <?php if ($row['image_url']): ?>
                                        <img src="../<?= $row['image_url'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="w-16 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6"><?= $row['created_at'] ? date('M d, Y H:i', strtotime($row['created_at'])) : '-' ?></td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="articles.php?edit=<?= $row['id'] ?>" class="bg-primary flex items-center text-white px-4 py-2.5 rounded-lg text-xs hover:bg-green-600 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Delete this article?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="bg-red-600 text-white flex items-center rounded-lg text-xs px-4 py-2.5 hover:bg-red-700 transition-colors">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
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
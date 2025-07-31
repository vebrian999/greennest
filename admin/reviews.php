<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// --- Handle Delete ---
if (isset($_POST['delete_review'])) {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
if (isset($_POST['delete_comment'])) {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM article_comments WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

// --- Handle Edit ---
if (isset($_POST['edit_review'])) {
    $id = intval($_POST['id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    if ($id && $comment) {
        $stmt = $conn->prepare("UPDATE reviews SET comment=?, rating=? WHERE id=?");
        $stmt->bind_param('sii', $comment, $rating, $id);
        $stmt->execute();
    }
}
if (isset($_POST['edit_comment'])) {
    $id = intval($_POST['id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($id && $comment) {
        $stmt = $conn->prepare("UPDATE article_comments SET comment=? WHERE id=?");
        $stmt->bind_param('si', $comment, $id);
        $stmt->execute();
    }
}

// --- Fetch Data ---
$productReviews = [];
$res = $conn->query("SELECT r.*, p.name as product_name, u.name as user_name 
    FROM reviews r 
    LEFT JOIN products p ON r.product_id = p.id 
    LEFT JOIN users u ON r.user_id = u.id 
    ORDER BY r.id DESC");
while ($row = $res->fetch_assoc()) {
    $productReviews[] = $row;
}

$articleComments = [];
$res = $conn->query("SELECT ac.*, a.title as article_title, u.name as user_name 
    FROM article_comments ac 
    LEFT JOIN articles a ON ac.article_id = a.id 
    LEFT JOIN users u ON ac.user_id = u.id 
    ORDER BY ac.id DESC");
while ($row = $res->fetch_assoc()) {
    $articleComments[] = $row;
}

// --- Stats ---
$totalProductReviews = count($productReviews);
$totalArticleComments = count($articleComments);
$totalAllComments = $totalProductReviews + $totalArticleComments;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Reviews & Comments | GreenNest</title>
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
                <li><a href="articles.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-newspaper w-5 h-5 mr-3"></i>Articles</a></li>
                <li><a href="reviews.php" class="flex items-center px-6 py-3 bg-primary text-white"><i class="fas fa-star w-5 h-5 mr-3"></i>Reviews & Comments</a></li>
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
        <!-- Header & Stats -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reviews & Comments Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage all product reviews and article comments</p>
                </div>
            </div>
        </header>
        <div class="px-6 py-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full"><i class="fas fa-star text-yellow-600"></i></div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Product Reviews</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $totalProductReviews ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full"><i class="fas fa-comments text-blue-600"></i></div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Article Comments</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $totalArticleComments ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
                    <div class="p-3 bg-green-100 rounded-full"><i class="fas fa-list text-green-600"></i></div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Comments</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $totalAllComments ?></p>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6">
                <nav class="flex space-x-2" id="reviewTabs">
                    <button class="tab-btn bg-primary text-white px-4 py-2 rounded-t-lg font-semibold" data-tab="product">Product Reviews</button>
                    <button class="tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t-lg font-semibold" data-tab="article">Article Comments</button>
                </nav>
            </div>

            <!-- Product Reviews Table -->
            <div id="tab-product" class="tab-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Product</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">User</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Rating</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Comment</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Image</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Created At</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productReviews as $row): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6 font-mono text-sm font-medium">#<?= $row['id'] ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['product_name'] ?? '-') ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['user_name'] ?? '-') ?></td>
                                        <td class="py-4 px-6"><?= $row['rating'] ?? '-' ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['comment'] ?? '-') ?></td>
                                        <td class="py-4 px-6">
                                            <?php if (!empty($row['image_url'])): ?>
                                                <img src="../<?= $row['image_url'] ?>" alt="Review Image" class="w-16 h-16 object-cover rounded">
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-6"><?= $row['created_at'] ? date('M d, Y H:i', strtotime($row['created_at'])) : '-' ?></td>
                                        <td class="py-4 px-6">
                                            <div class="flex gap-2">
                                                <button class="bg-primary text-white px-3 py-1.5 flex items-center space-x-2 rounded-lg text-xs hover:bg-green-600 btn-edit-review"
                                                    data-id="<?= $row['id'] ?>" data-comment="<?= htmlspecialchars($row['comment']) ?>" data-rating="<?= $row['rating'] ?>">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <form method="post" onsubmit="return confirm('Delete this review?')">
                                                    <input type="hidden" name="delete_review" value="1">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <button type="submit" class="bg-red-600 flex items-center space-x-2 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700">
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

            <!-- Article Comments Table -->
            <div id="tab-article" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Article</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">User</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Comment</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Created At</th>
                                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articleComments as $row): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6 font-mono text-sm font-medium">#<?= $row['id'] ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['article_title'] ?? '-') ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['user_name'] ?? '-') ?></td>
                                        <td class="py-4 px-6"><?= htmlspecialchars($row['comment'] ?? '-') ?></td>
                                        <td class="py-4 px-6"><?= $row['created_at'] ? date('M d, Y H:i', strtotime($row['created_at'])) : '-' ?></td>
                                        <td class="py-4 px-6">
                                            <button class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors btn-edit-comment" data-id="<?= $row['id'] ?>" data-comment="<?= htmlspecialchars($row['comment']) ?>">Edit</button>
                                            <form method="post" style="display:inline;" onsubmit="return confirm('Delete this comment?')">
                                                <input type="hidden" name="delete_comment" value="1">
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

            <!-- Edit Modal -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" id="editModal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-gray-900" id="editModalTitle">Edit</h2>
                            <button class="text-gray-400 hover:text-gray-600" onclick="closeEditModal()">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <form id="editForm" class="p-6 space-y-4" method="post">
                            <input type="hidden" name="id" id="editId" />
                            <div id="editRatingGroup" class="hidden">
                                <label>Rating</label>
                                <input type="number" name="rating" id="editRating" min="1" max="5" class="border rounded px-3 py-2 w-full" />
                            </div>
                            <div>
                                <label>Comment</label>
                                <textarea name="comment" id="editComment" class="border rounded px-3 py-2 w-full" rows="4"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600" id="editSubmitBtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('bg-primary', 'text-white'));
                btn.classList.add('bg-primary', 'text-white');
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
                document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
            });
        });

        // Edit Modal
        function openEditModal(type, id, comment, rating = null) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editId').value = id;
            document.getElementById('editComment').value = comment;
            document.getElementById('editModalTitle').textContent = type === 'review' ? 'Edit Product Review' : 'Edit Article Comment';
            document.getElementById('editForm').reset();
            if (type === 'review') {
                document.getElementById('editRatingGroup').classList.remove('hidden');
                document.getElementById('editRating').value = rating;
                document.getElementById('editSubmitBtn').name = 'edit_review';
            } else {
                document.getElementById('editRatingGroup').classList.add('hidden');
                document.getElementById('editSubmitBtn').name = 'edit_comment';
            }
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        document.querySelectorAll('.btn-edit-review').forEach(btn => {
            btn.addEventListener('click', function() {
                openEditModal('review', btn.dataset.id, btn.dataset.comment, btn.dataset.rating);
            });
        });
        document.querySelectorAll('.btn-edit-comment').forEach(btn => {
            btn.addEventListener('click', function() {
                openEditModal('comment', btn.dataset.id, btn.dataset.comment);
            });
        });

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>
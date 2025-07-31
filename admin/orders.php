<?php
include '../config/db.php';

session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}       

// --- CREATE ORDER ---
if (isset($_POST['create_order'])) {
    // Contoh: Ambil data dari form POST
    $user_id = intval($_POST['user_id']);
    $order_date = date('Y-m-d H:i:s');
    $status = $_POST['status'];
    $payment_method = $_POST['payment_method'];
    $shipping_address = $_POST['shipping_address'];
    $total_amount = floatval($_POST['total_amount']);
    $shipping_cost = floatval($_POST['shipping_cost']);
    $tracking_number = $_POST['tracking_number'];

    // Insert order
    $sql = "INSERT INTO orders (user_id, order_date, status, payment_method, shipping_address, total_amount, shipping_cost, tracking_number)
            VALUES ('$user_id', '$order_date', '$status', '$payment_method', '$shipping_address', '$total_amount', '$shipping_cost', '$tracking_number')";
    mysqli_query($conn, $sql);
    $order_id = mysqli_insert_id($conn);

    // Insert order items (contoh: dari array POST)
    if (!empty($_POST['items'])) {
        foreach ($_POST['items'] as $item) {
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')");
        }
    }
    header("Location: orders.php");
    exit;
}

// --- UPDATE ORDER ---
if (isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $payment_method = $_POST['payment_method'];
    $shipping_address = $_POST['shipping_address'];
    $total_amount = floatval($_POST['total_amount']);
    $shipping_cost = floatval($_POST['shipping_cost']);
    $tracking_number = $_POST['tracking_number'];

    // Update order
    $sql = "UPDATE orders SET 
            status='$status', 
            payment_method='$payment_method', 
            shipping_address='$shipping_address', 
            total_amount='$total_amount', 
            shipping_cost='$shipping_cost', 
            tracking_number='$tracking_number'
            WHERE id='$order_id'";
    mysqli_query($conn, $sql);

    // Update order items (optional: delete old then insert new)
    if (!empty($_POST['items'])) {
        mysqli_query($conn, "DELETE FROM order_items WHERE order_id='$order_id'");
        foreach ($_POST['items'] as $item) {
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')");
        }
    }

    // Ambil user_id dari order
    $getUser = mysqli_query($conn, "SELECT user_id FROM orders WHERE id='$order_id'");
    $userRow = mysqli_fetch_assoc($getUser);
    $userId = $userRow['user_id'];

    // Buat pesan notifikasi sesuai status
    $notifMsg = '';
    $notifType = '';
    switch ($status) {
        case 'pending':
            $notifMsg = "Pesanan Anda sedang menunggu konfirmasi admin.";
            $notifType = 'pending';
            break;
        case 'paid':
            $notifMsg = "Pembayaran pesanan Anda telah diterima.";
            $notifType = 'paid';
            break;
        case 'shipped':
            $notifMsg = "Pesanan Anda telah dikirim. Silakan cek resi pengiriman.";
            $notifType = 'shipped';
            break;
        case 'delivered':
            $notifMsg = "Pesanan Anda telah diterima. Terima kasih telah berbelanja!";
            $notifType = 'delivered';
            break;
        case 'completed':
            $notifMsg = "Pesanan Anda telah selesai. Silakan beri ulasan!";
            $notifType = 'completed';
            break;
        case 'cancelled':
            $notifMsg = "Pesanan Anda telah dibatalkan oleh admin.";
            $notifType = 'cancelled';
            break;
        default:
            $notifMsg = "Status pesanan Anda telah diperbarui.";
            $notifType = 'info';
    }

    // Insert ke tabel notifications
    $stmtNotif = $conn->prepare("INSERT INTO notifications (user_id, message, type, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
    $stmtNotif->bind_param('iss', $userId, $notifMsg, $notifType);
    $stmtNotif->execute();

    header("Location: orders.php");
    exit;
}

// --- DELETE ORDER ---
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM order_items WHERE order_id='$order_id'");
    mysqli_query($conn, "DELETE FROM orders WHERE id='$order_id'");
    header("Location: orders.php");
    exit;
}

// --- READ ORDERS (default) ---
$orders = [];
$sql = "SELECT o.*, u.name as customer_name, u.email as customer_email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.id DESC";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $order_id = $row['id'];
    $items = [];
    $sql_items = "SELECT oi.*, p.name as product_name, 
    (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as product_image
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = $order_id";
    $res_items = mysqli_query($conn, $sql_items);
    while ($item = mysqli_fetch_assoc($res_items)) {
        $items[] = $item;
    }
    $row['items'] = $items;
    $orders[] = $row;
}

// Statistik Orders
$totalOrders = count($orders);
$pendingOrders = 0;
$completedOrders = 0;
$totalRevenue = 0.00;

foreach ($orders as $order) {
    if ($order['status'] === 'pending') $pendingOrders++;
    if ($order['status'] === 'completed') $completedOrders++;
    $totalRevenue += floatval($order['total_amount']);
}

// --- EDIT FORM DATA ---
$edit_order = null;
if (isset($_GET['edit'])) {
    $order_id = intval($_GET['edit']);
    $sql = "SELECT * FROM orders WHERE id='$order_id'";
    $res = mysqli_query($conn, $sql);
    $edit_order = mysqli_fetch_assoc($res);

    // Ambil items
    $edit_order['items'] = [];
    $sql_items = "SELECT oi.*, p.name as product_name, 
    (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as product_image
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = $order_id";
    $res_items = mysqli_query($conn, $sql_items);
    while ($item = mysqli_fetch_assoc($res_items)) {
        $edit_order['items'][] = $item;
    }
}

// --- Untuk detail order (read single) ---
$view_order = null;
if (isset($_GET['view'])) {
    $order_id = intval($_GET['view']);
    foreach ($orders as $order) {
        if ($order['id'] == $order_id) {
            $view_order = $order;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders | GreenNest</title>
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
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" id="sidebar">
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
                    <a href="orders.php" class="flex items-center px-6 py-3 bg-primary text-white transition-colors">
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
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                        <p class="text-sm text-gray-600 mt-1">Manage and track all customer orders</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search orders..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="searchInput">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-shopping-cart text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalOrders"><?= $totalOrders ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                            <p class="text-2xl font-bold text-gray-900" id="pendingOrders"><?= $pendingOrders ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900" id="completedOrders"><?= $completedOrders ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-primary/10 rounded-full">
                            <i class="fas fa-dollar-sign text-primary"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text- font-bold text-gray-900" id="totalRevenue">$<?= number_format($totalRevenue, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Status:</label>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Payment:</label>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm" id="paymentFilter">
                                <option value="">All Methods</option>
                                <option value="transfer">Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="cod">COD</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Date Range:</label>
                            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <span class="text-gray-500">to</span>
                            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Order ID</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Customer</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Date</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Payment</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Total</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <?php foreach ($orders as $order): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors" data-order-id="<?= $order['id'] ?>">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                        <span class="font-mono text-sm font-medium">#<?= $order['id'] ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-primary text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></p>
                                            <p class="text-sm text-gray-500">User ID: <?= $order['user_id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= date('M d, Y', strtotime($order['order_date'])) ?></p>
                                        <p class="text-xs text-gray-500"><?= date('H:i:s', strtotime($order['order_date'])) ?></p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <?php if ($order['payment_method'] == 'cod'): ?>
                                            <i class="fas fa-truck text-gray-400 mr-2"></i>
                                        <?php elseif ($order['payment_method'] == 'ewallet'): ?>
                                            <i class="fas fa-mobile-alt text-gray-400 mr-2"></i>
                                        <?php else: ?>
                                            <i class="fas fa-credit-card text-gray-400 mr-2"></i>
                                        <?php endif; ?>
                                        <span class="text-sm text-gray-700"><?= strtoupper($order['payment_method']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-semibold text-gray-900">$<?= number_format($order['total_amount'], 2) ?></p>
                                        <p class="text-xs text-gray-500"><?= $order['shipping_cost'] > 0 ? '+$'.number_format($order['shipping_cost'],2).' shipping' : 'Free shipping' ?></p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <button class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors btn-edit" data-id="<?= $order['id'] ?>">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition-colors btn-delete" data-id="<?= $order['id'] ?>">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                        <button class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-blue-700 transition-colors btn-view" data-id="<?= $order['id'] ?>">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">3</span> orders
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                                Previous
                            </button>
                            <button class="px-3 py-2 text-sm text-white bg-primary border border-primary rounded-md">
                                1
                            </button>
                            <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" id="orderModal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Order Details</h2>
                        <button class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6" id="orderModalContent">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" id="editOrderModal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Edit Order</h2>
                    <button class="text-gray-400 hover:text-gray-600" onclick="closeEditModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editOrderForm" class="p-6 space-y-4">
                    <!-- Isi form edit order di sini, contoh: -->
                    <input type="hidden" name="order_id" id="editOrderId" />
                    <div>
                        <label>Status</label>
                        <select name="status" id="editOrderStatus" class="border rounded px-3 py-2 w-full">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label>Payment Method</label>
                        <select name="payment_method" id="editOrderPayment" class="border rounded px-3 py-2 w-full">
                            <option value="transfer">Transfer</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="cod">COD</option>
                        </select>
                    </div>
                    <div>
                        <label>Shipping Address</label>
                        <input type="text" name="shipping_address" id="editOrderAddress" class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div>
                        <label>Total Amount</label>
                        <input type="number" name="total_amount" id="editOrderTotal" class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div>
                        <label>Shipping Cost</label>
                        <input type="number" name="shipping_cost" id="editOrderShipping" class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div>
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" id="editOrderTracking" class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="update_order" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-green-600">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        // Toggle dropdown menu
        function toggleDropdown(orderId) {
            const dropdown = document.getElementById(`dropdown-${orderId}`);
            
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (d.id !== `dropdown-${orderId}`) {
                    d.classList.add('hidden');
                }
            });
            
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick^="toggleDropdown"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        });

        // 1. Kirim semua data order ke JS
        const ordersData = <?= json_encode($orders) ?>;

        // 2. Fungsi View Modal
        function viewOrder(orderId) {
            const modal = document.getElementById('orderModal');
            const content = document.getElementById('orderModalContent');
            const order = ordersData.find(o => o.id == orderId);
            if (!order) return;

            content.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-medium">#${order.id}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium">${order.order_date}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            ${order.status}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-medium">${order.payment_method}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tracking Number:</span>
                        <span class="font-medium">${order.tracking_number}</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium">${order.customer_name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">${order.customer_email}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left py-2">Product</th>
                            <th class="text-left py-2">Photo</th>
                            <th class="text-left py-2">Qty</th>
                            <th class="text-left py-2">Price</th>
                            <th class="text-left py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${(order.items || []).map(item => `
                            <tr>
                                <td class="py-2 font-medium">${item.product_name}</td>
                                <td class="py-2">
                                    ${item.product_image ? `<img src="../${item.product_image}" alt="${item.product_name}" class="w-16 h-16 object-cover rounded">` : '-'}
                                </td>
                                <td class="py-2">${item.quantity}</td>
                                <td class="py-2">$${parseFloat(item.price).toFixed(2)}</td>
                                <td class="py-2">$${(item.price * item.quantity).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        // 3. Fungsi Edit Modal
        function openEditModal(orderId) {
            const order = ordersData.find(o => o.id == orderId);
            if (!order) return;
            document.getElementById('editOrderId').value = order.id;
            document.getElementById('editOrderStatus').value = order.status;
            document.getElementById('editOrderPayment').value = order.payment_method;
            document.getElementById('editOrderAddress').value = order.shipping_address;
            document.getElementById('editOrderTotal').value = order.total_amount;
            document.getElementById('editOrderShipping').value = order.shipping_cost;
            document.getElementById('editOrderTracking').value = order.tracking_number;
            document.getElementById('editOrderModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editOrderModal').classList.add('hidden');
        }

        // Delete Order (AJAX)
        function deleteOrder(orderId) {
            if (!confirm('Delete order?')) return;
            fetch('orders.php?delete=' + orderId, { method: 'GET' })
                .then(res => {
                    if (res.ok) {
                        // Remove row from table
                        document.querySelector('tr[data-order-id="' + orderId + '"]').remove();
                        // Optionally, remove from ordersData
                        const idx = ordersData.findIndex(o => o.id == orderId);
                        if (idx !== -1) ordersData.splice(idx, 1);
                    }
                });
        }

        // Submit Edit (AJAX)
        document.getElementById('editOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('update_order', '1');
            fetch('orders.php', {
                method: 'POST',
                body: formData
            }).then(res => {
                if (res.ok) {
                    closeEditModal();
                    location.reload(); // Atau update tabel tanpa reload
                }
            });
        });

        function updateStats(filteredOrders) {
            // Total Orders
            document.getElementById('totalOrders').textContent = filteredOrders.length;

            // Pending Orders
            const pending = filteredOrders.filter(o => o.status === 'pending').length;
            document.getElementById('pendingOrders').textContent = pending;

            // Completed Orders
            const completed = filteredOrders.filter(o => o.status === 'completed').length;
            document.getElementById('completedOrders').textContent = completed;

            // Total Revenue
            const revenue = filteredOrders.reduce((sum, o) => sum + parseFloat(o.total_amount), 0);
            document.getElementById('totalRevenue').textContent = '$' + revenue.toFixed(2);
        }

        function filterOrders() {
            const status = document.getElementById('statusFilter').value;
            const payment = document.getElementById('paymentFilter').value;
            const tbody = document.getElementById('ordersTableBody');
            tbody.innerHTML = '';

            // Filter orders
            const filteredOrders = ordersData.filter(order => {
                if (status && order.status !== status) return false;
                if (payment && order.payment_method !== payment) return false;
                return true;
            });

            filteredOrders.forEach(order => {
                tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors" data-order-id="${order.id}">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="font-mono text-sm font-medium">#${order.id}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-primary text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${order.customer_name}</p>
                                <p class="text-sm text-gray-500">User ID: ${order.user_id}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div>
                            <p class="text-sm font-medium text-gray-900">${new Date(order.order_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</p>
                            <p class="text-xs text-gray-500">${new Date(order.order_date).toLocaleTimeString('en-US', { hour12: false })}</p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            ${order.payment_method === 'cod' ? '<i class="fas fa-truck text-gray-400 mr-2"></i>' : order.payment_method === 'ewallet' ? '<i class="fas fa-mobile-alt text-gray-400 mr-2"></i>' : '<i class="fas fa-credit-card text-gray-400 mr-2"></i>'}
                            <span class="text-sm text-gray-700">${order.payment_method.toUpperCase()}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div>
                            <p class="font-semibold text-gray-900">$${parseFloat(order.total_amount).toFixed(2)}</p>
                            <p class="text-xs text-gray-500">${order.shipping_cost > 0 ? '+$' + parseFloat(order.shipping_cost).toFixed(2) + ' shipping' : 'Free shipping'}</p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center space-x-2">
                            <button class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition-colors btn-edit" data-id="${order.id}">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition-colors btn-delete" data-id="${order.id}">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                            <button class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-blue-700 transition-colors btn-view" data-id="${order.id}">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                        </div>
                    </td>
                </tr>
                `;
            });

            // Re-attach event listeners for new buttons
            tbody.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    openEditModal(btn.dataset.id);
                });
            });
            tbody.querySelectorAll('.btn-view').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewOrder(btn.dataset.id);
                });
            });
            tbody.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    deleteOrder(btn.dataset.id);
                });
            });

            // Update stats
            updateStats(filteredOrders);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    openEditModal(btn.dataset.id);
                });
            });
            document.querySelectorAll('.btn-view').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewOrder(btn.dataset.id);
                });
            });
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    deleteOrder(btn.dataset.id);
                });
            });
        });

        // Event listeners for filter
        document.getElementById('statusFilter').addEventListener('change', filterOrders);
        document.getElementById('paymentFilter').addEventListener('change', filterOrders);
    </script>
</body>
</html>

<?php
// Setelah update status order
$updateStatus = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
$updateStatus->bind_param('si', $newStatus, $orderId);
if ($updateStatus->execute()) {
    // Ambil user_id dari order
    $getUser = $conn->prepare("SELECT user_id FROM orders WHERE id=?");
    $getUser->bind_param('i', $orderId);
    $getUser->execute();
    $userResult = $getUser->get_result();
    $userRow = $userResult->fetch_assoc();
    $userId = $userRow['user_id'];

    // Buat pesan notifikasi sesuai status
    $notifMsg = '';
    $notifType = '';
    switch ($newStatus) {
        case 'pending':
            $notifMsg = "Pesanan Anda sedang menunggu konfirmasi admin.";
            $notifType = 'pending';
            break;
        case 'paid':
            $notifMsg = "Pembayaran pesanan Anda telah diterima.";
            $notifType = 'paid';
            break;
        case 'shipped':
            $notifMsg = "Pesanan Anda telah dikirim. Silakan cek resi pengiriman.";
            $notifType = 'shipped';
            break;
        case 'delivered':
            $notifMsg = "Pesanan Anda telah diterima. Terima kasih telah berbelanja!";
            $notifType = 'delivered';
            break;
        case 'completed':
            $notifMsg = "Pesanan Anda telah selesai. Silakan beri ulasan!";
            $notifType = 'completed';
            break;
        case 'cancelled':
            $notifMsg = "Pesanan Anda telah dibatalkan oleh admin.";
            $notifType = 'cancelled';
            break;
        default:
            $notifMsg = "Status pesanan Anda telah diperbarui.";
            $notifType = 'info';
    }

    // Insert ke tabel notifications
    $insertNotif = $conn->prepare("INSERT INTO notifications (user_id, message, type, is_read) VALUES (?, ?, ?, 0)");
    $insertNotif->bind_param('iss', $userId, $notifMsg, $notifType);
    $insertNotif->execute();
}
?>
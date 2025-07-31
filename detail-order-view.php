<?php
// filepath: c:\laragon\www\greennest\order-detail.php
session_start();
require_once __DIR__ . '/config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Helper functions
function canUserAccessOrder($conn, $order_id, $user_id) {
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function getOrderDetails($conn, $order_id, $user_id) {
    $stmt = $conn->prepare("
        SELECT o.*, u.name as user_name, u.phone as user_phone
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function generateTrackingNumber($order_id, $order_date) {
    $date = date('Ymd', strtotime($order_date));
    $time = date('His', strtotime($order_date));
    return "GN{$date}{$time}{$order_id}";
}

function updateTrackingNumber($conn, $order_id, $tracking_number) {
    $stmt = $conn->prepare("UPDATE orders SET tracking_number = ? WHERE id = ?");
    $stmt->bind_param("si", $tracking_number, $order_id);
    $stmt->execute();
}

function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.botanical_name, p.plant_size, pi.image_url as product_image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function calculateOrderTotals($orderItems) {
    $subtotal = 0;
    $total_items = 0;
    
    foreach ($orderItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $total_items += $item['quantity'];
    }
    
    return [
        'subtotal' => $subtotal,
        'total_items' => $total_items
    ];
}

function getStatusDisplay($status) {
    $statuses = [
        'pending' => ['text' => 'Order Pending', 'description' => 'Your order is being processed'],
        'paid' => ['text' => 'Payment Confirmed', 'description' => 'Payment received, preparing your order'],
        'shipped' => ['text' => 'Order Shipped', 'description' => 'Your order is on the way'],
        'delivered' => ['text' => 'Order Delivered', 'description' => 'Your order has been delivered'],
        'completed' => ['text' => 'Order Completed', 'description' => 'Thank you for your purchase!'],
        'cancelled' => ['text' => 'Order Cancelled', 'description' => 'This order has been cancelled']
    ];
    
    return $statuses[$status] ?? ['text' => 'Unknown Status', 'description' => ''];
}

function getOrderTimeline($order) {
    $timeline = [];
    $status = $order['status'];
    $order_date = $order['order_date'];
    
    // Always show order placed
    $timeline[] = [
        'title' => 'Order Placed',
        'date' => formatOrderDate($order_date),
        'description' => 'Your order has been received',
        'completed' => true
    ];
    
    if ($status === 'cancelled') {
        $timeline[] = [
            'title' => 'Order Cancelled',
            'date' => 'Cancelled',
            'description' => 'This order has been cancelled',
            'completed' => true,
            'is_cancelled' => true
        ];
        return $timeline;
    }
    
    // Payment confirmed
    $timeline[] = [
        'title' => 'Payment Confirmed',
        'date' => in_array($status, ['paid', 'shipped', 'delivered', 'completed']) ? 'Confirmed' : 'Pending',
        'description' => 'Payment has been processed',
        'completed' => in_array($status, ['paid', 'shipped', 'delivered', 'completed'])
    ];
    
    // Order shipped
    $timeline[] = [
        'title' => 'Order Shipped',
        'date' => in_array($status, ['shipped', 'delivered', 'completed']) ? 'Shipped' : 'Not yet shipped',
        'description' => 'Your order is on the way',
        'completed' => in_array($status, ['shipped', 'delivered', 'completed'])
    ];
    
    // Order delivered
    $timeline[] = [
        'title' => 'Order Delivered',
        'date' => in_array($status, ['delivered', 'completed']) ? 'Delivered' : 'Not yet delivered',
        'description' => 'Package delivered to your address',
        'completed' => in_array($status, ['delivered', 'completed'])
    ];
    
    return $timeline;
}

function formatOrderDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function getPaymentMethodName($method) {
    $methods = [
        'transfer' => 'Bank Transfer',
        'ewallet' => 'E-Wallet',
        'cod' => 'Cash on Delivery'
    ];
    return $methods[$method] ?? $method;
}

function canReorder($status) {
    return in_array($status, ['completed', 'cancelled']);
}

function hasTracking($status) {
    return in_array($status, ['shipped', 'delivered', 'completed']);
}

function canCancelOrder($status) {
    return in_array($status, ['pending', 'paid']);
}

// Validate order access
if (!$order_id || !canUserAccessOrder($conn, $order_id, $user_id)) {
    header('Location: profile.php?tab=orders&error=order_not_found');
    exit;
}

// Get order details
$order = getOrderDetails($conn, $order_id, $user_id);
if (!$order) {
    header('Location: profile.php?tab=orders&error=order_not_found');
    exit;
}

// Generate tracking number if not exists
if (empty($order['tracking_number'])) {
    $tracking = generateTrackingNumber($order['id'], $order['order_date']);
    updateTrackingNumber($conn, $order['id'], $tracking);
    $order['tracking_number'] = $tracking;
}

// Get order items
$orderItems = getOrderItems($conn, $order_id);

// Calculate totals
$totals = calculateOrderTotals($orderItems);

// Get status info
$statusInfo = getStatusDisplay($order['status']);
$timeline = getOrderTimeline($order);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail #<?php echo htmlspecialchars($order['tracking_number']); ?> - GreenNest</title>
     <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#22c55e',
                        secondary: '#dcfce7'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50 min-h-screen">
    <!-- Header -->
   <?php include './component/navbar.php'; ?>
   
    <div class="container mx-auto px-4  sm:px-6 lg:px-16 py-24">
        <!-- Order Status Banner -->
        <div class="<?php 
            echo $order['status'] === 'completed' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
                ($order['status'] === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-red-600' :
                ($order['status'] === 'shipped' ? 'bg-gradient-to-r from-orange-500 to-orange-600' :
                ($order['status'] === 'delivered' ? 'bg-gradient-to-r from-blue-500 to-blue-600' :
                'bg-gradient-to-r from-yellow-500 to-yellow-600'))); 
        ?> rounded-2xl p-6 text-white mb-8 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <?php if ($order['status'] === 'completed'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                            <?php elseif ($order['status'] === 'cancelled'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                            <?php elseif ($order['status'] === 'shipped'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <span class="text-lg font-semibold"><?php echo $statusInfo['text']; ?></span>
                    </div>
                    <p class="text-white/90"><?php echo $statusInfo['description']; ?></p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">Order Date</p>
                    <p class="text-lg font-medium"><?php echo formatOrderDate($order['order_date']); ?></p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Timeline -->
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-primary/20 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Timeline</h2>
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 <?php echo $order['status'] === 'cancelled' ? 'bg-red-300' : 'bg-gray-300'; ?>"></div>
                        
                        <?php foreach ($timeline as $index => $step): ?>
                        <div class="relative flex items-start <?php echo $index < count($timeline) - 1 ? 'mb-6' : ''; ?>">
                            <div class="w-8 h-8 <?php 
                                if (isset($step['is_cancelled']) && $step['is_cancelled']) {
                                    echo 'bg-red-500';
                                } elseif ($step['completed']) {
                                    echo 'bg-primary';
                                } else {
                                    echo 'bg-gray-300';
                                }
                            ?> rounded-full flex items-center justify-center mr-4 z-10">
                                <?php if (isset($step['is_cancelled']) && $step['is_cancelled']): ?>
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium <?php echo (isset($step['is_cancelled']) && $step['is_cancelled']) ? 'text-red-700' : 'text-gray-900'; ?>">
                                    <?php echo $step['title']; ?>
                                </h3>
                                <p class="text-sm <?php echo (isset($step['is_cancelled']) && $step['is_cancelled']) ? 'text-red-600' : 'text-gray-600'; ?>">
                                    <?php echo $step['date']; ?>
                                </p>
                                <p class="text-sm text-gray-500 mt-1"><?php echo $step['description']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Items Ordered -->
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-primary/20 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Items Ordered (<?php echo $totals['total_items']; ?> items)</h2>
                    <div class="space-y-4">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="flex items-center p-4   bg-secondary/30 rounded-xl border border-primary/10">
                                <div class="w-20 h-20 bg-white rounded-xl mr-4 border-2 border-primary/20 overflow-hidden">
                                    <?php if (!empty($item['product_image']) && file_exists($item['product_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['product_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                    <?php if (!empty($item['botanical_name'])): ?>
                                        <p class="text-sm text-gray-600 mb-1"><?php echo htmlspecialchars($item['botanical_name']); ?></p>
                                    <?php endif; ?>
                                    <p class="text-sm text-white-500">
                                        <?php if (!empty($item['plant_size'])): ?>
                                            Size: <?php echo htmlspecialchars($item['plant_size']); ?> • 
                                        <?php endif; ?>
                                        Quantity: <?php echo $item['quantity']; ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900"><?php echo formatRupiah($item['price']); ?></p>
                                    <p class="text-sm text-gray-600">each</p>
                                    <?php if ($item['quantity'] > 1): ?>
                                        <p class="text-xs text-gray-500 mt-1">Total: <?php echo formatRupiah($item['price'] * $item['quantity']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-primary/20 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Delivery Address</h3>
                            <div class="p-4 bg-secondary/20 rounded-xl border border-primary/10">
                                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars($order['user_name']); ?></p>
                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                <?php if (!empty($order['user_phone'])): ?>
                                    <p class="text-gray-600 text-sm">Phone: <?php echo htmlspecialchars($order['user_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Shipping Method</h3>
                            <div class="p-4 bg-secondary/20 rounded-xl border border-primary/10">
                                <p class="text-gray-900 font-medium">
                                    <?php echo $order['shipping_cost'] > 0 ? 'Standard Delivery' : 'Free Delivery'; ?>
                                </p>
                                <p class="text-gray-600 text-sm mt-1">Estimated 2-3 business days</p>
                                <p class="text-gray-600 text-sm">Tracking: <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-primary/20 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal (<?php echo $totals['total_items']; ?> items)</span>
                            <span class="text-gray-900"><?php echo formatRupiah($totals['subtotal']); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="<?php echo $order['shipping_cost'] > 0 ? 'text-gray-900' : 'text-green-600'; ?>">
                                <?php echo $order['shipping_cost'] > 0 ? formatRupiah($order['shipping_cost']) : 'Free'; ?>
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-gray-900">Rp 0</span>
                        </div>
                        <div class="border-t border-primary/20 pt-3">
                            <div class="flex justify-between font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-primary text-lg"><?php echo formatRupiah($order['total_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-primary/20 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-secondary/20 rounded-xl border border-primary/10">
                            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo getPaymentMethodName($order['payment_method']); ?></p>
                                <p class="text-sm text-gray-600">
                                    <?php echo $order['status'] === 'pending' ? 'Payment Pending' : 'Payment Confirmed'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            <p>Order ID: <?php echo $order['id']; ?></p>
                            <p>Order Date: <?php echo formatOrderDate($order['order_date']); ?></p>
                            <?php if (!empty($order['bukti_pembayaran'])): ?>
                                <p>Payment Proof: Uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <?php if (canReorder($order['status'])): ?>
                        <button onclick="reorderItems()" class="w-full px-4 py-3 bg-primary text-white rounded-xl hover:bg-primary/90 transition-colors font-medium">
                            Reorder Items
                        </button>
                    <?php endif; ?>
                    
                    <?php if (hasTracking($order['status'])): ?>
                        <button onclick="trackOrder()" class="w-full px-4 py-3 border-2 border-primary text-primary rounded-xl hover:bg-primary/5 transition-colors font-medium">
                            Track Package
                        </button>
                    <?php endif; ?>
                    
                    <?php if (in_array($order['status'], ['shipped', 'delivered', 'completed'])): ?>
                        <button onclick="downloadInvoice()" class="w-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                            Download Invoice
                        </button>
                    <?php endif; ?>
                    
                    <?php if (canCancelOrder($order['status'])): ?>
                        <button onclick="cancelOrder()" class="w-full px-4 py-3 border-2 border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                            Cancel Order
                        </button>
                    <?php endif; ?>
                    
                    <button onclick="contactSupport()" class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                        Contact Support
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Action Successful!</h3>
                <p class="text-gray-600 mb-4" id="modalMessage">Your request has been processed successfully.</p>
                <button onclick="closeModal()" class="w-full px-4 py-2 bg-primary text-white rounded-xl hover:bg-primary/90 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Action</h3>
                <p class="text-gray-600 mb-4" id="confirmMessage">Are you sure you want to proceed?</p>
                <div class="flex space-x-3">
                    <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button id="confirmButton" onclick="confirmAction()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let confirmCallback = null;

        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('successModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        function showConfirmModal(message, callback) {
            document.getElementById('confirmMessage').textContent = message;
            confirmCallback = callback;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmCallback = null;
        }

        function confirmAction() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        }

        function reorderItems() {
            const orderItems = <?php echo json_encode($orderItems); ?>;
            
            // Create form data for reorder
            const formData = new FormData();
            formData.append('action', 'reorder');
            formData.append('order_id', <?php echo $order['id']; ?>);
            
            orderItems.forEach((item, index) => {
                formData.append(`items[${index}][product_id]`, item.product_id);
                formData.append(`items[${index}][quantity]`, item.quantity);
            });

            // In a real application, send this to cart.php or reorder endpoint
            showModal('Items added to cart! Redirecting to checkout...');
            setTimeout(() => {
                window.location.href = 'cart.php';
            }, 2000);
        }

        function trackOrder() {
            const trackingNumber = '<?php echo htmlspecialchars($order['tracking_number']); ?>';
            showModal(`Tracking Number: ${trackingNumber}\n\nYou can track your package with this number.`);
        }

        function downloadInvoice() {
            // Generate invoice data
            const invoiceData = {
                order_id: <?php echo $order['id']; ?>,
                tracking_number: '<?php echo htmlspecialchars($order['tracking_number']); ?>',
                order_date: '<?php echo $order['order_date']; ?>',
                total_amount: <?php echo $order['total_amount']; ?>
            };
            
            // In a real application, this would send request to generate PDF
            showModal('Invoice download started! Check your downloads folder.');
            
            // Simulate PDF generation
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = `generate_invoice.php?order_id=${invoiceData.order_id}`;
                link.download = `invoice-${invoiceData.tracking_number}.pdf`;
                link.click();
            }, 1000);
        }

        function cancelOrder() {
            showConfirmModal(
                'Are you sure you want to cancel this order? This action cannot be undone.',
                () => {
                    // Send cancel request
                    const formData = new FormData();
                    formData.append('action', 'cancel_order');
                    formData.append('order_id', <?php echo $order['id']; ?>);
                    
                    fetch('process_order.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showModal('Order cancelled successfully. You will be redirected.');
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showModal('Failed to cancel order. Please try again.');
                        }
                    })
                    .catch(error => {
                        showModal('An error occurred. Please try again.');
                    });
                }
            );
        }

        function contactSupport() {
            const orderInfo = {
                order_id: <?php echo $order['id']; ?>,
                tracking_number: '<?php echo htmlspecialchars($order['tracking_number']); ?>',
                status: '<?php echo $order['status']; ?>'
            };
            
            showModal('Redirecting to support chat...');
            setTimeout(() => {
                window.location.href = `contact.php?order=${orderInfo.order_id}&tracking=${orderInfo.tracking_number}`;
            }, 2000);
        }

        // Close modals when clicking outside
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // ESC key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeConfirmModal();
            }
        });

        // Auto-refresh for pending/shipped orders
        <?php if (in_array($order['status'], ['pending', 'shipped'])): ?>
        setInterval(() => {
            // Check for status updates every 30 seconds
            fetch(`check_order_status.php?order_id=<?php echo $order['id']; ?>`)
                .then(response => response.json())
                .then(data => {
                    if (data.status !== '<?php echo $order['status']; ?>') {
                        // Status has changed, reload page
                        location.reload();
                    }
                })
                .catch(error => {
                    console.log('Status check failed:', error);
                });
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>
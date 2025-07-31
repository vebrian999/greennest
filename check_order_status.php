<?php
// filepath: c:\laragon\www\greennest\check_order_status.php
session_start();
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id'] ?? 0);

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit;
}

// Get current order status
$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

$order = $result->fetch_assoc();
echo json_encode(['success' => true, 'status' => $order['status']]);
?>
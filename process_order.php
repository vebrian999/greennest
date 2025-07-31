<?php
// filepath: c:\laragon\www\greennest\process_order.php
session_start();
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'cancel_order':
        $order_id = intval($_POST['order_id'] ?? 0);
        
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            exit;
        }
        
        // Verify order belongs to user and can be cancelled
        $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            exit;
        }
        
        $order = $result->fetch_assoc();
        
        // Check if order can be cancelled
        if (!in_array($order['status'], ['pending', 'paid'])) {
            echo json_encode(['success' => false, 'message' => 'Order cannot be cancelled']);
            exit;
        }
        
        // Update order status to cancelled
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        
        if ($stmt->execute()) {
            // Return stock to products
            $stmt = $conn->prepare("
                UPDATE products p 
                JOIN order_items oi ON p.id = oi.product_id 
                SET p.stock = p.stock + oi.quantity 
                WHERE oi.order_id = ?
            ");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            
            // Add notification
            $stmt = $conn->prepare("
                INSERT INTO notifications (user_id, type, message, is_read, created_at) 
                VALUES (?, 'order_cancelled', ?, 0, NOW())
            ");
            $message = "Order #{$order_id} has been cancelled successfully.";
            $stmt->bind_param("is", $user_id, $message);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
        }
        break;
        
    case 'update_status':
        // Only for admin
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $new_status = $_POST['status'] ?? '';
        
        if (!$order_id || !in_array($new_status, ['pending', 'paid', 'shipped', 'delivered', 'completed', 'cancelled'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }
        
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        
        if ($stmt->execute()) {
            // Get user_id for notification
            $stmt = $conn->prepare("SELECT user_id FROM orders WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $order_user_id = $stmt->get_result()->fetch_assoc()['user_id'];
            
            // Add notification
            $status_messages = [
                'paid' => 'Your payment has been confirmed!',
                'shipped' => 'Your order has been shipped!',
                'delivered' => 'Your order has been delivered!',
                'completed' => 'Your order is completed. Thank you!',
                'cancelled' => 'Your order has been cancelled.'
            ];
            
            if (isset($status_messages[$new_status])) {
                $stmt = $conn->prepare("
                    INSERT INTO notifications (user_id, type, message, is_read, created_at) 
                    VALUES (?, 'order_status_update', ?, 0, NOW())
                ");
                $message = $status_messages[$new_status] . " Order #{$order_id}";
                $stmt->bind_param("is", $order_user_id, $message);
                $stmt->execute();
            }
            
            echo json_encode(['success' => true, 'message' => 'Order status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
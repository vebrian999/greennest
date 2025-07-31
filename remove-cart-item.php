<?php
require_once 'config/db.php';
session_start();

$userId = $_SESSION['user_id'] ?? 0;
$cartItemId = intval($_POST['cart_item_id'] ?? 0);

if (!$userId) {
    // Redirect jika user tidak login
    header('Location: login.php');
    exit;
}

if (!$cartItemId) {
    // Redirect kembali jika cart item ID tidak valid
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

try {
    // Pastikan cart item belongs to user yang sedang login
    $checkStmt = $conn->prepare("
        SELECT ci.id 
        FROM cart_items ci 
        JOIN cart c ON ci.cart_id = c.id 
        WHERE ci.id = ? AND c.user_id = ?
    ");
    $checkStmt->bind_param('ii', $cartItemId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // Hapus item jika belongs to user
        $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
        $deleteStmt->bind_param('i', $cartItemId);
        $deleteStmt->execute();
    }
    
} catch (Exception $e) {
    // Log error atau handle sesuai kebutuhan
    error_log("Error removing cart item: " . $e->getMessage());
}

// Redirect kembali ke halaman sebelumnya
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>
<?php
require_once 'config/db.php';
session_start();
$userId = $_SESSION['user_id'] ?? 0;
$productId = intval($_POST['product_id'] ?? 0);

if (!$userId || !$productId) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Cek apakah sudah like
$stmt = $conn->prepare("SELECT id FROM product_likes WHERE product_id = ? AND user_id = ?");
$stmt->bind_param('ii', $productId, $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Sudah like, maka unlike (hapus)
    $delStmt = $conn->prepare("DELETE FROM product_likes WHERE product_id = ? AND user_id = ?");
    $delStmt->bind_param('ii', $productId, $userId);
    $delStmt->execute();
    $liked = false;
} else {
    // Belum like, maka insert
    $insStmt = $conn->prepare("INSERT INTO product_likes (product_id, user_id) VALUES (?, ?)");
    $insStmt->bind_param('ii', $productId, $userId);
    $insStmt->execute();
    $liked = true;
}

// Hitung total like
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM product_likes WHERE product_id = ?");
$countStmt->bind_param('i', $productId);
$countStmt->execute();
$countRes = $countStmt->get_result();
$total = $countRes->fetch_assoc()['total'] ?? 0;

echo json_encode(['success' => true, 'liked' => $liked, 'total' => $total]);
?>
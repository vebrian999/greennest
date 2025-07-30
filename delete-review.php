<?php
require_once 'config/db.php';
session_start();

$userId = $_SESSION['user_id'] ?? 0;
$reviewId = intval($_POST['review_id'] ?? 0);

// Ambil review untuk cek owner
$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param('i', $reviewId);
$stmt->execute();
$res = $stmt->get_result();
$review = $res->fetch_assoc();

if (!$review || $review['user_id'] != $userId) {
    echo "Unauthorized.";
    exit;
}

$productId = $review['product_id'];

// Hapus review
$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $reviewId, $userId);
if ($stmt->execute()) {
    header("Location: detail-product.php?id=" . $productId . "&delete=success");
    exit;
} else {
    echo "Gagal hapus review.";
}
?>
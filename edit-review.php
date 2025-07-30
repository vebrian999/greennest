<?php
require_once 'config/db.php';
session_start();

$userId = $_SESSION['user_id'] ?? 0;
$reviewId = intval($_POST['review_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$image_url = null;

// Ambil review lama untuk cek owner dan gambar lama
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

// Proses upload gambar jika ada
if (!empty($_FILES['image_url']['name'])) {
    $targetDir = "uploads/review/";
    $fileName = 'review_' . time() . '_' . basename($_FILES["image_url"]["name"]);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFile)) {
        $image_url = $targetFile;
    }
} else {
    $image_url = $review['image_url'];
}

// Validasi sederhana
if ($reviewId && $userId && $rating && $comment) {
    $sql = "UPDATE reviews SET rating = ?, comment = ?, image_url = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issii', $rating, $comment, $image_url, $reviewId, $userId);
    if ($stmt->execute()) {
        $productId = $review['product_id']; // Ambil product_id dari review lama
        header('Location: detail-product.php?id=' . $productId . '#reviews');
        exit;
    } else {
        echo "Gagal update review.";
    }
} else {
    echo "Data tidak lengkap.";
}
?>
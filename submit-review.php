<?php
require_once __DIR__ . '/config/db.php';

// Pastikan user sudah login, misal dari session
session_start();
$userId = $_SESSION['user_id'] ?? 1; // Ganti dengan sistem login Anda

$productId = intval($_POST['product_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$image_url = null;

// Proses upload gambar jika ada
if (!empty($_FILES['image_url']['name'])) {
    $targetDir = "uploads/review/";
    $fileName = 'review_' . time() . '_' . basename($_FILES["image_url"]["name"]);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFile)) {
        $image_url = $targetFile;
    }
}

// Validasi sederhana
if ($productId && $userId && $rating && $comment) {
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, image_url, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiss', $productId, $userId, $rating, $comment, $image_url);
    if ($stmt->execute()) {
        // Redirect kembali ke halaman produk
        header('Location: detail-product.php?id=' . $productId . '#reviews');
        exit;
    } else {
        echo "Gagal menyimpan review.";
    }
} else {
    echo "Data tidak lengkap.";
}
?>
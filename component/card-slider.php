<?php
require_once __DIR__ . '/../config/db.php';

// Ambil 5 produk random selain produk yang sedang dibuka
$currentProductId = isset($productId) ? intval($productId) : 0;
$sql = "SELECT p.*, pi.image_url 
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        WHERE p.id != ?
        ORDER BY RAND()
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $currentProductId);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="flex space-x-6 min-w-max pb-4">
  <?php while ($cardProduct = $result->fetch_assoc()): ?>
    <?php include __DIR__ . '/card.php'; ?>
  <?php endwhile; ?>
</div>

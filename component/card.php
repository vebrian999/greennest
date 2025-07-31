<?php
if (!isset($cardProduct)) return;
?>
<a href="./detail-product.php?id=<?php echo $cardProduct['id']; ?>" class="w-[369px] flex-shrink-0">
  <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow h-[544px]">
    <div class="relative">
      <!-- Image carousel (hanya gambar utama) -->
      <div class="carousel-container h-[400px] relative">
        <?php
          $badges = [];
          // Urutan prioritas badge
          if (!empty($cardProduct['is_best_seller']) || ($cardProduct['product_label'] ?? '') === 'BEST SELLER') {
              $badges[] = 'BEST SELLER';
          }
          if (($cardProduct['stock'] ?? 0) <= 0) {
              $badges[] = 'OUT OF STOCK';
          }
          if (($cardProduct['stock'] ?? 0) > 0 && ($cardProduct['stock'] ?? 0) < 50) {
              $badges[] = 'LIMITED STOCK';
          }
          if (!empty($cardProduct['created_at']) && strtotime($cardProduct['created_at']) >= strtotime('-30 days')) {
              $badges[] = 'NEW ARRIVAL';
          }
          if (($cardProduct['review_count'] ?? 0) >= 10 || ($cardProduct['like_count'] ?? 0) >= 10) {
              $badges[] = 'POPULAR';
          }
          // Maksimal 2 badge saja
          $badges = array_slice($badges, 0, 2);
          foreach ($badges as $i => $badge) {
              $top = 16 + ($i * 36);
              echo '<div style="position:absolute;top:' . $top . 'px;left:16px;z-index:10;" class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium mb-1">' . htmlspecialchars($badge) . '</div>';
          }
        ?>
        <div class="carousel-slides h-full">
          <img src="<?php echo !empty($cardProduct['image_url']) ? htmlspecialchars($cardProduct['image_url']) : './src/img/product_default.png'; ?>" alt="Plant view" class="w-full h-full object-cover absolute transition-opacity duration-500 ease-in-out" />
        </div>
      </div>
    </div>
    <div class="p-6">
      <div class="flex justify-between items-start mb-4">
        <div>
          <h3 class="text-xl text-primary"><?php echo htmlspecialchars($cardProduct['name']); ?></h3>
          <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($cardProduct['botanical_name'] ?? 'Easy care indoor plant'); ?></p>
        </div>
        <p class="text-primary font-medium text-lg">$<?php echo number_format($cardProduct['price'], 2); ?></p>
      </div>
      <!-- Rating stars -->
      <div class="flex items-center space-x-1">
        <?php
          // Ambil rating rata-rata dari tabel reviews
          require_once __DIR__ . '/../config/db.php';
          $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
          $stmt->bind_param('i', $cardProduct['id']);
          $stmt->execute();
          $res = $stmt->get_result();
          $avgRating = round($res->fetch_assoc()['avg_rating'] ?? 0, 1);
          $fullStars = floor($avgRating);
          $emptyStars = 5 - $fullStars;
        ?>
        <?php for ($i = 0; $i < $fullStars; $i++): ?>
          <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        <?php endfor; ?>
        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
          <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        <?php endfor; ?>
        <span class="text-sm text-gray-500 ml-2">(<?php echo number_format($avgRating, 1); ?>)</span>
      </div>
    </div>
  </div>
</a>
<?php
require_once __DIR__ . '/../config/db.php';
$userId = $_SESSION['user_id'] ?? 0;

// Ambil produk favorit user
$favQuery = $conn->prepare("
  SELECT p.*, pi.image_url
  FROM product_likes pl
  JOIN products p ON pl.product_id = p.id
  LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
  WHERE pl.user_id = ?
  ORDER BY pl.created_at DESC
");
$favQuery->bind_param('i', $userId);
$favQuery->execute();
$favResult = $favQuery->get_result();

$favorites = [];
while ($row = $favResult->fetch_assoc()) {
  $favorites[] = $row;
}
?>

<!-- Wishlist Content -->
<div id="content-wishlist" class="content-section <?php echo $activeTab !== 'wishlist' ? 'hidden' : ''; ?>">
  <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-pink-600 mb-0">
        <svg class="w-7 h-7 inline mr-3 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
        </svg>
        Wishlist / Favorite
      </h2>
      <span class="px-3 py-1 bg-pink-100 text-pink-600 rounded-full text-sm font-medium">
        <?php echo count($favorites); ?> items
      </span>
    </div>

    <p class="mb-6 text-gray-600">Produk favorit Anda yang tersimpan untuk dibeli nanti.</p>

    <?php if (count($favorites) > 0): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($favorites as $fav): ?>
          <?php
            $cardProduct = $fav;
            if (!isset($cardProduct['botanical_name'])) {
              $cardProduct['botanical_name'] = 'Easy care indoor plant';
            }
          ?>
          <div class="relative group">
            <?php include __DIR__ . '/card-main.php'; ?>
            <!-- Tombol hapus dari favorite -->
            <form method="post" action="" class="absolute top-4 right-4 z-20">
              <input type="hidden" name="product_id" value="<?= $fav['id'] ?>">
              <button type="submit" class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-pink-100 transition-colors" title="Hapus dari Favorite">
                <svg class="w-5 h-5 text-pink-500 group-hover:text-pink-700 transition-colors" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-12">
        <svg class="w-20 h-20 mx-auto text-pink-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Wishlist Kosong</h3>
        <p class="text-gray-600 mb-4">Belum ada produk favorit. Mulai jelajahi tanaman dan tambahkan ke wishlist Anda!</p>
        <button class="px-6 py-3 bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition-colors">Jelajahi Produk</button>
      </div>
    <?php endif; ?>
  </div>
</div>
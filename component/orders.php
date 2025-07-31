<?php
// Tambahkan kode ini setelah bagian ambil notifikasi

// Handle filter orders
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Query orders dengan filter
$ordersQuery = "
    SELECT o.*, 
           COUNT(oi.id) as total_items,
           GROUP_CONCAT(p.name SEPARATOR ', ') as product_names,
           GROUP_CONCAT(pi.image_url SEPARATOR ', ') as product_images
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    LEFT JOIN products p ON oi.product_id = p.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    WHERE o.user_id = ?";

if ($statusFilter !== 'all') {
    // Mapping untuk UI
    $statusMapping = [
        'pending' => 'pending',
        'delivered' => 'shipped', // atau 'delivered' jika sudah diubah enum
        'completed' => 'completed', 
        'cancelled' => 'cancelled'
    ];
    
    if (isset($statusMapping[$statusFilter])) {
        $ordersQuery .= " AND o.status = '" . $statusMapping[$statusFilter] . "'";
    }
}

$ordersQuery .= " GROUP BY o.id ORDER BY o.order_date DESC";

$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->bind_param('i', $userId);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();

$orders = [];
while ($row = $ordersResult->fetch_assoc()) {
    $orders[] = $row;
}

// Function untuk format status
function getStatusDisplay($status) {
    $statusMap = [
        'pending' => ['text' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
        'paid' => ['text' => 'Paid', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
        'shipped' => ['text' => 'Delivered', 'class' => 'bg-green-100 text-green-800 border-green-300'],
        'delivered' => ['text' => 'Delivered', 'class' => 'bg-green-100 text-green-800 border-green-300'],
        'completed' => ['text' => 'Completed', 'class' => 'bg-secondary/20 text-secondary border-secondary/30'],
        'cancelled' => ['text' => 'Cancelled', 'class' => 'bg-red-100 text-red-800 border-red-300']
    ];
    
    return $statusMap[$status] ?? ['text' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'];
}

// Function untuk format tanggal
function formatOrderDate($date) {
    return date('F j, Y', strtotime($date));
}

// Function untuk format rupiah
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>          
       <!-- Orders Content -->
<div id="content-orders" class="content-section <?php echo $activeTab !== 'orders' ? 'hidden' : ''; ?>">
  <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
    <h2 class="text-2xl font-bold text-primary mb-6">My Orders</h2>
    
    <!-- Filter Tabs -->
    <div class="flex flex-wrap space-x-1 mb-6 bg-white/70 p-1 rounded-2xl border border-primary/10">
      <a href="?status=all#orders" 
         class="px-4 py-2 rounded-xl font-medium transition-all duration-300 <?php echo $statusFilter === 'all' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-secondary/10'; ?>">
        All Orders
      </a>
      <a href="pengaturan.php?tab=orders&status=pending" 
         class="px-4 py-2 rounded-xl font-medium transition-all duration-300 <?php echo $statusFilter === 'pending' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-secondary/10'; ?>">
        Pending
      </a>
      <a href="pengaturan.php?tab=orders&status=delivered" 
         class="px-4 py-2 rounded-xl font-medium transition-all duration-300 <?php echo $statusFilter === 'delivered' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-secondary/10'; ?>">
        Delivered
      </a>
      <a href="pengaturan.php?tab=orders&status=completed" 
         class="px-4 py-2 rounded-xl font-medium transition-all duration-300 <?php echo $statusFilter === 'completed' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-secondary/10'; ?>">
        Completed
      </a>
      <a href="pengaturan.php?tab=orders&status=cancelled" 
         class="px-4 py-2 rounded-xl font-medium transition-all duration-300 <?php echo $statusFilter === 'cancelled' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-secondary/10'; ?>">
        Cancelled
      </a>
    </div>
    
    <!-- Orders List -->
    <div class="space-y-6">
      <?php if (empty($orders)): ?>
        <div class="text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-600 mb-2">No Orders Found</h3>
          <p class="text-gray-500">You haven't placed any orders yet.</p>
        </div>
      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <?php 
            $statusInfo = getStatusDisplay($order['status']);
            $productImages = array_filter(explode(', ', $order['product_images'] ?? ''));
            $productNames = explode(', ', $order['product_names'] ?? '');
          ?>
          <div class="border border-primary/20 rounded-2xl p-6 bg-white/70 shadow-sm">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="font-semibold text-gray-900">Order #<?php echo $order['tracking_number'] ?? $order['id']; ?></h3>
                <p class="text-sm text-gray-600">Placed on <?php echo formatOrderDate($order['order_date']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?php echo $order['total_items']; ?> item(s)</p>
              </div>
              <span class="px-3 py-1 <?php echo $statusInfo['class']; ?> rounded-full text-sm font-medium border">
                <?php echo $statusInfo['text']; ?>
              </span>
            </div>
            
            <!-- Products Preview -->
            <div class="space-y-3 mb-4">
              <?php 
                // Tampilkan maksimal 2 produk pertama
                $displayProducts = array_slice($productNames, 0, 2);
                foreach ($displayProducts as $index => $productName): 
                  $productImage = isset($productImages[$index]) ? $productImages[$index] : '';
              ?>
                <div class="flex items-center">
                  <div class="w-16 h-16 bg-secondary/20 rounded-xl mr-4 border-2 border-primary/20 overflow-hidden">
                    <?php if ($productImage && file_exists($productImage)): ?>
                      <img src="<?php echo htmlspecialchars($productImage); ?>" 
                           alt="<?php echo htmlspecialchars($productName); ?>" 
                           class="w-full h-full object-cover">
                    <?php else: ?>
                      <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="flex-1">
                    <p class="font-medium"><?php echo htmlspecialchars($productName); ?></p>
                    <p class="text-sm text-gray-600">Quantity: 1</p>
                  </div>
                </div>
              <?php endforeach; ?>
              
              <?php if (count($productNames) > 2): ?>
                <p class="text-sm text-gray-500 ml-20">
                  +<?php echo count($productNames) - 2; ?> more item(s)
                </p>
              <?php endif; ?>
            </div>
            
            <div class="flex justify-between items-center pt-4 border-t border-primary/20">
              <div>
                <p class="font-semibold text-primary">Total: <?php echo formatRupiah($order['total_amount']); ?></p>
                <?php if ($order['shipping_cost'] > 0): ?>
                  <p class="text-sm text-gray-600">Shipping: <?php echo formatRupiah($order['shipping_cost']); ?></p>
                <?php endif; ?>
              </div>
              <div class="space-x-3">
                <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                  <button class="px-4 py-2 border border-primary text-primary rounded-xl hover:bg-primary/5 transition-colors"
                          onclick="alert('Tracking: <?php echo $order['tracking_number'] ?? 'Not available'; ?>')">
                    Track Order
                  </button>
                <?php endif; ?>
                
                <?php if ($order['status'] === 'completed'): ?>
                  <a href="product-detail.php?id=<?php echo $order['id']; ?>" 
                     class="px-4 py-2 bg-primary text-white rounded-xl hover:bg-primary/90 transition-colors">
                    Reorder
                  </a>
                <?php endif; ?>
                
                <a href="detail-order-view.php?id=<?php echo $order['id']; ?>" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                  View Details
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>


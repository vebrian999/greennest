<?php
// Ambil data cart untuk user yang sedang login
$cartItems = [];
$cartSubtotal = 0;
$userId = $_SESSION['user_id'] ?? 0;

if ($userId) {
    try {
        // Ambil cart ID user
        $cartStmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ?");
        $cartStmt->bind_param('i', $userId);
        $cartStmt->execute();
        $cartRes = $cartStmt->get_result();
        $cartData = $cartRes->fetch_assoc();
        $cartId = $cartData['id'] ?? null;

        if ($cartId) {
            // Ambil items dari cart dengan join ke products dan product_images
            $itemStmt = $conn->prepare("
                SELECT 
                    ci.id as cart_item_id, 
                    ci.quantity, 
                    p.id as product_id, 
                    p.name, 
                    p.price,
                    p.stock,
                    (
                        SELECT image_url 
                        FROM product_images 
                        WHERE product_id = p.id 
                        ORDER BY is_main DESC, id ASC 
                        LIMIT 1
                    ) as image_url
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?
                ORDER BY ci.id DESC
            ");
            $itemStmt->bind_param('i', $cartId);
            $itemStmt->execute();
            $itemRes = $itemStmt->get_result();
            
            while ($row = $itemRes->fetch_assoc()) {
                $cartItems[] = $row;
                $cartSubtotal += $row['price'] * $row['quantity'];
            }
        }
    } catch (Exception $e) {
        // Handle error
        error_log("Error loading cart: " . $e->getMessage());
    }
}
?>

<!-- Cart Sidebar Overlay -->
<div id="cartSidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300 ease-in-out"></div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="fixed top-0 right-0 h-full w-full max-w-md bg-primary text-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-white border-opacity-20">
        <h2 class="text-xl font-medium text-white">Your Cart</h2>
        <button id="closeCartSidebar" class="text-white hover:text-gray-300 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Free Shipping Banner -->
    <div class="px-6 py-3">
        <div class="flex items-center text-sm text-green-300 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                <polyline points="20,6 9,17 4,12"></polyline>
            </svg>
            You've unlocked Free Shipping
        </div>
        <div class="w-full h-1 bg-white bg-opacity-20 rounded-full">
            <div class="h-full bg-green-400 rounded-full" style="width: 100%"></div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto px-6 py-2" id="cartItemsContainer">
        <?php if (empty($cartItems)): ?>
            <div class="text-center text-gray-200 py-10" id="emptyCartMessage">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 opacity-50">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="m1 1 4 4 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Your cart is empty.
            </div>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
            <div class="bg-white/10 rounded-lg p-4 mb-4 cart-item-shadow" data-cart-item-id="<?php echo $item['cart_item_id']; ?>">
                <div class="flex items-start space-x-4">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <img 
                            src="<?php echo htmlspecialchars($item['image_url'] ?? './src/img/main-img-product.png'); ?>" 
                            class="w-16 h-16 rounded-lg object-cover" 
                            alt="<?php echo htmlspecialchars($item['name']); ?>"
                            onerror="this.src='./src/img/main-img-product.png'"
                        >
                    </div>
                    <!-- Product Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-white font-medium text-base truncate pr-2">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            <span class="text-white font-semibold text-base ml-2 flex-shrink-0">
                                $<?php echo number_format($item['price'], 2); ?>
                            </span>
                        </div>
                        <p class="text-gray-300 text-sm mb-3">Qty: <?php echo $item['quantity']; ?></p>
                        
                        <!-- Quantity Controls & Remove Button -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-white font-medium min-w-[20px] text-center">
                                    <?php echo $item['quantity']; ?>
                                </span>
                            </div>
                            <form method="post" action="remove-cart-item.php" style="display:inline;" onsubmit="return confirmRemove()">
                                <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                <button type="submit" class="text-gray-200 text-xs uppercase tracking-wider hover:text-red-300 transition-colors">
                                    REMOVE
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-white border-opacity-20">
        <!-- Subtotal -->
        <div class="flex justify-between items-center mb-1">
            <span class="text-white text-base">Subtotal:</span>
            <span class="text-white font-semibold text-lg" id="cartSubtotalDisplay">
                $<?php echo number_format($cartSubtotal, 2); ?>
            </span>
        </div>
        <!-- Disclaimer -->
        <p class="text-gray-300 text-xs mb-4 leading-relaxed">
            Most items ship separately. Orders cannot be cancelled once placed.
        </p>
        <!-- Checkout Button -->
        <?php if (!empty($cartItems)): ?>
            <a href="checkout-form.php" class="w-full block bg-white text-primary py-3 rounded-lg font-semibold text-base hover:bg-gray-100 transition-colors text-center">
                CHECKOUT
            </a>
        <?php else: ?>
            <button disabled class="w-full block bg-gray-500 text-gray-300 py-3 rounded-lg font-semibold text-base cursor-not-allowed">
                CHECKOUT
            </button>
        <?php endif; ?>
    </div>
</div>

<script>
// Fungsi untuk konfirmasi hapus item
function confirmRemove() {
    return confirm('Are you sure you want to remove this item from your cart?');
}

// Update cart display setelah item ditambahkan
function updateCartDisplay() {
    // Reload cart content via AJAX (optional enhancement)
    // Untuk implementasi sederhana, bisa reload halaman
    location.reload();
}

document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener pada semua tombol REMOVE
    document.querySelectorAll('form[action="remove-cart-item.php"]').forEach(form => {
        form.addEventListener('submit', function() {
            localStorage.setItem('openCartSidebar', 'true');
        });
    });

    // Setelah reload, cek flag dan buka sidebar jika perlu
    if (localStorage.getItem('openCartSidebar') === 'true') {
        const cartSidebar = document.getElementById('cartSidebar');
        const cartSidebarOverlay = document.getElementById('cartSidebarOverlay');
        if (cartSidebar && cartSidebarOverlay) {
            cartSidebar.classList.remove('translate-x-full');
            cartSidebarOverlay.classList.remove('hidden');
        }
        localStorage.removeItem('openCartSidebar');
    }
});
</script>
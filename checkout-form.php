<?php
require_once 'config/db.php'; // pastikan file ini menginisialisasi $conn
session_start(); // jika menggunakan session untuk user

$userId = $_SESSION['user_id'] ?? 1; // Ganti dengan session user asli

function getCartItems($userId, $conn) {
    $cartItems = [];
    $cartStmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ?");
    $cartStmt->bind_param('i', $userId);
    $cartStmt->execute();
    $cartRes = $cartStmt->get_result();
    $cartId = $cartRes->fetch_assoc()['id'] ?? null;

    if ($cartId) {
        $itemStmt = $conn->prepare(
            "SELECT ci.*, p.name, p.price, p.stock, pi.image_url
             FROM cart_items ci
             JOIN products p ON ci.product_id = p.id
             LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
             WHERE ci.cart_id = ?"
        );
        $itemStmt->bind_param('i', $cartId);
        $itemStmt->execute();
        $itemRes = $itemStmt->get_result();
        while ($row = $itemRes->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }
    return $cartItems;
}

function saveOrder($userId, $address, $total, $shippingCost, $paymentMethod, $conn) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, status, total_amount, shipping_cost, payment_method, shipping_address, created_at) VALUES (?, NOW(), 'pending', ?, ?, ?, ?, NOW())");
    $stmt->bind_param('iddss', $userId, $total, $shippingCost, $paymentMethod, $address);
    $stmt->execute();
    return $conn->insert_id;
}

function saveOrderItems($orderId, $cartItems, $conn) {
    foreach ($cartItems as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiid', $orderId, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
        // Update stock
        $update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update->bind_param('ii', $item['quantity'], $item['product_id']);
        $update->execute();
    }
}

function clearCart($userId, $conn) {
    $cartStmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ?");
    $cartStmt->bind_param('i', $userId);
    $cartStmt->execute();
    $cartRes = $cartStmt->get_result();
    $cartId = $cartRes->fetch_assoc()['id'] ?? null;
    if ($cartId) {
        $conn->query("DELETE FROM cart_items WHERE cart_id = $cartId");
        $conn->query("DELETE FROM cart WHERE id = $cartId");
    }
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $state = $_POST['state'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $payment = $_POST['payment'] ?? '';
    $shipping = $_POST['shipping'] ?? '';
    $fullAddress = "$address, $city, $state, $zip";

    $cartItems = getCartItems($userId, $conn);
    if (empty($cartItems)) {
        $error = "Keranjang kosong!";
    } else {
        // Hitung total
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item['stock'] < $item['quantity']) {
                $error = "Stok produk {$item['name']} tidak cukup!";
                break;
            }
            $total += $item['price'] * $item['quantity'];
        }
        $shippingCost = 0;
        if ($shipping == 'Express Shipping') $shippingCost = 15;
        if ($shipping == 'Next Day Delivery') $shippingCost = 25;
        $total += $shippingCost;

        if (!isset($error)) {
            $orderId = saveOrder($userId, $fullAddress, $total, $shippingCost, $payment, $conn);
            saveOrderItems($orderId, $cartItems, $conn);
            clearCart($userId, $conn);
            header("Location: payment-simulation.php?order_id=$orderId");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GreenNest | plant store</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
  </head>
  <body>
   
  <!-- memasukan navbar -->
    <?php include './component/navbar.php'; ?>



    <main class="pt-28 px-4 lg:px-16 container mx-auto">
      <!-- Main Content -->
      <div id="content" class="flex flex-col md:flex-row gap-8">
        <!-- Form Section -->
        <div class="flex-1 order-2 md:order-1">
          <!-- Express Checkout Section -->
          <div class="mb-8">
            <h1 class="text-[#8E8E8E] text-center mb-4">Express checkout</h1>
            <div class="flex justify-center gap-2">
              <img class="w-28 sm:w-48" src="./src/img/pay-img (1).png" alt="" />
              <img class="w-28 sm:w-48" src="./src/img/pay-img (2).png" alt="" />
              <img class="w-28 sm:w-48" src="./src/img/pay-img (3).png" alt="" />
            </div>
            <div class="flex my-4 items-center justify-center space-x-2">
              <hr class="h-0.5 bg-[#8E8E8E] flex-1" />
              <p class="text-center px-4">OR</p>
              <hr class="h-0.5 bg-[#8E8E8E] flex-1" />
            </div>
          </div>

          <?php
$cartItems = getCartItems($userId, $conn);
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!-- Cart Summary for Mobile -->
<div class="md:hidden mb-8 bg-gray-50 p-4 rounded-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-medium">Cart Summary</h2>
        <span class="text-sm text-gray-600"><?= count($cartItems) ?> item</span>
    </div>
    <?php foreach ($cartItems as $item): ?>
    <div class="flex gap-4 mb-4">
        <div class="relative">
            <img src="<?= $item['image_url'] ?? './src/img/main-img-product.png' ?>" alt="<?= $item['name'] ?>" class="w-20 h-20 object-cover rounded-lg" />
            <span class="absolute -top-2 -right-2 w-5 h-5 bg-primary text-white rounded-full flex items-center justify-center text-xs"><?= $item['quantity'] ?></span>
        </div>
        <div class="flex-1">
            <h3 class="font-medium"><?= $item['name'] ?></h3>
            <div class="flex justify-between items-center mt-2">
                <span class="text-gray-400 line-through text-sm">$<?= number_format($item['price'],2) ?></span>
                <span class="font-medium">$<?= number_format($item['price'],2) ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="border-t pt-4">
        <div class="flex justify-between mb-2">
            <span>Subtotal</span>
            <span>$<?= number_format($total,2) ?></span>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
            <span>Shipping</span>
            <span>Calculated at next step</span>
        </div>
    </div>
</div>

          <!-- Original Form Content -->
          <form class="space-y-6" id="checkoutForm" method="POST" action="">
            <!-- Contact Section -->
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-medium">Contact</h2>
              <p class="text-sm text-gray-600">
                Already have an account?
                <a href="#" class="text-secondary font-medium hover:underline">Login</a>
              </p>
            </div>

            <!-- Email Input -->
            <div>
              <input type="email" name="email" placeholder="Email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />
              <div class="mt-2">
                <label class="inline-flex items-center">
                  <input type="checkbox" class="form-checkbox text-primary" />
                  <span class="ml-2 text-sm text-gray-600">Email me with news and offers</span>
                </label>
              </div>
            </div>

            <!-- Delivery Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Delivery</h2>

              <!-- Country Selection -->
              <div class="relative">
                <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                  <option value="" disabled selected>--Select Country--</option>
                  <option value="us">Indonesia</option>
                  <option value="uk">Malaysia</option>
                  <option value="ca">Thailand</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                  </svg>
                </div>
              </div>

              <!-- Company (Optional) -->
              <input type="text" name="company" placeholder="Company (optional)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />

              <!-- Address -->
              <input type="text" name="address" placeholder="Address (no PO Boxes)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />

              <!-- Apartment -->
              <input type="text" name="apartment" placeholder="Apartment, suite, etc (optional)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />

              <!-- City, ZIP and State -->
              <!-- City, ZIP and State -->
              <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- City Input -->
                <input type="text" name="city" placeholder="City" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />

                <!-- ZIP Code Select -->
                <select name="zip" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                  <option value="" disabled selected>ZIP code</option>
                  <option value="10001">10001</option>
                  <option value="20001">20001</option>
                  <!-- ... -->
                </select>

                <!-- State Select -->
                <select name="state" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                  <option value="" disabled selected>State</option>
                  <option value="NY">New York</option>
                  <option value="CA">California</option>
                  <!-- ... -->
                </select>
              </div>

              <!-- Phone -->
              <div class="mt-4 relative">
                <input type="tel" name="phone" placeholder="Phone (Optional)" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                <div class="absolute inset-y-0 right-4 flex items-center">
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                </div>
              </div>

              <!-- Text me checkbox -->
              <div class="mt-4">
                <label class="inline-flex items-center">
                  <input type="checkbox" class="form-checkbox text-primary" />
                  <span class="ml-2 text-sm text-gray-600">Text me with news and offers</span>
                </label>
              </div>
            </div>

            <!-- Shipping Method Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Shipping method</h2>

              <!-- Shipping Options -->
              <div class="space-y-3">
                <!-- Standard Shipping -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="true">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" value="Standard Shipping" required class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Standard Shipping</p>
                      <p class="text-sm text-gray-600">4-5 business days</p>
                    </div>
                  </div>
                  <span class="font-medium">Free</span>
                </label>

                <!-- Express Shipping -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="false">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" value="Express Shipping" class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Express Shipping</p>
                      <p class="text-sm text-gray-600">2-3 business days</p>
                    </div>
                  </div>
                  <span class="font-medium">$15.00</span>
                </label>

                <!-- Next Day Delivery -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="false">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" value="Next Day Delivery" class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Next Day Delivery</p>
                      <p class="text-sm text-gray-600">Next business day</p>
                    </div>
                  </div>
                  <span class="font-medium">$25.00</span>
                </label>
              </div>

              <!-- Info Message -->
              <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                </svg>
                <span>Some items may ship separately</span>
              </div>
            </div>

            <!-- Payment Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Metode Pembayaran</h2>
              <p class="text-sm text-gray-600 mb-4">Pilih metode pembayaran yang tersedia di Indonesia.</p>
              <div class="space-y-4">
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="transfer" class="form-radio text-primary" required />
                  <span class="font-medium">Transfer Bank</span>
                  <span class="text-xs text-gray-500">(BCA, Mandiri, BRI, dll)</span>
                </label>
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="cod" class="form-radio text-primary" />
                  <span class="font-medium">Bayar di Tempat (COD)</span>
                </label>
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="ewallet" class="form-radio text-primary" />
                  <span class="font-medium">E-Wallet</span>
                  <span class="text-xs text-gray-500">(OVO, GoPay, ShopeePay, dll)</span>
                </label>
              </div>
              <button
                type="submit"
                class="mt-6 w-full bg-primary text-white py-2.5 px-6 rounded-full hover:bg-opacity-90 transition-colors text-lg font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                Checkout Sekarang
              </button>
            </div>
          </form>
        </div>

        <!-- Sidebar - Hidden on mobile -->
        <?php
// Hitung subtotal, shipping, dan total untuk sidebar
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Default shipping label dan cost
$shippingLabel = 'Standard Shipping';
$shippingCost = 0;

// Jika ada POST (user sudah memilih shipping), ambil dari POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingLabel = $_POST['shipping'] ?? 'Standard Shipping';
    if ($shippingLabel == 'Express Shipping') $shippingCost = 15;
    if ($shippingLabel == 'Next Day Delivery') $shippingCost = 25;
    if ($shippingLabel == 'Standard Shipping') $shippingCost = 0;
}

$total = $subtotal + $shippingCost;
?>

<!-- Sidebar - Hidden on mobile -->
<aside class="hidden md:block w-[520px] bg-primary p-6 rounded-lg h-fit sticky top-28 order-1 md:order-2" id="sidebar">
  <!-- Order Summary -->
  <div class="space-y-6">
    <!-- Product Items -->
    <?php foreach ($cartItems as $item): ?>
    <div class="flex gap-4 mb-4">
      <div class="relative">
        <img src="<?= $item['image_url'] ?? './src/img/main-img-product.png' ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-20 object-cover rounded-lg" />
        <span class="absolute -top-2 -right-2 w-5 h-5 bg-white text-primary rounded-full flex items-center justify-center text-xs"><?= $item['quantity'] ?></span>
      </div>
      <div class="flex-1">
        <h3 class="font-medium text-white"><?= htmlspecialchars($item['name']) ?></h3>
        <p class="text-sm text-gray-200"><?= $item['plant_size'] ?? '' ?></p>
      </div>
      <div class="text-right">
        <?php if (!empty($item['price_old']) && $item['price_old'] > $item['price']): ?>
          <p class="text-white/60 line-through text-sm">$<?= number_format($item['price_old'],2) ?></p>
        <?php endif; ?>
        <p class="font-medium text-white">$<?= number_format($item['price'],2) ?></p>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Discount Input (opsional, belum terintegrasi) -->
    <div class="flex gap-2">
      <input type="text" placeholder="Discount code or gift card" class="flex-1 px-4 py-2.5 rounded-lg border border-white/40 bg-white/10 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent" />
      <button class="bg-white text-primary px-6 rounded-lg hover:bg-opacity-90">apply</button>
    </div>

    <!-- Order Summary Details -->
    <div class="space-y-3 pt-3">
      <div class="flex justify-between">
        <span class="text-white">Subtotal</span>
        <span class="text-white">$<?= number_format($subtotal,2) ?></span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-white">Shipping</span>
        <span class="text-white/80"><?= htmlspecialchars($shippingLabel) ?> <?= $shippingCost > 0 ? '$'.number_format($shippingCost,2) : 'Free' ?></span>
      </div>
      <div class="flex justify-between items-center pt-3 border-t-2 border-white/30">
        <span class="text-xl font-medium text-white">Total</span>
        <div class="text-right">
          <span class="text-sm text-white/80">USD</span>
          <span class="text-xl font-medium text-white">$<?= number_format($total,2) ?></span>
        </div>
      </div>
      <?php if ($subtotal > 0 && isset($item['price_old']) && $item['price_old'] > $item['price']): ?>
      <div class="flex items-center gap-1 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 15 16" fill="none">
          <!-- ...icon... -->
        </svg>
        <span class="text-white">TOTAL SAVINGS $<?= number_format($item['price_old'] - $item['price'],2) ?></span>
      </div>
      <?php endif; ?>
    </div>
  </div>
</aside>
      </div>

      <!-- Mobile Checkout Fixed Bottom -->
      <!-- Mobile Checkout Fixed Bottom -->
      <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 md:hidden">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm">Total</span>
          <div class="flex items-center">
            <div>
              <span class="text-sm text-gray-600 mr-2">USD</span>
              <span class="text-lg font-medium">$175.20</span>
            </div>
            <span class="text-gray-400 line-through text-sm ml-1">$219.00</span>
          </div>
        </div>
        <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-full hover:bg-opacity-90 transition-colors text-base font-medium">Continue to Payment</button>
      </div>
    </main>

    <script src="./src/script.js"></script>
    <!-- footer -->
    <?php include './component/footer.php'; ?>

    <!-- Add this JavaScript code -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const shippingOptions = document.querySelectorAll(".shipping-option");

        shippingOptions.forEach((option) => {
          option.addEventListener("click", function () {
            // Remove active state from all options
            shippingOptions.forEach((opt) => {
              opt.dataset.active = "false";
              opt.classList.remove("bg-[#B8E0CE]");
            });

            // Add active state to clicked option
            this.dataset.active = "true";
            this.classList.add("bg-[#B8E0CE]");

            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
          });
        });
      });
    </script>
  </body>
</html>

<?php
require_once 'config/db.php';

// Ambil order_id dari URL
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Ambil data order
$order = null;
if ($orderId) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
}

// Insert notifikasi jika belum ada untuk order ini
if ($order && $order['user_id']) {
    // Cek apakah notifikasi sudah ada
    $notifCheck = $conn->prepare("SELECT id FROM notifications WHERE user_id = ? AND type = 'checkout_success' AND message LIKE ?");
    $msgLike = "%Order #" . $orderId . "%";
    $notifCheck->bind_param('is', $order['user_id'], $msgLike);
    $notifCheck->execute();
    $notifCheck->store_result();
    if ($notifCheck->num_rows == 0) {
        $notifMsg = "Checkout berhasil! Order #" . $orderId . " sedang diproses.";
        $notifInsert = $conn->prepare("INSERT INTO notifications (user_id, type, message, is_read, created_at) VALUES (?, 'checkout_success', ?, 0, NOW())");
        $notifInsert->bind_param('is', $order['user_id'], $notifMsg);
        $notifInsert->execute();
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
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2d5016',
            secondary: '#4a7c28',
            accent: '#8bc34a',
            'light-green': '#f0f8e7'
          },
          fontFamily: {
            'sans': ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>
</head>
   <!-- navbar component -->
      <?php include('component/navbar.php'); ?>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
  <main class="container mx-auto px-4 py-32 flex items-center justify-center min-h-screen">
    <div class="backdrop-blur-md bg-white/95 border border-white/20 rounded-3xl shadow-2xl p-8 w-full max-w-md transform transition-all duration-700 hover:shadow-3xl hover:-translate-y-1">

   

      <!-- Success Icon & Header -->
      <div class="text-center mb-8 animate-pulse">
        <div class="relative mx-auto mb-6">
          <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto animate-bounce">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <!-- Decorative rings -->
          <div class="absolute inset-0 w-20 h-20 border-4 border-green-300/30 rounded-full mx-auto animate-ping"></div>
          <div class="absolute inset-0 w-24 h-24 border-2 border-green-200/20 rounded-full mx-auto animate-pulse" style="animation-delay: 0.5s;"></div>
        </div>
        
        <h1 class="text-3xl font-bold text-primary mb-3 tracking-tight">
          Checkout Berhasil!
        </h1>
        <p class="text-gray-600 text-base leading-relaxed max-w-sm mx-auto">
          Terima kasih telah berbelanja di GreenNest.<br>
          <span class="font-medium text-primary">Pesanan Anda sedang diproses</span>
          <?php if ($order): ?>
  <div class="mt-4 mb-2 p-3 bg-blue-100 text-blue-700 rounded-lg text-center">
    Notifikasi: Checkout berhasil! Order #<?= htmlspecialchars($orderId) ?> sedang diproses.
  </div>
<?php endif; ?>
        </p>
        
      </div>

      <!-- Order Summary Card -->
      <div class="bg-gradient-to-r from-gray-50 to-light-green/50 rounded-2xl p-6 mb-6 border border-gray-100 transition-all duration-300 hover:shadow-md">
        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-primary" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
          </svg>
          Ringkasan Pesanan
        </h3>
        
        <div class="space-y-3">
          <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
            <span class="text-gray-600 text-sm">Produk</span>
            <span class="font-semibold text-primary">Snake Plant Laurentii</span>
          </div>
          
          <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
            <span class="text-gray-600 text-sm">Jumlah</span>
            <span class="font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full text-sm">1</span>
          </div>
          
          <div class="flex justify-between items-center py-2 border-b border-gray-200/50">
            <span class="text-gray-600 text-sm">Metode Pembayaran</span>
            <span class="font-medium text-primary text-sm" id="payment-method">Transfer Bank</span>
          </div>
          
          <div class="flex justify-between items-center pt-3">
            <span class="text-gray-700 font-medium">Total Pembayaran</span>
            <span class="font-bold text-primary text-xl">$175.20</span>
          </div>
        </div>
      </div>

      <!-- Payment Instructions -->
      <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-50 to-primary/5 border border-primary/20 rounded-2xl p-5 transition-all duration-300 hover:shadow-sm">
          <h4 class="text-primary font-semibold mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"/>
            </svg>
            Instruksi Pembayaran
          </h4>
          
          <!-- Bank Transfer Instructions -->
          <div id="bank-instructions" class="space-y-3">
            <div class="bg-white/80 rounded-xl p-4 border border-primary/10">
              <p class="text-gray-700 text-sm leading-relaxed">
                Transfer ke rekening: 
                <span class="font-bold text-primary block mt-1">
                  🏦 BCA 1234567890 a.n. GreenNest
                </span>
              </p>
              <p class="text-gray-600 text-xs mt-2">
                💬 Konfirmasi pembayaran melalui WhatsApp setelah transfer
              </p>
            </div>
          </div>

          <!-- COD Instructions -->
          <div id="cod-instructions" class="hidden">
            <div class="bg-white/80 rounded-xl p-4 border border-primary/10">
              <p class="text-gray-700 text-sm">
                💰 <span class="font-semibold text-primary">Cash on Delivery</span><br>
                Bayar di tempat saat barang diterima
              </p>
            </div>
          </div>

          <!-- E-Wallet Instructions -->
          <div id="ewallet-instructions" class="hidden">
            <div class="bg-white/80 rounded-xl p-4 border border-primary/10">
              <p class="text-gray-700 text-sm">
                📱 Transfer ke: 
                <span class="font-bold text-primary block mt-1">
                  0812-3456-7890 (OVO/GoPay/ShopeePay)
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Tracking Info -->
      <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-5 mb-8">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/>
            </svg>
          </div>
          <div>
            <h4 class="font-semibold text-yellow-800 text-sm mb-1">Status Pesanan</h4>
            <p class="text-yellow-700 text-sm leading-relaxed">
              Pesanan akan diproses dalam 1-2 hari kerja. Anda akan menerima notifikasi untuk update status pesanan.
            </p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-3">
        <a href="index.php" class="w-full bg-gradient-to-r from-primary to-secondary text-white py-4 px-8 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 text-center block relative overflow-hidden group">
          <span class="relative z-10 flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Kembali ke Beranda
          </span>
          <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
        </a>
        
        <a href="./pengaturan.php" class="w-full border-2 border-primary text-primary py-3 px-8 rounded-2xl font-medium hover:bg-primary hover:text-white transition-all duration-300 text-center block">
          Lacak Pesanan
        </a>
      </div>
    </div>
  </main>

  <script>
    // Simulate getting payment method from previous page or URL
    function getPaymentMethod() {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('method') || 'transfer';
    }

    // Initialize page based on payment method
    document.addEventListener('DOMContentLoaded', function() {
      const method = getPaymentMethod();
      const paymentMethodSpan = document.getElementById('payment-method');
      
      // Hide all instruction sections
      document.getElementById('bank-instructions').classList.add('hidden');
      document.getElementById('cod-instructions').classList.add('hidden');
      document.getElementById('ewallet-instructions').classList.add('hidden');
      
      // Show relevant instructions and update payment method display
      if (method === 'transfer') {
        document.getElementById('bank-instructions').classList.remove('hidden');
        paymentMethodSpan.textContent = 'Transfer Bank';
      } else if (method === 'cod') {
        document.getElementById('cod-instructions').classList.remove('hidden');
        paymentMethodSpan.textContent = 'Cash on Delivery';
      } else if (method === 'ewallet') {
        document.getElementById('ewallet-instructions').classList.remove('hidden');
        paymentMethodSpan.textContent = 'E-Wallet';
      }

      // Add entrance animation
      const card = document.querySelector('main > div');
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        card.style.transition = 'all 0.7s cubic-bezier(0.4, 0, 0.2, 1)';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, 100);
    });

    // Add some interactive feedback
    document.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (this.href === '#') {
          e.preventDefault();
          // Add ripple effect or show coming soon message
          const ripple = document.createElement('div');
          ripple.className = 'absolute inset-0 bg-white/30 rounded-2xl animate-ping';
          this.style.position = 'relative';
          this.appendChild(ripple);
          
          setTimeout(() => ripple.remove(), 600);
        }
      });
    });
  </script>
</body>
</html>
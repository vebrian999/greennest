<?php
require_once 'config/db.php';

$orderId = $_GET['order_id'] ?? null;
$order = null;

// Proses upload bukti pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $orderId) {
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . "/uploads/bukti_pembayaran/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["bukti"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $targetFile)) {
            // Generate tracking number otomatis
            $trackingNumber = "GN" . date("YmdHis") . $orderId;
            $stmt = $conn->prepare("UPDATE orders SET bukti_pembayaran = ?, tracking_number = ? WHERE id = ?");
            $stmt->bind_param('ssi', $fileName, $trackingNumber, $orderId);
            $stmt->execute();
            // Redirect ke halaman sukses
            header("Location: checkout-success.php?order_id=$orderId");
            exit;
        }
    }
}

// Ambil data order
if ($orderId) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
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
  <style>
    :root {
      --primary: #2d5016;
      --secondary: #4a7c28;
      --accent: #8bc34a;
      --light-green: #f0f8e7;
    }
    
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .primary { color: var(--primary); }
    .bg-primary { background-color: var(--primary); }
    .bg-secondary { background-color: var(--secondary); }
    .bg-light-green { background-color: var(--light-green); }
    .border-primary { border-color: var(--primary); }
    .ring-primary { --tw-ring-color: var(--primary); }
    
    .glass-effect {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .payment-card {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid transparent;
    }
    
    .payment-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .file-upload {
      position: relative;
      overflow: hidden;
      display: inline-block;
      width: 100%;
    }
    
    .file-upload input[type=file] {
      position: absolute;
      left: -9999px;
    }
    
    .file-upload-label {
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      border: 2px dashed #d1d5db;
      border-radius: 0.75rem;
      transition: all 0.3s ease;
      background: #fafafa;
    }
    
    .file-upload-label:hover {
      border-color: var(--primary);
      background: var(--light-green);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 20px rgba(45, 80, 22, 0.3);
    }
    
    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-primary:hover:before {
      left: 100%;
    }
    
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .slide-in {
      animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }
  </style>
</head>
   <!-- navbar component -->
      <?php include('component/navbar.php'); ?>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">


  <main class="container mx-auto px-4 pt-28 pb-20 flex items-center justify-center min-h-screen">
    <div class="payment-card glass-effect rounded-3xl p-8 w-full max-w-md fade-in">
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-light-green rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 primary" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-semibold primary mb-2">Simulasi Pembayaran</h1>
        <p class="text-gray-600 text-sm">Lakukan pembayaran sesuai metode pilihan Anda</p>
      </div>

      <form action="payment-simulation.php?order_id=<?= $orderId ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Payment Details Card -->
        <div id="payment-info" class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 slide-in">
          <h3 class="text-lg font-medium primary mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
            </svg>
            Detail Pembayaran
          </h3>

          <!-- Bank Transfer -->
          <div id="bank-info" class="hidden space-y-3">
            <p class="text-gray-600 text-sm mb-3">Transfer ke rekening:</p>
            <div class="bg-white rounded-xl p-4 border-l-4 border-primary">
              <div class="flex justify-between items-center">
                <div>
                  <span class="font-semibold primary block">BCA 1234567890</span>
                  <span class="text-gray-500 text-sm">a.n. GreenNest</span>
                </div>
                <button type="button" class="text-primary text-sm hover:underline" onclick="copyToClipboard('1234567890')">
                  Copy
                </button>
              </div>
            </div>
          </div>

          <!-- E-Wallet -->
          <div id="ewallet-info" class="hidden space-y-3">
            <p class="text-gray-600 text-sm mb-3">Transfer ke e-wallet:</p>
            <div class="bg-white rounded-xl p-4 border-l-4 border-primary">
              <div class="flex justify-between items-center">
                <div>
                  <span class="font-semibold primary block">0812-3456-7890</span>
                  <span class="text-gray-500 text-sm">OVO/GoPay/ShopeePay</span>
                </div>
                <button type="button" class="text-primary text-sm hover:underline" onclick="copyToClipboard('081234567890')">
                  Copy
                </button>
              </div>
            </div>
          </div>

          <!-- COD -->
          <div id="cod-info" class="hidden">
            <div class="bg-white rounded-xl p-4 border-l-4 border-primary">
              <p class="text-gray-600 text-sm">💰 Bayar di tempat saat barang diterima</p>
            </div>
          </div>

          <!-- Total Amount -->
          <div class="border-t border-gray-200 pt-4 mt-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Total Pembayaran</span>
              <span class="font-bold primary text-xl">$175.20</span>
            </div>
          </div>
        </div>

        <!-- File Upload -->
        <div id="upload-section" class="space-y-2">
          <label class="block text-primary font-medium text-sm">Bukti Pembayaran</label>
          <div class="file-upload">
            <input type="file" name="bukti" id="bukti" accept="image/*" required onchange="handleFileSelect(this)" />
            <label for="bukti" class="file-upload-label">
              <div class="text-center">
                <svg class="w-8 h-8 primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                </svg>
                <p class="text-sm text-gray-600">
                  <span class="font-medium primary">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 10MB</p>
              </div>
            </label>
          </div>
          <div id="file-preview" class="hidden mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9,16.17L4.83,12l-1.42,1.41L9,19 21,7l-1.41-1.41L9,16.17z"/>
                </svg>
                <span class="text-sm text-green-700" id="file-name"></span>
              </div>
              <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700 text-sm">
                Remove
              </button>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary text-white py-4 px-8 rounded-2xl font-semibold text-lg shadow-lg">
          Konfirmasi Pembayaran
        </button>
      </form>

      <!-- Back Link -->
      <div class="text-center mt-6">
        <a href="checkout-form.php" class="text-primary hover:underline text-sm font-medium">
          ← Kembali ke Checkout
        </a>
      </div>

      <!-- Payment Proof Notification -->
      <?php if ($order && $order['bukti_pembayaran']): ?>
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
          Bukti pembayaran sudah di-upload.<br>
          <a href="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" target="_blank" class="underline">Lihat bukti pembayaran</a>
        </div>
      <?php endif; ?>

      <!-- Success Message -->
      <?php if (isset($_GET['success'])): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center">
          Bukti pembayaran berhasil di-upload!
        </div>
      <?php endif; ?>
    </div>
  </main>

  <script>
    // Get payment method from URL
    function getPaymentMethod() {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('method') || 'transfer';
    }

    // Initialize payment info display
    document.addEventListener('DOMContentLoaded', function() {
      const method = getPaymentMethod();
      
      // Hide all payment info sections
      document.getElementById('bank-info').classList.add('hidden');
      document.getElementById('ewallet-info').classList.add('hidden');
      document.getElementById('cod-info').classList.add('hidden');
      
      // Show relevant payment info
      if (method === 'transfer') {
        document.getElementById('bank-info').classList.remove('hidden');
      } else if (method === 'ewallet') {
        document.getElementById('ewallet-info').classList.remove('hidden');
      } else if (method === 'cod') {
        document.getElementById('cod-info').classList.remove('hidden');
        // Hide upload section for COD
        document.getElementById('upload-section').style.display = 'none';
        document.getElementById('bukti').required = false;
      }
    });

    // Copy to clipboard function
    function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('text-green-600');
        
        setTimeout(() => {
          button.textContent = originalText;
          button.classList.remove('text-green-600');
        }, 2000);
      });
    }

    // Handle file selection
    function handleFileSelect(input) {
      const file = input.files[0];
      const preview = document.getElementById('file-preview');
      const fileName = document.getElementById('file-name');
      
      if (file) {
        fileName.textContent = file.name;
        preview.classList.remove('hidden');
      }
    }

    // Remove selected file
    function removeFile() {
      document.getElementById('bukti').value = '';
      document.getElementById('file-preview').classList.add('hidden');
    }

    // Add loading state to submit button
    document.querySelector('form').addEventListener('submit', function(e) {
      const button = this.querySelector('button[type="submit"]');
      button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
      `;
      button.disabled = true;
    });
  </script>


</body>
</html>
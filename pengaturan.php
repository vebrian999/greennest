<?php
session_start();
require_once 'config/db.php';
// Cek login
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
$userId = $_SESSION['user_id'];
$profileUploadMsg = '';
$profileMsg = '';
$passwordMsg = '';
$addressMsg = '';
$userNotFound = false;

if (!isset($activeTab)) {
  $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
}

// Query user
$userQuery = $conn->prepare("SELECT * FROM users WHERE id = ?");
$userQuery->bind_param('i', $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
if (!$userData) {
  $userNotFound = true;
}
// Handle upload foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_profile_image'])) {
  if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = $_FILES['profile_image']['type'];
    if (in_array($fileType, $allowedTypes)) {
      $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
      $newFileName = 'profile' . $userId . '_' . time() . '.' . $ext;
      $uploadDir = 'uploads/profile/';
      if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
      $uploadPath = $uploadDir . $newFileName;
      if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
        $updateImg = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
        $updateImg->bind_param('si', $uploadPath, $userId);
        if ($updateImg->execute()) {
          $profileUploadMsg = 'Foto profil berhasil diubah.';
          // Refresh user data
          $userQuery->execute();
          $userResult = $userQuery->get_result();
          $userData = $userResult->fetch_assoc();
        } else {
          $profileUploadMsg = 'Gagal update foto profil.';
        }
      } else {
        $profileUploadMsg = 'Gagal upload file.';
      }
    } else {
      $profileUploadMsg = 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.';
    }
  } else {
    $profileUploadMsg = 'Pilih file gambar untuk diupload.';
  }
  // Redirect to maintain tab after form submission
  header('Location: pengaturan.php?tab=' . $activeTab);
  exit;
}
// Handle hapus foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile_image'])) {
  if (!empty($userData['profile_image']) && file_exists($userData['profile_image'])) {
    unlink($userData['profile_image']);
  }
  $updateImg = $conn->prepare("UPDATE users SET profile_image=NULL WHERE id=?");
  $updateImg->bind_param('i', $userId);
  if ($updateImg->execute()) {
    $profileUploadMsg = 'Foto profil berhasil dihapus.';
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $userData = $userResult->fetch_assoc();
  } else {
    $profileUploadMsg = 'Gagal menghapus foto profil.';
  }
  // Redirect to maintain tab after form submission
  header('Location: pengaturan.php?tab=' . $activeTab);
  exit;
}
// Ambil alamat user
$addressQuery = $conn->prepare("SELECT * FROM addresses WHERE user_id = ?");
$addressQuery->bind_param('i', $userId);
$addressQuery->execute();
$addressResult = $addressQuery->get_result();
$addresses = [];
while ($row = $addressResult->fetch_assoc()) {
  $addresses[] = $row;
}
// Handle update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $updateQuery = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
  $updateQuery->bind_param('sssi', $name, $email, $phone, $userId);
  if ($updateQuery->execute()) {
    $profileMsg = 'Profil berhasil diperbarui.';
    // Refresh user data
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $userData = $userResult->fetch_assoc();
  } else {
    $profileMsg = 'Gagal memperbarui profil.';
  }
  // Redirect to maintain tab after form submission
  header('Location: pengaturan.php?tab=details');
  exit;
}
// Handle ganti password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
  $current = $_POST['current_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];
  if ($new !== $confirm) {
    $passwordMsg = 'Konfirmasi password tidak cocok.';
  } else {
    // Cek password lama
    if (password_verify($current, $userData['password'])) {
      $newHash = password_hash($new, PASSWORD_DEFAULT);
      $passQuery = $conn->prepare("UPDATE users SET password=? WHERE id=?");
      $passQuery->bind_param('si', $newHash, $userId);
      if ($passQuery->execute()) {
        $passwordMsg = 'Password berhasil diganti.';
      } else {
        $passwordMsg = 'Gagal mengganti password.';
      }
    } else {
      $passwordMsg = 'Password lama salah.';
    }
  }
  // Redirect to maintain tab after form submission
  header('Location: pengaturan.php?tab=details');
  exit;
}
// Handle tambah alamat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
  $address = trim($_POST['address']);
  $isDefault = isset($_POST['is_default']) ? 1 : 0;
  if ($isDefault) {
    // Set semua alamat lain non-default
    $conn->query("UPDATE addresses SET is_default=0 WHERE user_id=$userId");
  }
  // Insert ke tabel addresses, bukan users
  $addQuery = $conn->prepare("INSERT INTO addresses (user_id, address, is_default) VALUES (?, ?, ?)");
  $addQuery->bind_param('isi', $userId, $address, $isDefault);
  if ($addQuery->execute()) {
    $addressMsg = 'Alamat berhasil ditambahkan.';
    // Refresh alamat
    $addressQuery->execute();
    $addressResult = $addressQuery->get_result();
    $addresses = [];
    while ($row = $addressResult->fetch_assoc()) {
      $addresses[] = $row;
    }
  } else {
    $addressMsg = 'Gagal menambah alamat.';
  }
  // Redirect to maintain tab after form submission
  header('Location: pengaturan.php?tab=addresses');
  exit;
}
// Handle set default alamat
if (isset($_GET['set_default'])) {
  $addrId = intval($_GET['set_default']);
  $conn->query("UPDATE addresses SET is_default=0 WHERE user_id=$userId");
  $setQuery = $conn->prepare("UPDATE addresses SET is_default=1 WHERE id=? AND user_id=?");
  $setQuery->bind_param('ii', $addrId, $userId);
  $setQuery->execute();
  header('Location: pengaturan.php?tab=addresses');
  exit;
}
// Handle hapus alamat
if (isset($_GET['delete_address'])) {
  $addrId = intval($_GET['delete_address']);
  $delQuery = $conn->prepare("DELETE FROM addresses WHERE id=? AND user_id=?");
  $delQuery->bind_param('ii', $addrId, $userId);
  $delQuery->execute();
  header('Location: pengaturan.php?tab=addresses');
  exit;
}
// Ambil notifikasi
$notifications = [];
$notifQuery = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$notifQuery->bind_param('i', $userId);
$notifQuery->execute();
$notifResult = $notifQuery->get_result();
while ($row = $notifResult->fetch_assoc()) {
    $notifications[] = $row;
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
  <body class="min-h-screen bg-white">
    <!-- Header -->
    <?php include('component/navbar.php'); ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mt-20">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
          <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-6 border border-white/20 sticky top-24">
            <!-- User Profile -->
            <div class="text-center mb-8">
              <?php if ($profileUploadMsg) { echo '<div class="mb-4 text-green-600">'.htmlspecialchars($profileUploadMsg).'</div>'; } ?>
              <?php if ($userNotFound): ?>
                <div class="mb-4 text-red-600">Data user tidak ditemukan di database.</div>
                <div class="w-20 h-20 bg-secondary/30 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-primary/30">
                  <span class="text-2xl font-bold text-white">?</span>
                </div>
                <h3 class="font-semibold text-primary">Belum diisi</h3>
                <p class="text-sm text-gray-600">Belum diisi</p>
              <?php else: ?>
                <div class="w-20 h-20 bg-secondary/30 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-primary/30 relative group cursor-pointer" id="profileImageWrapper">
                  <?php
                    if (!empty($userData['profile_image'])) {
                      echo '<img src="' . htmlspecialchars($userData['profile_image']) . '" alt="Foto Profil" class="w-full h-full object-cover rounded-full transition duration-200 group-hover:brightness-75" id="profileImage" />';
                    } else {
                      echo '<span class="text-2xl font-bold text-white cursor-pointer w-full h-full flex items-center justify-center rounded-full transition duration-200 group-hover:bg-black/30" id="profileImage">';
                      if (isset($userData['name']) && $userData['name']) {
                        $nameParts = explode(' ', $userData['name']);
                        $firstInitial = strtoupper(substr($nameParts[0],0,1));
                        $lastInitial = isset($nameParts[1]) ? strtoupper(substr($nameParts[1],0,1)) : '';
                        echo $firstInitial . $lastInitial;
                      } else {
                        echo '?';
                      }
                      echo '</span>';
                    }
                  ?>
                  <!-- Overlay pensil -->
                  <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-full">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 0 0-2.828 0l-9.192 9.192a2 2 0 0 0-.497.879l-1 4A1 1 0 0 0 4 18a.997.997 0 0 0 .242-.03l4-1a2 2 0 0 0 .879-.497l9.192-9.192a2 2 0 0 0 0-2.828zm-10.607 10.607l7.778-7.778 2.121 2.121-7.778 7.778-2.121-2.121zm-1.414 1.414l2.121 2.121-2.829.707.708-2.828z"/></svg>
                  </span>
                </div>
                <h3 class="font-semibold text-primary">
                  <?php echo (isset($userData['name']) && $userData['name']) ? htmlspecialchars($userData['name']) : 'Belum diisi'; ?>
                </h3>
                <p class="text-sm text-gray-600">
                  <?php echo (isset($userData['email']) && $userData['email']) ? htmlspecialchars($userData['email']) : 'Belum diisi'; ?>
                </p>
              <?php endif; ?>
            </div>
            <!-- Navigation Menu -->
            <nav class="space-y-2">
              <a href="?tab=dashboard" id="nav-dashboard" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'dashboard' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-secondary/10 hover:text-secondary'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                </svg>
                Dashboard
              </a>
              <a href="?tab=orders" id="nav-orders" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'orders' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-secondary/10 hover:text-secondary'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z" />
                </svg>
                Orders
              </a>
              <a href="?tab=wishlist" id="nav-wishlist" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'wishlist' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-pink-100 hover:text-pink-600'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                Wishlist / Favorite
              </a>
              <a href="?tab=notifications" id="nav-notifications" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'notifications' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-600'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2zm6-6V11c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v0.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29A1 1 0 006 20h12a1 1 0 00.71-1.71L18 16z" />
                </svg>
                Notifications
              </a>
              <a href="?tab=addresses" id="nav-addresses" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'addresses' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-secondary/10 hover:text-secondary'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22S19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9S10.62 6.5 12 6.5S14.5 7.62 14.5 9S13.38 11.5 12 11.5Z" />
                </svg>
                Addresses
              </a>
              <a href="?tab=details" id="nav-details" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl <?php echo $activeTab === 'details' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-secondary/10 hover:text-secondary'; ?> font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H19C20.11 23 21 22.11 21 21V9Z" />
                </svg>
                Account Details
              </a>
              <a href="logout.php" class="nav-item w-full flex items-center text-left px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-medium transition-all duration-300 mt-2">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Log Out
              </a>
            </nav>
          </div>
        </div>
        <!-- modal profile -->
        <div id="profileModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
          <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-xs text-center relative">
            <button id="closeProfileModal" class="absolute top-2 right-2 text-gray-400 hover:text-primary text-xl">&times;</button>
            <h3 class="font-semibold text-primary mb-4">Foto Profil</h3>
            <?php if (!empty($userData['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($userData['profile_image']); ?>" alt="Foto Profil" class="w-20 h-20 object-cover rounded-full mx-auto mb-4" />
            <?php else: ?>
              <div class="w-20 h-20 bg-secondary/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl font-bold text-white">
                  <?php
                    if (isset($userData['name']) && $userData['name']) {
                      $nameParts = explode(' ', $userData['name']);
                      $firstInitial = strtoupper(substr($nameParts[0],0,1));
                      $lastInitial = isset($nameParts[1]) ? strtoupper(substr($nameParts[1],0,1)) : '';
                      echo $firstInitial . $lastInitial;
                    } else {
                      echo '?';
                    }
                  ?>
                </span>
              </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="mt-2 flex flex-col items-center gap-2">
              <input type="file" name="profile_image" accept="image/*" class="block w-full max-w-xs text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
              <button type="submit" name="upload_profile_image" class="px-4 py-2 bg-primary text-white rounded-xl font-semibold shadow hover:bg-secondary transition-colors w-full">Upload Foto</button>
            </form>
            <?php if (!empty($userData['profile_image'])): ?>
              <form method="post" class="mt-2">
                <input type="hidden" name="delete_profile_image" value="1" />
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-xl font-semibold shadow hover:bg-red-600 transition-colors w-full">Hapus Foto</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
        <!-- Main Content -->
        <div class="lg:col-span-3">
          <!-- Dashboard Content -->
          <div id="content-dashboard" class="content-section <?php echo $activeTab !== 'dashboard' ? 'hidden' : ''; ?>">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 mb-8">
              <h2 class="text-2xl font-bold text-primary mb-6">Dashboard</h2>
              <?php
                // Total Orders
                $totalOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE user_id = $userId")->fetch_row()[0];

                // Plants Owned (jumlah produk unik yang pernah dibeli user)
                $plantsOwnedQuery = $conn->query("
                  SELECT COUNT(DISTINCT oi.product_id)
                  FROM orders o
                  JOIN order_items oi ON o.id = oi.order_id
                  WHERE o.user_id = $userId
                ");
                $plantsOwned = $plantsOwnedQuery->fetch_row()[0];

                // Total Spent
                $totalSpentQuery = $conn->query("
                  SELECT COALESCE(SUM(total_amount + shipping_cost),0)
                  FROM orders
                  WHERE user_id = $userId AND status IN ('completed','delivered')
                ");
                $totalSpent = $totalSpentQuery->fetch_row()[0];

                // Recent Orders
                $recentOrdersQuery = $conn->prepare("
                  SELECT o.*, GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
                  FROM orders o
                  LEFT JOIN order_items oi ON o.id = oi.order_id
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE o.user_id = ?
                  GROUP BY o.id
                  ORDER BY o.order_date DESC
                  LIMIT 2
                ");
                $recentOrdersQuery->bind_param('i', $userId);
                $recentOrdersQuery->execute();
                $recentOrdersResult = $recentOrdersQuery->get_result();
                $recentOrders = [];
                while ($row = $recentOrdersResult->fetch_assoc()) {
                  $recentOrders[] = $row;
                }
              ?>
              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-primary/90 rounded-2xl p-6 text-white shadow-lg">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-white/80 text-sm">Total Orders</p>
                      <p class="text-2xl font-bold"><?php echo $totalOrders; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div class="bg-secondary/90 rounded-2xl p-6 text-white shadow-lg">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-white/80 text-sm">Plants Owned</p>
                      <p class="text-2xl font-bold"><?php echo $plantsOwned; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div class="bg-green-400/90 rounded-2xl p-6 text-white shadow-lg">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-white/80 text-sm">Total Spent</p>
                      <p class="text-2xl font-bold">Rp <?php echo number_format($totalSpent, 0, ',', '.'); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22S22 17.52 22 12S17.52 2 12 2ZM13.41 18.09L13.13 18.37C12.73 18.77 12.1 18.77 11.7 18.37L11.42 18.09C11.02 17.69 11.02 17.06 11.42 16.66L13.08 15L11.42 13.34C11.02 12.94 11.02 12.31 11.42 11.91L11.7 11.63C12.1 11.23 12.73 11.23 13.13 11.63L14.79 13.29L16.45 11.63C16.85 11.23 17.48 11.23 17.88 11.63L18.16 11.91C18.56 12.31 18.56 12.94 18.16 13.34L16.5 15L18.16 16.66C18.56 17.06 18.56 17.69 18.16 18.09L17.88 18.37C17.48 18.77 16.85 18.77 16.45 18.37L14.79 16.71L13.13 18.37C13.13 18.37 13.41 18.09 13.41 18.09Z" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Recent Orders -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-primary mb-4">Recent Orders</h3>
                <div class="space-y-4">
                  <?php if (empty($recentOrders)): ?>
                    <div class="text-center text-gray-500 py-8">Belum ada pesanan terbaru.</div>
                  <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                      <div class="flex items-center justify-between p-4 bg-white/70 rounded-2xl border border-primary/20 shadow-sm">
                        <div class="flex items-center">
                          <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-secondary" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" />
                            </svg>
                          </div>
                          <div>
                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($order['product_names']); ?></p>
                            <p class="text-sm text-gray-600">Order #<?php echo htmlspecialchars($order['tracking_number'] ?? $order['id']); ?> • <?php echo date('d M Y', strtotime($order['order_date'])); ?></p>
                          </div>
                        </div>
                        <div class="text-right">
                          <p class="font-semibold text-primary">Rp <?php echo number_format($order['total_amount'] + $order['shipping_cost'], 0, ',', '.'); ?></p>
                          <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
  <?php
    $statusClass = [
      'pending' => 'bg-yellow-100 text-yellow-800',
      'paid' => 'bg-blue-100 text-blue-800',
      'shipped' => 'bg-green-100 text-green-800',
      'delivered' => 'bg-green-100 text-green-800',
      'completed' => 'bg-secondary/20 text-secondary',
      'cancelled' => 'bg-red-100 text-red-800'
    ];
    echo $statusClass[$order['status']] ?? 'bg-gray-100 text-gray-800';
  ?>">
  <?php echo ucfirst($order['status']); ?>
</span>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <?php include('./component/orders.php'); ?>
          <!-- Wishlist Content -->
          <?php include('./component/favorite.php'); ?>
          <!-- Notifications Content -->
          <?php include('./component/notifications-component.php'); ?>
          <!-- Account Details Content -->
          <div id="content-details" class="content-section <?php echo $activeTab !== 'details' ? 'hidden' : ''; ?>">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
              <h2 class="text-2xl font-bold text-primary mb-6">Account Details</h2>
              <?php if ($profileMsg) { echo '<div class="mb-4 text-green-600">'.htmlspecialchars($profileMsg).'</div>'; } ?>
              <form class="space-y-6" method="post">
                <div>
                  <label class="block text-sm font-medium text-primary mb-2">Nama Lengkap</label>
                  <input type="text" name="name" value="<?php echo isset($userData['name']) ? htmlspecialchars($userData['name']) : ''; ?>" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-primary mb-2">Email Address</label>
                  <input type="email" name="email" value="<?php echo isset($userData['email']) ? htmlspecialchars($userData['email']) : ''; ?>" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-primary mb-2">Phone Number</label>
                  <input type="text" name="phone" value="<?php echo isset($userData['phone']) ? htmlspecialchars($userData['phone']) : ''; ?>" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                </div>
                <div class="pt-4 border-t border-primary/20">
                  <h3 class="text-lg font-semibold text-primary mb-4">Change Password</h3>
                  <?php if ($passwordMsg) { echo '<div class="mb-2 text-red-600">'.htmlspecialchars($passwordMsg).'</div>'; } ?>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-primary mb-2">Current Password</label>
                      <input type="password" name="current_password" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-primary mb-2">New Password</label>
                      <input type="password" name="new_password" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-primary mb-2">Confirm New Password</label>
                      <input type="password" name="confirm_password" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" />
                    </div>
                  </div>
                </div>
                <div class="flex justify-end">
                  <button type="submit" name="update_profile" class="px-6 py-3 bg-primary text-white rounded-2xl font-semibold shadow-md hover:bg-secondary transition-colors">Save Changes</button>
                  <button type="submit" name="change_password" class="ml-4 px-6 py-3 bg-secondary text-white rounded-2xl font-semibold shadow-md hover:bg-primary transition-colors">Change Password</button>
                </div>
              </form>
            </div>
          </div>
          <!-- Addresses Content -->
          <div id="content-addresses" class="content-section <?php echo $activeTab !== 'addresses' ? 'hidden' : ''; ?>">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
              <h2 class="text-2xl font-bold text-primary mb-6">Addresses</h2>
              <?php if ($addressMsg) { echo '<div class="mb-4 text-green-600">'.htmlspecialchars($addressMsg).'</div>'; } ?>
              <div class="space-y-6">
                <?php foreach ($addresses as $addr): ?>
                  <div class="border border-primary/20 rounded-2xl p-6 bg-white/90 shadow-sm flex justify-between items-center">
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Alamat</h3>
                      <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($addr['address']); ?></p>
                      <?php if ($addr['is_default']): ?>
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-sm">Default</span>
                      <?php endif; ?>
                    </div>
                    <div class="flex gap-2">
                      <?php if (!$addr['is_default']): ?>
                        <a href="?set_default=<?php echo $addr['id']; ?>&tab=addresses" class="px-3 py-1 bg-primary text-white rounded-lg text-sm hover:bg-secondary transition-colors">Set as Default</a>
                      <?php endif; ?>
                      <a href="?delete_address=<?php echo $addr['id']; ?>&tab=addresses" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-colors" onclick="return confirm('Hapus alamat ini?')">Delete</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <form class="mt-8 space-y-4" method="post">
                <h3 class="font-semibold text-primary">Tambah Alamat Baru</h3>
                <input type="text" name="address" placeholder="Alamat lengkap" class="w-full px-4 py-3 rounded-2xl border border-primary/20 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/90 text-gray-700" required />
                  <div class="flex items-center justify-between mt-4">
                  <label class="inline-flex items-center">
                  <input type="checkbox" name="is_default" class="form-checkbox text-primary" />
                  <span class="ml-2 text-sm text-gray-700">Jadikan Default</span>
                </label>
                <button type="submit" name="add_address" class="px-6 py-3 bg-primary text-white rounded-2xl font-semibold shadow-md hover:bg-secondary transition-colors">Tambah Alamat</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End of main content area -->
    <!-- Floating Elements -->
    <div class="fixed top-20 left-10 w-20 h-20 bg-secondary/10 rounded-full blur-xl animate-pulse"></div>
    <div class="fixed bottom-20 right-10 w-32 h-32 bg-primary/10 rounded-full blur-xl animate-pulse" style="animation-delay: 2s"></div>

<script>
// Popup Profile Image
document.addEventListener('DOMContentLoaded', function () {
  var profileImageWrapper = document.getElementById('profileImageWrapper');
  var profileModal = document.getElementById('profileModal');
  var closeProfileModal = document.getElementById('closeProfileModal');
  
  if (profileImageWrapper && profileModal && closeProfileModal) {
    profileImageWrapper.addEventListener('click', function () {
      profileModal.classList.remove('hidden');
      profileModal.classList.add('flex');
    });
    
    closeProfileModal.addEventListener('click', function () {
      profileModal.classList.add('hidden');
      profileModal.classList.remove('flex');
    });
    
    profileModal.addEventListener('click', function (e) {
      if (e.target === profileModal) {
        profileModal.classList.add('hidden');
        profileModal.classList.remove('flex');
      }
    });
  }
});
</script>
<script src="./src/script.js"></script>
  </body>
</html>


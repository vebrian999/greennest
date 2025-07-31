<?php
// Pastikan tidak ada output sebelum ini
ob_start(); // Buffer output untuk mencegah headers already sent

require_once __DIR__ . '/../config/db.php';
$userId = $_SESSION['user_id'] ?? 0;

// Handle aksi mark as read notifikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read_id'])) {
  $notifId = intval($_POST['mark_read_id']);
  $markQuery = $conn->prepare("UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?");
  $markQuery->bind_param('ii', $notifId, $userId);
  $markQuery->execute();
  
  // Gunakan JavaScript redirect untuk menghindari form resubmit
  echo "<script>window.location.href = 'pengaturan.php?tab=notifications';</script>";
  ob_end_flush();
  exit;
}

// Handle mark all as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
  $markAllQuery = $conn->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?");
  $markAllQuery->bind_param('i', $userId);
  $markAllQuery->execute();
  
  // Gunakan JavaScript redirect untuk menghindari form resubmit
  echo "<script>window.location.href = 'pengaturan.php?tab=notifications';</script>";
  ob_end_flush();
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

// Hitung unread
$unreadCount = 0;
foreach ($notifications as $notif) {
  if (!$notif['is_read']) $unreadCount++;
}

// Tambahkan fungsi timeAgo
function timeAgo($datetime) {
  $timestamp = strtotime($datetime);
  $diff = time() - $timestamp;
  if ($diff < 60) return 'Baru saja';
  elseif ($diff < 3600) return round($diff/60).' menit yang lalu';
  elseif ($diff < 86400) return round($diff/3600).' jam yang lalu';
  elseif ($diff < 604800) return round($diff/86400).' hari yang lalu';
  else return date('d M Y, H:i', $timestamp);
}

ob_end_flush(); // Selesai buffering
?>

<!-- Notifications Content -->
<div id="content-notifications" class="content-section <?php echo $activeTab !== 'notifications' ? 'hidden' : ''; ?>">
  <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-blue-600 mb-0">
        <svg class="w-7 h-7 inline mr-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2zm6-6V11c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v0.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29A1 1 0 006 20h12a1 1 0 00.71-1.71L18 16z" />
        </svg>
        Notifications
      </h2>
      <div class="flex items-center space-x-3">
        <?php if ($unreadCount > 0): ?>
          <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-medium">
            <?php echo $unreadCount . ' unread'; ?>
          </span>
          <form method="post" class="inline-block" onsubmit="return confirmMarkAllRead()">
            <input type="hidden" name="mark_all_read" value="1">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-xl text-sm font-medium hover:bg-blue-600 transition-all duration-300 shadow-sm">
              Mark All Read
            </button>
          </form>
        <?php else: ?>
          <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">
            All caught up!
          </span>
        <?php endif; ?>
      </div>
    </div>

    <p class="mb-6 text-gray-600">Semua update dan informasi penting tentang pesanan dan akun Anda.</p>
    
    <div class="space-y-4">
      <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $notif): 
          $iconType = strtolower($notif['type']);
          $iconBg = 'bg-gray-400';
          $iconSvg = '';
          $cardBg = 'bg-gray-50/50';
          $cardBorder = 'border-gray-200';

          switch ($iconType) {
            case 'completed':
              $iconBg = 'bg-green-500';
              $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="text-white w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                          </svg>';
              $cardBg = 'bg-emerald-50/50';
              $cardBorder = 'border-emerald-200';
              break;
            case 'shipped':
              $iconBg = 'bg-emerald-500';
              $iconSvg = '<svg class="text-white h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>';
              $cardBg = 'bg-emerald-50/50';
              $cardBorder = 'border-emerald-200';
              break;
            case 'delivered':
              $iconBg = 'bg-green-500';
              $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 941 444" fill="none"><path d="M874.783 84.5038C870.904 78.9625 864.532 75.6378 857.882 75.6378H729.048V20.7796C729.048 9.42009 719.628 0 708.269 0H241.42C230.061 0 220.641 9.42009 220.641 20.7796V356.578C220.641 367.938 230.061 377.358 241.42 377.358H300.157C310.963 415.038 345.872 443.021 386.878 443.021C427.883 443.021 462.792 415.315 473.598 377.358H703.836C714.641 415.038 749.551 443.021 790.556 443.021C831.561 443.021 866.471 415.315 877.276 377.358H920.221C931.58 377.358 941 367.938 941 356.578V185.631C941 181.198 939.615 177.042 937.122 173.718L874.783 84.5038ZM899.718 191.727H806.626V117.474H847.354L899.718 191.727ZM262.2 41.8363H687.489V336.076H475.537C467.502 294.239 430.93 262.654 386.878 262.654C342.825 262.654 305.976 294.239 298.218 336.076H262.2V41.8363ZM386.878 401.462C360.003 401.462 338.115 379.574 338.115 352.699C338.115 325.824 360.003 303.937 386.878 303.937C413.752 303.937 435.64 325.824 435.64 352.699C435.64 379.574 413.752 401.462 386.878 401.462ZM790.833 401.462C763.958 401.462 742.07 379.574 742.07 352.699C742.07 325.824 763.958 303.937 790.833 303.937C817.708 303.937 839.596 325.824 839.596 352.699C839.596 379.574 817.708 401.462 790.833 401.462ZM879.493 336.076C871.458 294.239 834.886 262.654 790.833 262.654C767.006 262.654 745.118 272.074 729.048 287.313V117.474H764.789V212.506C764.789 223.866 774.21 233.286 785.569 233.286H899.995V336.076H879.493Z" fill="white"/><path d="M20.8792 41.8362H169.107C180.467 41.8362 189.887 32.4161 189.887 21.0566C189.887 9.69707 180.467 0.276978 169.107 0.276978H20.8792C9.5197 0.276978 0.0996094 9.69707 0.0996094 21.0566C0.0996094 32.4161 9.5197 41.8362 20.8792 41.8362Z" fill="white"/><path d="M169.111 75.9149H54.9613C43.6017 75.9149 34.1816 85.335 34.1816 96.6945C34.1816 108.054 43.6017 117.474 54.9613 117.474H169.111C180.47 117.474 189.89 108.054 189.89 96.6945C189.89 85.335 180.747 75.9149 169.111 75.9149Z" fill="white"/><path d="M169.107 151.553H97.0706C85.7111 151.553 76.291 160.973 76.291 172.332C76.291 183.692 85.7111 193.112 97.0706 193.112H169.107C180.466 193.112 189.886 183.692 189.886 172.332C189.886 160.973 180.743 151.553 169.107 151.553Z" fill="white"/><path d="M169.108 227.19H144.172C132.813 227.19 123.393 236.611 123.393 247.97C123.393 259.33 132.813 268.75 144.172 268.75H169.108C180.467 268.75 189.887 259.33 189.887 247.97C189.887 236.611 180.744 227.19 169.108 227.19Z" fill="white"/></svg>';
              $cardBg = 'bg-green-50/50';
              $cardBorder = 'border-green-200';
              break;
            case 'out_for_delivery':
              $iconBg = 'bg-blue-500';
              $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 941 444" fill="none"><path d="M874.783 84.5038C870.904 78.9625 864.532 75.6378 857.882 75.6378H729.048V20.7796C729.048 9.42009 719.628 0 708.269 0H241.42C230.061 0 220.641 9.42009 220.641 20.7796V356.578C220.641 367.938 230.061 377.358 241.42 377.358H300.157C310.963 415.038 345.872 443.021 386.878 443.021C427.883 443.021 462.792 415.315 473.598 377.358H703.836C714.641 415.038 749.551 443.021 790.556 443.021C831.561 443.021 866.471 415.315 877.276 377.358H920.221C931.58 377.358 941 367.938 941 356.578V185.631C941 181.198 939.615 177.042 937.122 173.718L874.783 84.5038ZM899.718 191.727H806.626V117.474H847.354L899.718 191.727ZM262.2 41.8363H687.489V336.076H475.537C467.502 294.239 430.93 262.654 386.878 262.654C342.825 262.654 305.976 294.239 298.218 336.076H262.2V41.8363ZM386.878 401.462C360.003 401.462 338.115 379.574 338.115 352.699C338.115 325.824 360.003 303.937 386.878 303.937C413.752 303.937 435.64 325.824 435.64 352.699C435.64 379.574 413.752 401.462 386.878 401.462ZM790.833 401.462C763.958 401.462 742.07 379.574 742.07 352.699C742.07 325.824 763.958 303.937 790.833 303.937C817.708 303.937 839.596 325.824 839.596 352.699C839.596 379.574 817.708 401.462 790.833 401.462ZM879.493 336.076C871.458 294.239 834.886 262.654 790.833 262.654C767.006 262.654 745.118 272.074 729.048 287.313V117.474H764.789V212.506C764.789 223.866 774.21 233.286 785.569 233.286H899.995V336.076H879.493Z" fill="white"/><path d="M20.8792 41.8362H169.107C180.467 41.8362 189.887 32.4161 189.887 21.0566C189.887 9.69707 180.467 0.276978 169.107 0.276978H20.8792C9.5197 0.276978 0.0996094 9.69707 0.0996094 21.0566C0.0996094 32.4161 9.5197 41.8362 20.8792 41.8362Z" fill="white"/><path d="M169.111 75.9149H54.9613C43.6017 75.9149 34.1816 85.335 34.1816 96.6945C34.1816 108.054 43.6017 117.474 54.9613 117.474H169.111C180.47 117.474 189.89 108.054 189.89 96.6945C189.89 85.335 180.747 75.9149 169.111 75.9149Z" fill="white"/><path d="M169.107 151.553H97.0706C85.7111 151.553 76.291 160.973 76.291 172.332C76.291 183.692 85.7111 193.112 97.0706 193.112H169.107C180.466 193.112 189.886 183.692 189.886 172.332C189.886 160.973 180.743 151.553 169.107 151.553Z" fill="white"/><path d="M169.108 227.19H144.172C132.813 227.19 123.393 236.611 123.393 247.97C123.393 259.33 132.813 268.75 144.172 268.75H169.108C180.467 268.75 189.887 259.33 189.887 247.97C189.887 236.611 180.744 227.19 169.108 227.19Z" fill="white"/></svg>';
              $cardBg = 'bg-blue-50/50';
              $cardBorder = 'border-blue-200';
              break;
            case 'paid':
              $iconBg = 'bg-blue-500';
              $iconSvg = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" /></svg>';
              $cardBg = 'bg-blue-50/50';
              $cardBorder = 'border-blue-200';
              break;
            case 'pending':
              $iconBg = 'bg-yellow-400';
              $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                          </svg>';
              $cardBg = 'bg-yellow-50/50';
              $cardBorder = 'border-yellow-200';
              break;
            case 'cancelled':
              $iconBg = 'bg-red-500';
              $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>';
              $cardBg = 'bg-red-50/50';
              $cardBorder = 'border-red-200';
              break;
            default:
              $iconBg = 'bg-gray-400';
              $iconSvg = '';
              $cardBg = 'bg-gray-50/50';
              $cardBorder = 'border-gray-200';
          }
        ?>
          <div class="border rounded-2xl p-6 shadow-sm relative transition-all duration-300 hover:shadow-md <?php echo "$cardBg $cardBorder"; ?>">
            <!-- Unread indicator -->
            <?php if (!$notif['is_read']): ?>
              <div class="absolute top-4 right-4 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
            <?php endif; ?>
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 <?php echo $iconBg; ?> rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                <?php echo $iconSvg; ?>
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($notif['title'] ?? $notif['type']) ?></h3>
                <p class="text-gray-600 mb-2"><?= htmlspecialchars($notif['message']) ?></p>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-500"><?= timeAgo($notif['created_at']) ?></span>
                  <?php if (!$notif['is_read']): ?>
                    <form method="post" class="inline-block" onsubmit="return confirmMarkRead()">
                      <input type="hidden" name="mark_read_id" value="<?= $notif['id'] ?>">
                      <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition-colors">
                        Mark Read
                      </button>
                    </form>
                  <?php else: ?>
                    <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">Read</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <!-- Empty state -->
        <div class="text-center py-16">
          <div class="w-24 h-24 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2zm6-6V11c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v0.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29A1 1 0 006 20h12a1 1 0 00.71-1.71L18 16z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Notifikasi</h3>
          <p class="text-gray-600 max-w-md mx-auto">Semua notifikasi penting tentang pesanan, pembayaran, dan update akun akan muncul di sini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
// Mencegah form resubmit confirmation
function confirmMarkRead() {
  // Tambahkan loading state atau feedback visual jika diperlukan
  return true;
}

function confirmMarkAllRead() {
  return confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai sudah dibaca?');
}

// Mencegah form resubmit saat back/forward browser
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
</script>

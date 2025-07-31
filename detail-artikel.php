<?php
require_once 'config/db.php';
session_start();

$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Hapus komentar jika ada POST hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $userId = $_SESSION['user_id'] ?? 0;
    $commentId = intval($_POST['comment_id'] ?? 0);
    if ($userId && $commentId) {
        $stmt = $conn->prepare("DELETE FROM article_comments WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $commentId, $userId);
        $stmt->execute();
    }
}

// Edit komentar jika ada POST edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])) {
    $userId = $_SESSION['user_id'] ?? 0;
    $commentId = intval($_POST['comment_id'] ?? 0);
    $commentText = trim($_POST['comment'] ?? '');
    if ($userId && $commentId && $commentText) {
        $stmt = $conn->prepare("UPDATE article_comments SET comment = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param('sii', $commentText, $commentId, $userId);
        $stmt->execute();
    }
}

$article = null;

if ($articleId) {
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param('i', $articleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
}

// Ambil komentar artikel
$comments = [];
if ($articleId) {
    $stmt = $conn->prepare("SELECT c.*, u.name AS user_name FROM article_comments c LEFT JOIN users u ON c.user_id = u.id WHERE c.article_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param('i', $articleId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}
?>
<?php
function timeAgo($datetime) {
    if (!$datetime) return '-';
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) return 'baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari yang lalu';
    return date('d M Y H:i', $timestamp);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article ? htmlspecialchars($article['title']) : 'Artikel Tidak Ditemukan'; ?> - GreenNest</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
  </head>
<body class="min-h-screen ">
    <?php include('component/navbar.php'); ?>
    <main class="pt-16 bg-white">
        <section id="content" class="pt-14 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php if ($article): ?>
                    <!-- Gambar Header -->
                    <img src="<?php echo htmlspecialchars($article['image_url'] ?? ''); ?>" alt="Header Artikel" class="w-full h-64 object-cover mb-8 rounded-lg" />

                    <!-- Metadata -->
                    <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                        <span class="bg-primary text-white px-3 py-1 rounded-full text-sm"><?php echo htmlspecialchars($article['category'] ?? ''); ?></span>
                        <span><?php echo date('d M Y', strtotime($article['created_at'])); ?></span>
                    </div>

                    <!-- Judul Artikel -->
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight mb-6"><?php echo htmlspecialchars($article['title']); ?></h1>

                    <!-- Isi Artikel -->
                    <div class="space-y-6 text-gray-700 leading-relaxed text-justify">
                        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Artikel tidak ditemukan</h3>
                        <p class="text-gray-600 mb-4">Artikel yang Anda cari tidak tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
             <hr class="my-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" />
        <!-- Artikel Terkait Section -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">Artikel Terkait</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
              // Ambil 3 artikel terkait berdasarkan kategori yang sama, kecuali artikel yang sedang dibuka
              $related = [];
              if ($article && !empty($article['category'])) {
                $stmt = $conn->prepare("SELECT * FROM articles WHERE category = ? AND id != ? ORDER BY created_at DESC LIMIT 3");
                $stmt->bind_param('si', $article['category'], $article['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                  $related[] = $row;
                }
              }
            ?>
            <?php if (!empty($related)): ?>
              <?php foreach ($related as $rel): ?>
                <div class="bg-white overflow-hidden transition-transform hover:scale-[1.02] group">
                  <a href="detail-artikel.php?id=<?php echo $rel['id']; ?>">
                    <div class="overflow-hidden">
                      <img src="<?php echo htmlspecialchars($rel['image_url'] ?? ''); ?>" alt="Gambar Artikel" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />
                    </div>
                  </a>
                  <div class="py-4 space-y-2">
                    <div class="flex justify-between items-center text-sm text-gray-500">
                      <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs"><?php echo htmlspecialchars($rel['category'] ?? ''); ?></span>
                      <span><?php echo date('d M Y', strtotime($rel['created_at'])); ?></span>
                    </div>
                    <a href="detail-artikel.php?id=<?php echo $rel['id']; ?>">
                      <h3 class="text-base font-medium text-gray-800 hover:text-primary transition-colors"><?php echo htmlspecialchars($rel['title'] ?? ''); ?></h3>
                    </a>
                    <p class="text-sm text-gray-600 leading-relaxed"><?php echo htmlspecialchars($rel['excerpt'] ?? ''); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-span-3 text-center py-8 text-gray-500">
                Tidak ada artikel terkait.
              </div>
            <?php endif; ?>
          </div>
        </section>
        
        <hr class="my-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" />

        <!-- Komentar Artikel Section -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" id="comments">
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">Komentar Artikel</h2>

          <!-- Form Komentar -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <form action="submit-article-comment.php" method="POST" class="mb-8 space-y-4">
              <input type="hidden" name="article_id" value="<?php echo $articleId; ?>">
              <textarea name="comment" rows="3" class="w-full border rounded-lg px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Tulis komentar Anda..." required></textarea>
              <button type="submit" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-opacity-90 transition">Kirim Komentar</button>
            </form>
          <?php else: ?>
            <div class="mb-8 text-gray-500">
    Silakan
    <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="text-primary underline">login</a>
    untuk menulis komentar.
  </div>
          <?php endif; ?>

          <!-- Daftar Komentar -->
          <div class="space-y-6">
            <?php if (empty($comments)): ?>
              <p class="text-gray-400 italic">Belum ada komentar untuk artikel ini.</p>
            <?php else: ?>
              <?php
              $userId = $_SESSION['user_id'] ?? 0;
              foreach ($comments as $comment): ?>
                <?php
                  $date = !empty($comment['created_at']) ? date('d/m/Y H:i', strtotime($comment['created_at'])) : '-';
                  $initial = strtoupper(substr($comment['user_name'] ?? 'A', 0, 1));
                  $isOwner = ($userId && $userId == ($comment['user_id'] ?? 0));
                ?>
                <div class="bg-white rounded-xl shadow-md p-5 flex gap-4 items-start group hover:shadow-lg transition relative">
                  <!-- Avatar -->
                  <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-semibold text-xl">
                    <?php echo $initial; ?>
                  </div>
                  <!-- Comment Content -->
                  <div class="flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-1">
                      <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($comment['user_name'] ?? 'Anon'); ?></span>
                      <?php if ($isOwner): ?>
                        <!-- Meatball Button -->
                        <div class="relative inline-block text-left">
                          <button type="button" class="meatball-btn p-2 rounded-full hover:bg-gray-100 focus:outline-none" onclick="toggleDropdown(this)">
                            <svg width="20" height="20" fill="currentColor" class="text-gray-500" viewBox="0 0 20 20">
                              <circle cx="4" cy="10" r="2"/>
                              <circle cx="10" cy="10" r="2"/>
                              <circle cx="16" cy="10" r="2"/>
                            </svg>
                          </button>
                          <div class="dropdown-menu absolute right-0 mt-2 w-28 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                            <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openEditModal(<?php echo $comment['id']; ?>, '<?php echo htmlspecialchars(addslashes($comment['comment'])); ?>'); closeAllDropdowns();">Edit</button>
                            <form method="POST" onsubmit="return confirm('Hapus komentar ini?')" class="block">
                              <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                              <input type="hidden" name="delete_comment" value="1">
                              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Hapus</button>
                            </form>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="text-gray-700 leading-relaxed mb-2"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                    <!-- Date/Time pojok kanan bawah -->
                    <div class="flex justify-end">
                      <span class="text-xs text-gray-400"><?php echo timeAgo($comment['created_at'] ?? null); ?></span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

        <!-- Modal Edit Komentar -->
        <div id="editCommentModal" class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50 hidden">
          <form method="POST" class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg space-y-4">
            <input type="hidden" name="comment_id" id="editCommentId">
            <input type="hidden" name="edit_comment" value="1">
            <label class="block font-semibold mb-2">Edit Komentar</label>
            <textarea name="comment" id="editCommentText" rows="4" class="w-full border rounded-lg px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary outline-none transition-all" required></textarea>
            <div class="flex justify-end gap-2">
              <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</button>
              <button type="submit" class="px-4 py-2 rounded bg-primary text-white hover:bg-opacity-90">Simpan</button>
            </div>
          </form>
        </div>

        <script>
        function toggleDropdown(btn) {
          closeAllDropdowns();
          const menu = btn.parentElement.querySelector('.dropdown-menu');
          if (menu) menu.classList.toggle('hidden');
        }
        function closeAllDropdowns() {
          document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        }
        document.addEventListener('click', function(e) {
          if (!e.target.closest('.meatball-btn') && !e.target.closest('.dropdown-menu')) {
            closeAllDropdowns();
          }
        });
        function openEditModal(id, comment) {
          document.getElementById('editCommentId').value = id;
          document.getElementById('editCommentText').value = comment;
          document.getElementById('editCommentModal').classList.remove('hidden');
        }
        function closeEditModal() {
          document.getElementById('editCommentModal').classList.add('hidden');
        }
        document.addEventListener('DOMContentLoaded', function() {
  // Cek apakah user belum login
  <?php if (!isset($_SESSION['user_id'])): ?>
    const commentButton = document.querySelector('button[type="submit"].bg-primary');
    if (commentButton) {
      commentButton.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'login.php';
      });
    }
  <?php endif; ?>
});
        </script>
    </main>
    <?php include('component/footer.php'); ?>

    <script src="./src/script.js"></script>
</body>
</html>

<?php
require_once 'config/db.php';

$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$article = null;

if ($articleId) {
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param('i', $articleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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
                      <h3 class="text-base font-semibold text-gray-800 hover:text-primary transition-colors"><?php echo htmlspecialchars($rel['title'] ?? ''); ?></h3>
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
    </main>
    <?php include('component/footer.php'); ?>
</body>
</html>

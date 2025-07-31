<?php
require_once __DIR__ . '/../config/db.php';
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// PAGINATION SETUP
$perPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// Hitung total review
$totalReviews = 0;
if ($productId) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM reviews WHERE product_id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $totalReviews = intval($row['total'] ?? 0);
}

// Ambil review untuk halaman ini
$reviews = [];
if ($productId) {
    $sql = "SELECT r.*, u.name AS user_name 
            FROM reviews r 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $productId, $perPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['rating_text'] = ($row['rating'] >= 4) ? 'Very Good' : (($row['rating'] == 3) ? 'Good' : 'Fair');
        $reviews[] = $row;
    }
}

// Ambil semua gambar review produk (maksimal 10 terbaru)
$reviewImages = [];
if ($productId) {
    $imgSql = "SELECT image_url FROM reviews WHERE product_id = ? AND image_url IS NOT NULL AND image_url != '' ORDER BY created_at DESC LIMIT 10";
    $imgStmt = $conn->prepare($imgSql);
    $imgStmt->bind_param('i', $productId);
    $imgStmt->execute();
    $imgResult = $imgStmt->get_result();
    while ($imgRow = $imgResult->fetch_assoc()) {
        $reviewImages[] = $imgRow['image_url'];
    }
}

// Hitung overall rating
$overallRating = 0;
if ($productId) {
    $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $overallRating = round($row['avg_rating'] ?? 0, 1);
}
?>
<!-- section comment and ratings -->
        <section class="mx-auto container px-2 md:px-16 py-16">
          <div class="md:flex md:flex-col gap-8">
            <!-- Rating Header -->
            <div class="md:flex items-start justify-between flex-row md:flex-wrap">
              <!-- Column 1: Overall Rating -->
              <div class="md:flex md:space-y-0 space-y-5 gap-6 w-full sm:w-auto">
                <div>
                  <div class="flex items-center gap-2">
                    <h1 class="text-3xl font-medium">
                      <?php echo number_format($overallRating, 1); ?>
                    </h1>
                    <!-- Rating stars -->
                    <div class="flex items-center space-x-1">
                      <?php
                        $fullStars = floor($overallRating);
                        $emptyStars = 5 - $fullStars;
                      ?>
                      <?php for ($i = 0; $i < $fullStars; $i++): ?>
                        <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                      <?php endfor; ?>
                      <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                      <?php endfor; ?>
                    </div>
                  </div>
                  <p class="text-black text-sm">Based on <?php echo $totalReviews; ?> Reviews</p>
                </div>

                <!-- Column 2: Rating Bars -->
     

                <!-- Column 3: Review Images -->
                <div class="flex items-start space-x-3 md:space-x-2 w-full sm:w-auto mt-4 sm:mt-0">
                  <?php if (empty($reviewImages)): ?>
                    <span class="text-gray-400 italic">Belum ada gambar review.</span>
                  <?php else: ?>
                    <?php foreach (array_slice($reviewImages, 0, 2) as $idx => $img): ?>
                      <img
                        class="md:w-20 md:h-14 w-28 object-cover cursor-pointer review-img-thumb rounded-lg"
                        src="<?php echo htmlspecialchars($img); ?>"
                        alt="Review Image <?php echo $idx+1; ?>"
                        data-img="<?php echo htmlspecialchars($img); ?>"
                      />
                    <?php endforeach; ?>
                    <?php if (count($reviewImages) > 2): ?>
                      <?php
                        $lastThumb = $reviewImages[2];
                        $total = count($reviewImages);
                      ?>
                      <div class="relative md:w-20 md:h-14 w-28 cursor-pointer group rounded-lg overflow-hidden" id="seeAllImagesBtn">
                        <img src="<?php echo htmlspecialchars($lastThumb); ?>" alt="See All Review Images" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-200" />
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                          <span class="text-white font-normal text-lg md:text-xl drop-shadow-lg"><?php echo $total; ?>+</span>
                        </div>
                        <div class="absolute inset-0 rounded-lg border-2 border-secondary opacity-0 group-hover:opacity-100 transition-opacity"></div>
                      </div>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Write Review Button -->
              <div class="mt-6 md:mt-0 flex justify-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                  <button id="showModal" class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 transition-colors">Write a Review</button>
                <?php else: ?>
                  <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 transition-colors text-center">Write a Review</a>
                <?php endif; ?>
              </div>
            </div>

            <div class="flex items-center text-center border-b-2 justify-start space-x-2 text-secondary py-2 border-secondary w-[90px]">
              <p>Reviews</p>
              <p><?php echo $totalReviews; ?></p>
            </div>

            <!-- Review Comments Section -->
            <div id="reviews" class="mt-8">
              <?php
              if (empty($reviews)) {
                echo '<p class="text-gray-500 italic">Belum ada review untuk produk ini.</p>';
              } else {
                foreach ($reviews as $review) {
                  include(__DIR__ . '/card-comment.php');
                }
              }
              ?>
              <!-- PAGINATION -->
              <?php
                $totalPages = ceil($totalReviews / $perPage);
                if ($totalPages > 1):
              ?>
              <div class="flex items-center justify-center md:justify-center gap-x-2 md:gap-x-3 mt-8 md:mt-10 lg:mt-12">
                <!-- Prev Button -->
                <a href="?id=<?php echo $productId; ?>&page=<?php echo max(1, $page-1); ?>#reviews" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md <?php echo $page == 1 ? 'pointer-events-none opacity-50' : ''; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
                    <path d="M8.75 16.5L1.25 9L8.75 1.5" stroke="#0F0F0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </a>
                <!-- Page Numbers -->
                <?php
                  $start = max(1, $page - 2);
                  $end = min($totalPages, $page + 2);
                  if ($start > 1) {
                    echo '<a href="?id='.$productId.'&page=1#reviews" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">1</a>';
                    if ($start > 2) echo '<span class="px-2">...</span>';
                  }
                  for ($i = $start; $i <= $end; $i++) {
                    if ($i == $page) {
                      echo '<span class="bg-primary text-white font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">'.$i.'</span>';
                    } else {
                      echo '<a href="?id='.$productId.'&page='.($i).'#reviews" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">'.$i.'</a>';
                    }
                  }
                  if ($end < $totalPages) {
                    if ($end < $totalPages - 1) echo '<span class="px-2">...</span>';
                    echo '<a href="?id='.$productId.'&page='.$totalPages.'#reviews" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">'.$totalPages.'</a>';
                  }
                ?>
                <!-- Next Button -->
                <a href="?id=<?php echo $productId; ?>&page=<?php echo min($totalPages, $page+1); ?>#reviews" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md <?php echo $page == $totalPages ? 'pointer-events-none opacity-50' : ''; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
                    <path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="#0F0F0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </a>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </section>

        <!-- Modal Popup -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
<div class="bg-white rounded-2xl p-0 w-full max-w-lg mx-4 shadow-2xl relative overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Header -->
        <div class="bg-primary px-8 py-6 text-white relative">
            <button id="closeReviewModal" class="absolute top-4 right-4 text-white hover:text-gray-200 text-2xl font-light transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20">
                ×
            </button>
            <h2 class="text-2xl font-semibold">Write a Review</h2>
            <p class="text-white text-opacity-80 text-sm mt-1">Share your experience with others</p>
        </div>

        <!-- Form Content -->
        <div class="px-8 py-6">
            <form action="submit-review.php#reviews" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                
                <!-- Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 outline-none transition-all duration-200 bg-gray-50 hover:bg-white" required>
                        <option value="">Choose your rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                        <option value="4">⭐⭐⭐⭐ Good</option>
                        <option value="3">⭐⭐⭐ Fair</option>
                        <option value="2">⭐⭐ Poor</option>
                        <option value="1">⭐ Bad</option>
                    </select>
                </div>

                <!-- Comment -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea name="comment" 
                              class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 outline-none transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                              rows="4" 
                              placeholder="Tell us about your experience..."
                              required></textarea>
                </div>

                <!-- Image Upload -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Add Photo <span class="text-gray-400 text-xs">(optional)</span></label>
                    <div class="relative">
                        <input type="file" 
                               name="image_url" 
                               accept="image/*" 
                               class="w-full border-2 border-dashed border-gray-300 rounded-xl px-4 py-6 text-center cursor-pointer hover:border-primary hover:bg-gray-50 transition-all duration-200 file:hidden"
                               id="imageInput">
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-gray-500">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm">Click to upload image</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-primary text-white px-6 py-4 rounded-xl hover:bg-opacity-90 transform hover:scale-105 transition-all duration-200 font-medium text-lg shadow-lg hover:shadow-xl">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        const modal = document.getElementById('reviewModal');
        const modalContent = document.getElementById('modalContent');
        const showModalBtn = document.getElementById('showModal');
        const closeModalBtn = document.getElementById('closeReviewModal');

        function showModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        showModalBtn.addEventListener('click', showModal);
        closeModalBtn.addEventListener('click', hideModal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                hideModal();
            }
        });

        // File input enhancement
        const fileInput = document.getElementById('imageInput');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = e.target.parentElement.querySelector('div');
                label.innerHTML = `
                    <svg class="w-8 h-8 mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-green-600">${fileName}</span>
                `;
            }
        });
    </script>
<!-- Modal Single Image -->
<div id="reviewImgModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="relative bg-transparent">
    <button id="closeReviewImgModal" class="absolute top-2 right-2 text-white text-2xl font-bold z-10">&times;</button>
    <img id="reviewImgModalSrc" src="" alt="Review Image" class="max-w-full max-h-[80vh] rounded-lg shadow-lg" />
  </div>
</div>

<!-- Modal All Images Gallery -->
<div id="reviewGalleryModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg p-6 w-full max-w-2xl relative">
    <button id="closeReviewGalleryModal" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
    <h2 class="text-lg font-bold mb-4">Semua Gambar Review</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <?php foreach ($reviewImages as $img): ?>
        <img src="<?php echo htmlspecialchars($img); ?>" alt="Review Gallery" class="w-full h-32 object-cover rounded cursor-pointer review-gallery-thumb" />
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php if (!isset($_SESSION['user_id'])): ?>
    const showModalBtn = document.getElementById('showModal');
    if (showModalBtn) {
      showModalBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'login.php';
      });
    }
  <?php endif; ?>

  let galleryWasOpen = false;

  // Modal single image
  document.querySelectorAll('.review-img-thumb').forEach(img => {
    img.onclick = function() {
      document.getElementById('reviewImgModalSrc').src = img.getAttribute('data-img');
      document.getElementById('reviewImgModal').classList.remove('hidden');
      galleryWasOpen = false;
    };
  });
  document.getElementById('closeReviewImgModal').onclick = function() {
    document.getElementById('reviewImgModal').classList.add('hidden');
    if (galleryWasOpen) {
      document.getElementById('reviewGalleryModal').classList.remove('hidden');
      galleryWasOpen = false;
    }
  };
  document.getElementById('reviewImgModal').onclick = function(e) {
    if (e.target === this) {
      document.getElementById('reviewImgModal').classList.add('hidden');
      if (galleryWasOpen) {
        document.getElementById('reviewGalleryModal').classList.remove('hidden');
        galleryWasOpen = false;
      }
    }
  };

  // Modal gallery
  document.querySelectorAll('#seeAllImagesBtn').forEach(btn => {
    btn.onclick = function() {
      document.getElementById('reviewGalleryModal').classList.remove('hidden');
    };
  });
  document.getElementById('closeReviewGalleryModal').onclick = function() {
    document.getElementById('reviewGalleryModal').classList.add('hidden');
  };
  document.getElementById('reviewGalleryModal').onclick = function(e) {
    if (e.target === this) this.classList.add('hidden');
  };

  // Click gallery image to show single image modal
  document.querySelectorAll('.review-gallery-thumb').forEach(img => {
    img.onclick = function() {
      document.getElementById('reviewGalleryModal').classList.add('hidden');
      document.getElementById('reviewImgModalSrc').src = img.src;
      document.getElementById('reviewImgModal').classList.remove('hidden');
      galleryWasOpen = true;
    };
  });
});
</script>


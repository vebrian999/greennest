<?php
// Pastikan variabel $review sudah dikirim dari parent (comment-section.php)
if (!isset($review)) return;

$userId = $_SESSION['user_id'] ?? 0;
$isOwner = ($userId && $userId == ($review['user_id'] ?? 0));
$reviewId = $review['id'];
?>
<div class="space-y-2.5 relative">
  <div class="flex justify-between">
    <div class="flex items-center space-x-2">
      <p><?php echo htmlspecialchars($review['user_name'] ?? 'Anon'); ?></p>
      <p class="text-secondary">Verified Buyer</p>
    </div>
    <div class="flex items-center space-x-2">
      <p><?php echo date('d/m/Y', strtotime($review['created_at'] ?? '')); ?></p>
      <?php if ($isOwner): ?>
        <!-- Kebab menu -->
        <div class="relative inline-block text-left">
          <button type="button" class="kebab-btn" data-review="<?php echo $reviewId; ?>">
            <svg class="w-6 h-6 text-gray-500 hover:text-black" fill="none" viewBox="0 0 24 24">
              <circle cx="5" cy="12" r="2" fill="currentColor"/>
              <circle cx="12" cy="12" r="2" fill="currentColor"/>
              <circle cx="19" cy="12" r="2" fill="currentColor"/>
            </svg>
          </button>
          <div class="kebab-menu absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg z-50 hidden" id="kebab-menu-<?php echo $reviewId; ?>">
            <button class="block w-full text-left px-4 py-2 hover:bg-gray-100 edit-btn" data-review="<?php echo $reviewId; ?>">Edit</button>
            <form action="delete-review.php" method="POST" onsubmit="return confirm('Yakin hapus review ini?');">
              <input type="hidden" name="review_id" value="<?php echo $reviewId; ?>">
              <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Hapus</button>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="flex space-x-2">
    <!-- Rating stars -->
    <div class="flex items-center space-x-1">
      <?php
      $rating = intval($review['rating'] ?? 0);
      for ($i = 1; $i <= 5; $i++) {
        $color = $i <= $rating ? 'text-secondary' : 'text-gray-300';
        echo '<svg class="w-5 h-5 ' . $color . '" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
      }
      ?>
    </div>
    <p><?php echo htmlspecialchars($review['rating_text'] ?? ''); ?></p>
  </div>
  <div>
    <p><?php echo htmlspecialchars($review['comment'] ?? ''); ?></p>
  </div>
  <?php if (!empty($review['image_url'])): ?>
  <div>
    <img src="<?php echo htmlspecialchars($review['image_url']); ?>" alt="image comment" class="w-20 h-32 object-cover" />
  </div>
  <?php endif; ?>

<!-- Modal Edit Review -->
<div class="fixed -inset-2.5 bg-black bg-opacity-50 flex items-center justify-center z-[9999] hidden backdrop-blur-sm" id="edit-modal-<?php echo $reviewId; ?>">
    <div class="bg-white rounded-2xl p-0 w-full max-w-lg mx-4 shadow-2xl relative overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Header -->
        <div class="bg-primary px-8 py-6 text-white relative">
            <button type="button" class="close-edit-modal absolute top-4 right-4 text-white hover:text-gray-200 text-2xl font-light transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20" data-review="<?php echo $reviewId; ?>">
                ×
            </button>
            <h2 class="text-2xl font-semibold">Edit Review</h2>
            <p class="text-white text-opacity-80 text-sm mt-1">Update your experience</p>
        </div>

        <!-- Form Content -->
        <div class="px-8 py-6">
            <form action="edit-review.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="review_id" value="<?php echo $reviewId; ?>">
                
                <!-- Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 outline-none transition-all duration-200 bg-gray-50 hover:bg-white" required>
                        <option value="">Choose your rating</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo $review['rating'] == $i ? 'selected' : ''; ?>>
                                <?php 
                                    $stars = str_repeat('⭐', $i);
                                    $labels = [5 => 'Excellent', 4 => 'Good', 3 => 'Fair', 2 => 'Poor', 1 => 'Bad'];
                                    echo $stars . ' ' . $labels[$i];
                                ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Comment -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea name="comment" 
                              class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 outline-none transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                              rows="4" 
                              placeholder="Tell us about your experience..."
                              required><?php echo htmlspecialchars($review['comment']); ?></textarea>
                </div>

                <!-- Image Upload -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Add Photo <span class="text-gray-400 text-xs">(optional)</span></label>
                    <div class="relative">
                        <input type="file" 
                               name="image_url" 
                               accept="image/*" 
                               class="w-full border-2 border-dashed border-gray-300 rounded-xl px-4 py-6 text-center cursor-pointer hover:border-primary hover:bg-gray-50 transition-all duration-200 file:hidden">
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
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<hr class="w-full h-0.5 bg-[#F0F8E7] my-10" />

<script>
document.querySelectorAll('.kebab-btn').forEach(btn => {
  btn.onclick = function(e) {
    e.stopPropagation();
    const id = btn.getAttribute('data-review');
    document.getElementById('kebab-menu-' + id).classList.toggle('hidden');
    // Close other kebab menus
    document.querySelectorAll('.kebab-menu').forEach(menu => {
      if (menu.id !== 'kebab-menu-' + id) menu.classList.add('hidden');
    });
  };
});

// Hide kebab menu when clicking outside
document.addEventListener('click', function(e) {
  document.querySelectorAll('.kebab-menu').forEach(menu => {
    if (!menu.classList.contains('hidden')) menu.classList.add('hidden');
  });
});

// Edit button
document.querySelectorAll('.edit-btn').forEach(btn => {
  btn.onclick = function(e) {
    e.preventDefault();
    const id = btn.getAttribute('data-review');
    const modal = document.getElementById('edit-modal-' + id);
    const modalContent = modal.querySelector('.bg-white');
    modal.classList.remove('hidden');
    // Animasi muncul
    setTimeout(() => {
      modalContent.classList.remove('scale-95', 'opacity-0');
      modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    document.getElementById('kebab-menu-' + id).classList.add('hidden');
  };
});

// Close edit modal
document.querySelectorAll('.close-edit-modal').forEach(btn => {
  btn.onclick = function() {
    const id = btn.getAttribute('data-review');
    const modal = document.getElementById('edit-modal-' + id);
    const modalContent = modal.querySelector('.bg-white');
    // Animasi tutup
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  };
});

// Optional: close modal when clicking outside
document.querySelectorAll('[id^="edit-modal-"]').forEach(modal => {
  modal.onclick = function(e) {
    if (e.target === modal) {
      const modalContent = modal.querySelector('.bg-white');
      modalContent.classList.remove('scale-100', 'opacity-100');
      modalContent.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
    }
  };
});
</script>
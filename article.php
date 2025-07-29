<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - GreenNest</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen ">
    <?php include('component/navbar.php'); ?>
        <main class="pt-16">
                       <section class="">
      <div class="relative h-[625px] md:h-[560px] text-white overflow-hidden">
        <div class="absolute inset-0">
          <img src="./src/img/hero-image (2).png" alt="Background Image" class="object-cover object-center w-full h-full" />
          <div class="absolute inset-0 blur-md bg-[#8E8E8E33] opacity-50"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4 md:px-0">
          <h1 class="text-3xl md:text-5xl leading-tight mb-4 px-4 md:px-0">Explore Career & Inspiration Articles</h1>
          <p class="text-base md:text-xl px-4 md:px-72 text-[#F7F6F8] mb-8">Find tips, guides, and inspiration to help you grow your career, ace interviews, and stay motivated. Discover curated articles for every job seeker and professional.</p>
          <a href="#" id="scrollToArticles" class="bg-secondary text-white py-2 md:py-2.5 px-4 md:px-6 rounded-full text-base md:text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">Lihat Artikel</a>
        </div>
      </div>
    </section>

          <div id="content" class="mx-auto container px-2 md:px-16">


        <!-- List blog Section start -->
        <section id="article-list" class="py-16">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h1 class="md:text-2xl text-xl">Daftar Artikel Semua</h1>
            </div>
            <!-- nanti dibawah ini buat ikut database sesuai profesi yang ada pada database. -->
            <!-- Tombol & Search Bar -->
            <div class="flex flex-wrap items-center gap-2" id="searchWrapper">
              <!-- Input Search (awal hidden, akan geser tombol lain saat aktif) -->
              <input id="searchInput" type="text" placeholder="Cari artikel..." class="w-0 opacity-0 transition-all duration-300 ease-in-out border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />

              <!-- Tombol Search -->
              <button id="searchToggle" class="flex items-center justify-center w-10 h-10 bg-white border-2 rounded-lg hover:bg-gray-100 transition-all duration-300">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
                </svg>
              </button>

              <!-- Tombol Semua -->
              <button type="button" class="bg-white border-2 py-2 rounded-lg px-4">Terbaru</button>
              <button type="button" class="bg-white border-2 py-2 rounded-lg px-4">Terlama</button>

              <!-- Dropdown Kota / Kabupaten -->
              <div class="relative inline-block">
                <button id="dropdownButton" class="bg-white border-2 py-2 px-4 rounded-lg text-black hover:bg-gray-100 flex items-center">
                  Kategori
                  <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div id="dropdownMenu" class="absolute z-50 mt-2 right-0 w-64 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                  <ul class="py-2 text-left text-gray-700">
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Tips Karir</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Wawancara Kerja</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">CV & Resume</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Lowongan Terbaru</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Motivasi & Inspirasi</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Budaya Kerja</li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Freelance & Remote</li>
                  </ul>
                </div>
              </div>
            </div>

            <script>
              const searchToggle = document.getElementById("searchToggle");
              const searchInput = document.getElementById("searchInput");

              let searchActive = false;

              searchToggle.addEventListener("click", () => {
                if (!searchActive) {
                  searchInput.classList.remove("opacity-0", "w-0");
                  searchInput.classList.add("opacity-100", "w-64", "mr-2");
                  searchInput.focus();
                } else {
                  searchInput.classList.add("opacity-0", "w-0");
                  searchInput.classList.remove("opacity-100", "w-64", "mr-2");
                  searchInput.value = ""; // Optional: kosongkan input
                }
                searchActive = !searchActive;
              });
            </script>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card Artikel Start -->
            <div class="bg-white overflow-hidden">
              <!-- Gambar Artikel dengan hover effect dan link -->
              <div class="overflow-hidden">
                <a href="detail-artikel.html">
                  <img src="./src/img/article-img.png" alt="Gambar Artikel" class="w-full h-48 object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                </a>
              </div>

              <div class="py-6 space-y-2">
                <!-- Kategori dan Tanggal -->
                <div class="flex justify-between items-center text-sm text-gray-500 mb-1">
                  <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs">Desain Grafis</span>
                  <span>24 Juli 2025</span>
                </div>

                <!-- Judul -->
                <h2 class="text-lg font-semibold text-gray-800 hover:text-primary transition-colors duration-200 cursor-pointer">
                  <a href="detail-artikel.html">Pentingnya Visual Branding dalam Bisnis Modern</a>
                </h2>

                <!-- Potongan Artikel -->
                <p class="text-gray-600 text-sm leading-relaxed">Setiap kemasan produk yang menarik, feed media sosial yang tertata rapi, hingga logo brand yang mudah diingat adalah hasil karya seorang graphic designer...</p>
              </div>
            </div>

            <!-- card 2 -->
            <div class="bg-white overflow-hidden">
              <!-- Gambar Artikel dengan hover effect dan link -->
              <div class="overflow-hidden">
                <a href="detail-artikel.html">
                  <img src="./src/img/article-img.png" alt="Gambar Artikel" class="w-full h-48 object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                </a>
              </div>

              <div class="py-6 space-y-2">
                <!-- Kategori dan Tanggal -->
                <div class="flex justify-between items-center text-sm text-gray-500 mb-1">
                  <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs">Desain Grafis</span>
                  <span>24 Juli 2025</span>
                </div>

                <!-- Judul -->
                <h2 class="text-lg font-semibold text-gray-800 hover:text-primary transition-colors duration-200 cursor-pointer">
                  <a href="detail-artikel.html">Pentingnya Visual Branding dalam Bisnis Modern</a>
                </h2>

                <!-- Potongan Artikel -->
                <p class="text-gray-600 text-sm leading-relaxed">Setiap kemasan produk yang menarik, feed media sosial yang tertata rapi, hingga logo brand yang mudah diingat adalah hasil karya seorang graphic designer...</p>
              </div>
            </div>

            <!-- card 3 -->
            <div class="bg-white overflow-hidden">
              <!-- Gambar Artikel dengan hover effect dan link -->
              <div class="overflow-hidden">
                <a href="detail-artikel.html">
                  <img src="./src/img/article-img.png" alt="Gambar Artikel" class="w-full h-48 object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                </a>
              </div>

              <div class="py-6 space-y-2">
                <!-- Kategori dan Tanggal -->
                <div class="flex justify-between items-center text-sm text-gray-500 mb-1">
                  <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs">Desain Grafis</span>
                  <span>24 Juli 2025</span>
                </div>

                <!-- Judul -->
                <h2 class="text-lg font-semibold text-gray-800 hover:text-primary transition-colors duration-200 cursor-pointer">
                  <a href="detail-artikel.html">Pentingnya Visual Branding dalam Bisnis Modern</a>
                </h2>

                <!-- Potongan Artikel -->
                <p class="text-gray-600 text-sm leading-relaxed">Setiap kemasan produk yang menarik, feed media sosial yang tertata rapi, hingga logo brand yang mudah diingat adalah hasil karya seorang graphic designer...</p>
              </div>
            </div>

            <!-- Card End -->
          </div>
        </section>

        <!-- List Card Section end -->
      </div>
    <script>
      document.getElementById('scrollToArticles').addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById('article-list');
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    </script>
    <?php include('component/footer.php'); ?>
</body>
</html>

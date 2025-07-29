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

    <!-- hero section -->
    <section class="md:pt-16">
      <div class="relative h-[625px] md:h-[560px] text-white overflow-hidden">
        <div class="absolute inset-0">
          <img src="./src/img/hero-image (2).png" alt="Background Image" class="object-cover object-center w-full h-full" />
          <div class="absolute inset-0 blur-md bg-[#8E8E8E33] opacity-50"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4 md:px-0">
          <h1 class="text-3xl md:text-5xl leading-tight mb-4 px-4 md:px-0">Grow Your Green Escape</h1>
          <p class="text-base md:text-xl px-4 md:px-72 text-[#F7F6F8] mb-8">Find beginner-friendly plants, expert advice, and stylish pots to transform your space into a lush oasis.</p>
          <a href="#" class="bg-secondary text-white py-2 md:py-2.5 px-4 md:px-6 rounded-full text-base md:text-lg font-normal transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg"> Explore Starter Plants </a>
        </div>
      </div>
    </section>
    <main class="pt-16 lg:px-16 flex gap-8 container px-2 mx-auto">
      <!-- Sidebar -->
      <aside class="fixed inset-0 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 bg-white md:w-64 md:flex-shrink-0 z-40" id="sidebar" aria-label="Sidebar">
       
        <!-- aside component -->
        <?php include './component/aside-filter.php'; ?>

      </aside>
    

      <!-- Main Content -->
      <div id="content" class="flex-1">
        <section>
          <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Card 1 -->
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>
            <?php include './component/card-main.php'; ?>

          </div>
        </section>

        <!-- PAGINATION -->
        <div class="flex items-center justify-center md:justify-center gap-x-2 md:gap-x-3 mt-8 md:mt-10 lg:mt-12">
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
              <path d="M8.75 16.5L1.25 9L8.75 1.5" stroke="#0F0F0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
          <button type="button" class="bg-primary text-white font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">1</button>
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">2</button>
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">3</button>
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">...</button>
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">13</button>
          <button type="button" class="bg-slate-100 text-gray-700 font-semibold text-sm md:text-base h-[40px] md:h-[46px] lg:h-[50px] w-[40px] md:w-[46px] lg:w-[50px] inline-flex justify-center items-center border rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
              <path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="#0F0F0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
      </div>
    </main>

              <!-- memasukan navbar -->
    <?php include './component/footer.php'; ?>  

    <!-- Add button to toggle filter on mobile -->
    <button id="filter-toggle" class="fixed bottom-4 right-4 bg-primary text-white p-4 rounded-full shadow-lg md:hidden z-50">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
      </svg>
    </button>

    <script>
      // Toggle filter sidebar on mobile
      const filterToggle = document.getElementById("filter-toggle");
      const sidebar = document.getElementById("sidebar");

      filterToggle.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-full");
      });

      // Close sidebar when clicking outside
      document.addEventListener("click", (e) => {
        if (!sidebar.contains(e.target) && !filterToggle.contains(e.target)) {
          sidebar.classList.add("-translate-x-full");
        }
      });

      // Close sidebar when screen resized to desktop
      window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
          // md breakpoint
          sidebar.classList.remove("-translate-x-full");
        }
      });
    </script>


<script src="./src/script.js"></script>
  </body>
</html>

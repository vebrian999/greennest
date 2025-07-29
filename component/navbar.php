<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cart Sidebar</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />

</head>
  <?php
  session_start();
  // Cek login
  $isLoggedIn = isset($_SESSION['user_id']);
  $userName = $isLoggedIn ? $_SESSION['user_name'] : '';
  $userProfileImg = $isLoggedIn && isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : '';
  // Ambil inisial
  function getInitial($name) {
    $name = trim($name);
    if ($name === '') return '?';
    return strtoupper(substr($name, 0, 1));
  }
  ?>
  <body> 
 <header class="bg-white shadow-sm fixed w-full top-0 z-50">
      <nav class="mx-auto px-2 container md:px-16">
        <div class="flex items-center justify-between h-16">
          <!-- Logo Section -->
          <div class="flex-shrink-0">
            <a href="./index.php" class="text-2xl text-primary">GREENNEST</a>
          </div>

          <!-- Desktop Navigation & Icons - Hidden on mobile -->
          <div class="hidden md:flex flex-grow justify-center font-light">
            <ul class="flex space-x-8">
              <li><a href="./index.php" class="text-primary transition-colors">Home</a></li>
              <li><a href="./list-product.php" class="text-primary transition-colors">Shop All</a></li>
              <li><a href="#gifts" class="text-primary transition-colors">Gifts</a></li>
              <li><a href="#learn" class="text-primary transition-colors">Learn</a></li>
              <li><a href="#about" class="text-primary transition-colors">About Us</a></li>
            </ul>
          </div>

          <!-- Desktop Icons - Hidden on mobile -->
          <div class="hidden md:flex items-center space-x-4">
            <!-- Search, User, Cart icons for desktop -->
            <div class="relative">
              <div class="flex items-center">
                <div class="search-container overflow-hidden transition-all duration-300 w-0">
                  <input type="text" class="w-full pl-4 pr-12 py-2 border rounded-full focus:outline-none focus:border-primary text-sm" placeholder="Search..." />
                </div>
                <button class="search-trigger p-2 text-primary transition-colors absolute right-0">
                  <svg xmlns="http://www.w3.org/2000/svg" width="29" height="28" viewBox="0 0 29 28" fill="none">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.75 4.375C8.40076 4.375 4.875 7.90076 4.875 12.25C4.875 16.5992 8.40076 20.125 12.75 20.125C14.9249 20.125 16.8924 19.2445 18.3185 17.8185C19.7445 16.3924 20.625 14.4249 20.625 12.25C20.625 7.90076 17.0992 4.375 12.75 4.375ZM3.125 12.25C3.125 6.93426 7.43426 2.625 12.75 2.625C18.0657 2.625 22.375 6.93426 22.375 12.25C22.375 14.5925 21.5373 16.7406 20.1466 18.4091L25.6187 23.8813C25.9604 24.223 25.9604 24.777 25.6187 25.1187C25.277 25.4604 24.723 25.4604 24.3813 25.1187L18.9091 19.6466C17.2406 21.0373 15.0925 21.875 12.75 21.875C7.43426 21.875 3.125 17.5657 3.125 12.25Z"
                      fill="currentColor" />
                  </svg>
                </button>
              </div>
            </div>
            <a href="<?= $isLoggedIn ? './pengaturan.php' : './login.php' ?>" class="flex items-center justify-center w-10 aspect-square rounded-full hover:bg-gray-100 transition-colors text-primary z-20 relative">
              <?php if ($isLoggedIn): ?>
                <?php if ($userProfileImg): ?>
                  <img src="<?= htmlspecialchars($userProfileImg) ?>" alt="Profile" class="w-8 aspect-square rounded-full object-cover" />
                <?php else: ?>
                  <div class="w-8 aspect-square rounded-full border border-primary text-primary flex items-center justify-center font-semibold text-xl text-center">
                    <?= getInitial($userName) ?>
                  </div>
                <?php endif; ?>
              <?php else: ?>
                <!-- User icon -->
                <span class="flex items-center justify-center w-8 aspect-square">
                  <svg xmlns="http://www.w3.org/2000/svg" width="29" height="28" viewBox="0 0 29 28" fill="none">
                    <path
                      d="M18.875 7C18.875 9.41625 16.9163 11.375 14.5 11.375C12.0838 11.375 10.125 9.41625 10.125 7C10.125 4.58375 12.0838 2.625 14.5 2.625C16.9163 2.625 18.875 4.58375 18.875 7Z"
                      stroke="#45671E"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round" />
                    <path
                      d="M5.75134 23.4713C5.83337 18.7097 9.71887 14.875 14.5 14.875C19.2813 14.875 23.1669 18.7099 23.2487 23.4716C20.5854 24.6937 17.6225 25.375 14.5004 25.375C11.378 25.375 8.4148 24.6936 5.75134 23.4713Z"
                      stroke="#45671E"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round" />
                  </svg>
                </span>
              <?php endif; ?>
            </a>
                    <!-- Cart Icon -->
                    <button id="cartButton" class="p-2 text-primary transition-colors hover:text-green-600 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">1</span>
                    </button>
          </div>

          <!-- Mobile Menu Button -->
          <button class="md:hidden p-2 text-primary transition-colors" id="mobile-menu-button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden absolute left-0 right-0 top-16 bg-white shadow-lg z-50 px-4" id="mobile-menu">
          <!-- Search Bar for Mobile -->
          <div class="py-4 border-b border-gray-200">
            <div class="relative flex items-center">
              <input type="text" class="w-full pl-4 pr-12 py-2 border rounded-full focus:outline-none focus:border-primary text-sm" placeholder="Search..." />
              <button class="p-2 text-primary transition-colors absolute right-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Navigation Links -->
          <ul class="py-2 border-b border-gray-200">
            <li><a href="./index.php" class="block px-4 py-2 text-primary hover:bg-gray-50">Home</a></li>
            <li><a href="./list-product.php" class="block px-4 py-2 text-primary hover:bg-gray-50">Shop All</a></li>
            <li><a href="#gifts" class="block px-4 py-2 text-primary hover:bg-gray-50">Gifts</a></li>
            <li><a href="#learn" class="block px-4 py-2 text-primary hover:bg-gray-50">Learn</a></li>
            <li><a href="#about" class="block px-4 py-2 text-primary hover:bg-gray-50">About Us</a></li>
          </ul>

          <!-- User Actions -->
          <div class="py-4 space-y-2">
            <a href="#" class="flex items-center px-4 py-2 text-primary hover:bg-gray-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              My Account
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-primary hover:bg-gray-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              My Cart (0)
            </a>
          </div>
        </div>
      </nav>
    </header>

        <!-- cart sidebar -->
    <?php include 'cart-sidebar.php'; ?>
    </body>
       </html>
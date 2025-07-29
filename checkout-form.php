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

    <main class="pt-28 px-4 lg:px-16 container mx-auto">
      <!-- Main Content -->
      <div id="content" class="flex flex-col md:flex-row gap-8">
        <!-- Form Section -->
        <div class="flex-1 order-2 md:order-1">
          <!-- Express Checkout Section -->
          <div class="mb-8">
            <h1 class="text-[#8E8E8E] text-center mb-4">Express checkout</h1>
            <div class="flex justify-center gap-2">
              <img class="w-28 sm:w-48" src="./src/img/pay-img (1).png" alt="" />
              <img class="w-28 sm:w-48" src="./src/img/pay-img (2).png" alt="" />
              <img class="w-28 sm:w-48" src="./src/img/pay-img (3).png" alt="" />
            </div>
            <div class="flex my-4 items-center justify-center space-x-2">
              <hr class="h-0.5 bg-[#8E8E8E] flex-1" />
              <p class="text-center px-4">OR</p>
              <hr class="h-0.5 bg-[#8E8E8E] flex-1" />
            </div>
          </div>

          <!-- Cart Summary for Mobile -->
          <div class="md:hidden mb-8 bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-center mb-4">
              <h2 class="font-medium">Cart Summary</h2>
              <span class="text-sm text-gray-600">1 item</span>
            </div>
            <div class="flex gap-4 mb-4">
              <div class="relative">
                <img src="./src/img/main-img-product.png" alt="Snake Plant" class="w-20 h-20 object-cover rounded-lg" />
                <span class="absolute -top-2 -right-2 w-5 h-5 bg-primary text-white rounded-full flex items-center justify-center text-xs">1</span>
              </div>
              <div class="flex-1">
                <h3 class="font-medium">Snake Plant Laurentii</h3>
                <p class="text-sm text-gray-600">Large / Isabella (12.5" wide)</p>
                <div class="flex justify-between items-center mt-2">
                  <span class="text-gray-400 line-through text-sm">$219.00</span>
                  <span class="font-medium">$175.20</span>
                </div>
              </div>
            </div>
            <div class="border-t pt-4">
              <div class="flex justify-between mb-2">
                <span>Subtotal</span>
                <span>$175.20</span>
              </div>
              <div class="flex justify-between text-sm text-gray-600">
                <span>Shipping</span>
                <span>Calculated at next step</span>
              </div>
            </div>
          </div>

          <!-- Original Form Content -->
          <form class="space-y-6" id="checkoutForm" method="GET" action="payment-simulation.php">
            <!-- Contact Section -->
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-medium">Contact</h2>
              <p class="text-sm text-gray-600">
                Already have an account?
                <a href="#" class="text-secondary font-medium hover:underline">Login</a>
              </p>
            </div>

            <!-- Email Input -->
            <div>
              <input type="email" name="email" placeholder="Email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />
              <div class="mt-2">
                <label class="inline-flex items-center">
                  <input type="checkbox" class="form-checkbox text-primary" />
                  <span class="ml-2 text-sm text-gray-600">Email me with news and offers</span>
                </label>
              </div>
            </div>

            <!-- Delivery Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Delivery</h2>

              <!-- Country Selection -->
              <div class="relative">
                <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                  <option value="" disabled selected>--Select Country--</option>
                  <option value="us">Indonesia</option>
                  <option value="uk">Malaysia</option>
                  <option value="ca">Thailand</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                  </svg>
                </div>
              </div>

              <!-- Company (Optional) -->
              <input type="text" name="company" placeholder="Company (optional)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />

              <!-- Address -->
              <input type="text" name="address" placeholder="Address (no PO Boxes)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />

              <!-- Apartment -->
              <input type="text" name="apartment" placeholder="Apartment, suite, etc (optional)" class="mt-4 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />

              <!-- City, ZIP and State -->
              <!-- City, ZIP and State -->
              <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- City Input -->
                <input type="text" name="city" placeholder="City" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required />

                <!-- ZIP Code Select (Mobile: Full width, Desktop: 1 column) -->
                <div class="relative col-span-1 w-full">
                  <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                    <option value="" disabled selected>ZIP code</option>
                    <option value="10001">10001</option>
                    <option value="20001">20001</option>
                    <option value="30301">30301</option>
                    <option value="94101">94101</option>
                    <option value="75201">75201</option>
                    <!-- Add more ZIP codes here as needed -->
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                  </div>
                </div>

                <!-- State Select (Mobile: Full width, Desktop: 1 column) -->
                <div class="relative col-span-1 w-full">
                  <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                    <option value="" disabled selected>State</option>
                    <option value="NY">New York</option>
                    <option value="CA">California</option>
                    <option value="TX">Texas</option>
                    <option value="FL">Florida</option>
                    <option value="IL">Jakarta</option>
                    <!-- Add more states here as needed -->
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Phone -->
              <div class="mt-4 relative">
                <input type="tel" name="phone" placeholder="Phone (Optional)" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                <div class="absolute inset-y-0 right-4 flex items-center">
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                </div>
              </div>

              <!-- Text me checkbox -->
              <div class="mt-4">
                <label class="inline-flex items-center">
                  <input type="checkbox" class="form-checkbox text-primary" />
                  <span class="ml-2 text-sm text-gray-600">Text me with news and offers</span>
                </label>
              </div>
            </div>

            <!-- Shipping Method Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Shipping method</h2>

              <!-- Shipping Options -->
              <div class="space-y-3">
                <!-- Standard Shipping -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="true">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Standard Shipping</p>
                      <p class="text-sm text-gray-600">4-5 business days</p>
                    </div>
                  </div>
                  <span class="font-medium">Free</span>
                </label>

                <!-- Express Shipping -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="false">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Express Shipping</p>
                      <p class="text-sm text-gray-600">2-3 business days</p>
                    </div>
                  </div>
                  <span class="font-medium">$15.00</span>
                </label>

                <!-- Next Day Delivery -->
                <label class="shipping-option flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors" data-active="false">
                  <div class="flex items-center gap-3">
                    <input type="radio" name="shipping" class="form-radio text-primary" />
                    <div>
                      <p class="font-medium">Next Day Delivery</p>
                      <p class="text-sm text-gray-600">Next business day</p>
                    </div>
                  </div>
                  <span class="font-medium">$25.00</span>
                </label>
              </div>

              <!-- Info Message -->
              <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                </svg>
                <span>Some items may ship separately</span>
              </div>
            </div>

            <!-- Payment Section -->
            <div class="mt-8">
              <h2 class="text-xl font-medium mb-4">Metode Pembayaran</h2>
              <p class="text-sm text-gray-600 mb-4">Pilih metode pembayaran yang tersedia di Indonesia.</p>
              <div class="space-y-4">
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="transfer" class="form-radio text-primary" required />
                  <span class="font-medium">Transfer Bank</span>
                  <span class="text-xs text-gray-500">(BCA, Mandiri, BRI, dll)</span>
                </label>
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="cod" class="form-radio text-primary" />
                  <span class="font-medium">Bayar di Tempat (COD)</span>
                </label>
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                  <input type="radio" name="payment" value="ewallet" class="form-radio text-primary" />
                  <span class="font-medium">E-Wallet</span>
                  <span class="text-xs text-gray-500">(OVO, GoPay, ShopeePay, dll)</span>
                </label>
              </div>
              <button
                type="submit"
                class="mt-6 w-full bg-primary text-white py-2.5 px-6 rounded-full hover:bg-opacity-90 transition-colors text-lg font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                Checkout Sekarang
              </button>
            </div>
          </form>
        </div>

        <!-- Sidebar - Hidden on mobile -->
        <aside class="hidden md:block w-[520px] bg-primary p-6 rounded-lg h-fit sticky top-28 order-1 md:order-2" id="sidebar">
          <!-- Order Summary -->
          <div class="space-y-6">
            <!-- Product Item -->
            <div class="flex gap-4">
              <div class="relative">
                <img src="./src/img/main-img-product.png" alt="Snake Plant" class="w-20 h-20 object-cover rounded-lg" />
                <span class="absolute -top-2 -right-2 w-5 h-5 bg-white text-primary rounded-full flex items-center justify-center text-xs">1</span>
              </div>
              <div class="flex-1">
                <h3 class="font-medium text-white">Snake Plant Laurentii</h3>
                <p class="text-sm text-gray-200">Large / Isabella (12.5" wide) / Top Half Black</p>
                <div class="flex items-center gap-2 mt-1">
                  <div class="flex items-center gap-1 bg-white/20 px-2 py-0.5 rounded">
                    <span class="text-xs text-white">EARTH DAY SALE</span>
                  </div>
                  <span class="text-xs text-white">(-$43.80)</span>
                </div>
              </div>
              <div class="text-right">
                <p class="text-white/60 line-through text-sm">$219.00</p>
                <p class="font-medium text-white">$175.20</p>
              </div>
            </div>

            <!-- Discount Input -->
            <div class="flex gap-2">
              <input type="text" placeholder="Discount code or gift card" class="flex-1 px-4 py-2.5 rounded-lg border border-white/40 bg-white/10 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent" />
              <button class="bg-white text-primary px-6 rounded-lg hover:bg-opacity-90">apply</button>
            </div>

            <!-- Applied Discount -->
            <div class="inline-flex items-center gap-2 bg-white/20 px-3 py-1.5 rounded-md">
              <span class="text-sm text-white">EARTH DAY SALE</span>
              <button class="text-white hover:text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Order Summary Details -->
            <div class="space-y-3 pt-3">
              <div class="flex justify-between">
                <span class="text-white">Subtotal</span>
                <span class="text-white">$175.20</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-white">Shipping</span>
                <span class="text-white/80">Yogyakarta, Indonesia</span>
              </div>
              <div class="flex justify-between items-center pt-3 border-t-2 border-white/30">
                <span class="text-xl font-medium text-white">Total</span>
                <div class="text-right">
                  <span class="text-sm text-white/80">USD</span>
                  <span class="text-xl font-medium text-white">$175.20</span>
                </div>
              </div>
              <div class="flex items-center gap-1 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 15 16" fill="none">
                  <mask id="mask0_95_5951" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="15" height="16">
                    <path d="M14.5 1V15H0.5V1H14.5Z" fill="white" stroke="white" />
                  </mask>
                  <g mask="url(#mask0_95_5951)">
                    <mask id="path-2-inside-1_95_5951" fill="white">
                      <path
                        d="M11.25 2.10715H8.49638C8.13626 2.10728 7.77986 2.18002 7.44849 2.32104C7.11712 2.46205 6.81759 2.66844 6.56781 2.92786L1.62853 8.05572C1.33657 8.35898 1.17527 8.76469 1.17927 9.18563C1.18328 9.60657 1.35228 10.0091 1.64995 10.3068L5.37853 14.0354C5.62663 14.2831 5.96196 14.4236 6.31252 14.4268C6.66309 14.43 7.00094 14.2956 7.25353 14.0525L12.735 8.77465C12.9427 8.57471 13.1079 8.33493 13.2207 8.06964C13.3336 7.80435 13.3918 7.51901 13.3917 7.23072V4.25C13.3917 3.68168 13.166 3.13664 12.7641 2.73478C12.3622 2.33291 11.8172 2.10715 11.2489 2.10715" />
                    </mask>
                    <path
                      d="M8.49638 2.10715V1.10715L8.49602 1.10715L8.49638 2.10715ZM6.56781 2.92786L7.28804 3.6216L7.28817 3.62146L6.56781 2.92786ZM1.62853 8.05572L0.9083 7.36198L0.908106 7.36218L1.62853 8.05572ZM1.64995 10.3068L2.35706 9.59968L2.35704 9.59966L1.64995 10.3068ZM5.37853 14.0354L4.67142 14.7425L4.672 14.743L5.37853 14.0354ZM7.25353 14.0525L7.947 14.773L7.94713 14.7729L7.25353 14.0525ZM12.735 8.77465L12.0415 8.05419L12.0413 8.05429L12.735 8.77465ZM13.3917 7.23072H12.3917V7.23076L13.3917 7.23072ZM11.25 2.10715V1.10715H8.49638V2.10715V3.10715H11.25V2.10715ZM8.49638 2.10715L8.49602 1.10715C8.00145 1.10733 7.51199 1.20723 7.05692 1.40089L7.44849 2.32104L7.84006 3.24119C8.04772 3.15282 8.27106 3.10723 8.49674 3.10715L8.49638 2.10715ZM7.44849 2.32104L7.05692 1.40089C6.60184 1.59455 6.19049 1.87799 5.84745 2.23426L6.56781 2.92786L7.28817 3.62146C7.4447 3.45889 7.63241 3.32956 7.84006 3.24119L7.44849 2.32104ZM6.56781 2.92786L5.84759 2.23412L0.9083 7.36198L1.62853 8.05572L2.34875 8.74946L7.28804 3.6216L6.56781 2.92786ZM1.62853 8.05572L0.908106 7.36218C0.434497 7.85415 0.172817 8.51229 0.179317 9.19515L1.17927 9.18563L2.17923 9.17611C2.17771 9.01709 2.23865 8.86382 2.34894 8.74926L1.62853 8.05572ZM1.17927 9.18563L0.179317 9.19515C0.185818 9.87801 0.459979 10.5311 0.942868 11.0139L1.64995 10.3068L2.35704 9.59966C2.24459 9.48722 2.18074 9.33514 2.17923 9.17611L1.17927 9.18563ZM1.64995 10.3068L0.942847 11.0139L4.67142 14.7425L5.37853 14.0354L6.08563 13.3283L2.35706 9.59968L1.64995 10.3068ZM5.37853 14.0354L4.672 14.743C5.10535 15.1757 5.69106 15.4212 6.30338 15.4268L6.31252 14.4268L6.32167 13.4268C6.23286 13.426 6.14791 13.3904 6.08505 13.3277L5.37853 14.0354ZM6.31252 14.4268L6.30338 15.4268C6.9157 15.4324 7.50581 15.1976 7.947 14.773L7.25353 14.0525L6.56005 13.332C6.49607 13.3936 6.41048 13.4277 6.32167 13.4268L6.31252 14.4268ZM7.25353 14.0525L7.94713 14.7729L13.4286 9.495L12.735 8.77465L12.0413 8.05429L6.55992 13.3321L7.25353 14.0525ZM12.735 8.77465L13.4285 9.4951C13.7331 9.20187 13.9754 8.85018 14.1409 8.46109L13.2207 8.06964L12.3005 7.67818C12.2403 7.81967 12.1522 7.94756 12.0415 8.05419L12.735 8.77465ZM13.2207 8.06964L14.1409 8.46109C14.3065 8.072 14.3918 7.65351 14.3917 7.23068L13.3917 7.23072L12.3917 7.23076C12.3917 7.38452 12.3607 7.5367 12.3005 7.67818L13.2207 8.06964ZM13.3917 7.23072H14.3917V4.25H13.3917H12.3917V7.23072H13.3917ZM13.3917 4.25H14.3917C14.3917 3.41647 14.0606 2.61707 13.4712 2.02767L12.7641 2.73478L12.057 3.44188C12.2713 3.65621 12.3917 3.9469 12.3917 4.25H13.3917ZM12.7641 2.73478L13.4712 2.02767C12.8818 1.43827 12.0824 1.10715 11.2489 1.10715V2.10715V3.10715C11.552 3.10715 11.8427 3.22756 12.057 3.44188L12.7641 2.73478Z"
                      fill="#80807F"
                      mask="url(#path-2-inside-1_95_5951)" />
                    <path
                      d="M10.1787 5.21823C10.2356 5.21839 10.2812 5.26484 10.2812 5.32175C10.2811 5.37851 10.2355 5.42412 10.1787 5.42429C10.1218 5.42429 10.0754 5.37861 10.0752 5.32175C10.0752 5.26474 10.1217 5.21823 10.1787 5.21823Z"
                      fill="#7B7B7B"
                      stroke="#80807F" />
                    <mask id="path-5-inside-2_95_5951" fill="white">
                      <path d="M10.168 5.3107H10.1894V5.33213H10.168V5.3107Z" />
                    </mask>
                    <path
                      d="M10.168 5.3107V4.3107H9.16797V5.3107H10.168ZM10.1894 5.3107H11.1894V4.3107H10.1894V5.3107ZM10.1894 5.33213V6.33213H11.1894V5.33213H10.1894ZM10.168 5.33213H9.16797V6.33213H10.168V5.33213ZM10.168 5.3107V6.3107H10.1894V5.3107V4.3107H10.168V5.3107ZM10.1894 5.3107H9.1894V5.33213H10.1894H11.1894V5.3107H10.1894ZM10.1894 5.33213V4.33213H10.168V5.33213V6.33213H10.1894V5.33213ZM10.168 5.33213H11.168V5.3107H10.168H9.16797V5.33213H10.168Z"
                      fill="#80807F"
                      mask="url(#path-5-inside-2_95_5951)" />
                  </g>
                </svg>
                <span class="text-white">TOTAL SAVINGS $43.80</span>
              </div>
            </div>
          </div>
        </aside>
      </div>

      <!-- Mobile Checkout Fixed Bottom -->
      <!-- Mobile Checkout Fixed Bottom -->
      <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 md:hidden">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm">Total</span>
          <div class="flex items-center">
            <div>
              <span class="text-sm text-gray-600 mr-2">USD</span>
              <span class="text-lg font-medium">$175.20</span>
            </div>
            <span class="text-gray-400 line-through text-sm ml-1">$219.00</span>
          </div>
        </div>
        <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-full hover:bg-opacity-90 transition-colors text-base font-medium">Continue to Payment</button>
      </div>
    </main>

    <!-- footer -->
    <?php include './component/footer.php'; ?>

    <!-- Add this JavaScript code -->
    <script>
      // Script untuk mengarahkan ke payment-simulation.php sesuai metode pembayaran
      document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const paymentMethod = document.querySelector('input[name="payment"]:checked');
        if (paymentMethod) {
          const method = paymentMethod.value;
          window.location.href = `payment-simulation.php?method=${method}`;
        } else {
          alert('Pilih metode pembayaran terlebih dahulu!');
        }
      });
      document.addEventListener("DOMContentLoaded", function () {
        const shippingOptions = document.querySelectorAll(".shipping-option");

        shippingOptions.forEach((option) => {
          option.addEventListener("click", function () {
            // Remove active state from all options
            shippingOptions.forEach((opt) => {
              opt.dataset.active = "false";
              opt.classList.remove("bg-[#B8E0CE]");
            });

            // Add active state to clicked option
            this.dataset.active = "true";
            this.classList.add("bg-[#B8E0CE]");

            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
          });
        });
      });
    </script>
  </body>
</html>

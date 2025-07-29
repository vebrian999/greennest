  <!-- Cart Sidebar Overlay -->
    <div id="cartSidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300 ease-in-out"></div>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="fixed top-0 right-0 h-full w-full max-w-md bg-primary text-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-white border-opacity-20">
            <h2 class="text-xl font-medium text-white">Your Cart</h2>
            <button id="closeCartSidebar" class="text-white hover:text-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Free Shipping Banner -->
        <div class="px-6 py-3">
            <div class="flex items-center text-sm text-green-300 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                You've unlocked Free Shipping
            </div>
            <div class="w-full h-1 bg-white bg-opacity-20 rounded-full">
                <div class="h-full bg-green-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto px-6 py-2">
            <!-- Cart Item -->
            <div class="bg-white/10 rounded-lg p-4 mb-4 cart-item-shadow">
                <div class="flex items-start space-x-4">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-green-200 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2D5530" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-white font-medium text-base">Bamboo Palm</h3>
                            <span class="text-white font-semibold text-base ml-2">$259</span>
                        </div>
                        <p class="text-gray-300 text-sm mb-3">Self-Watering, Slate</p>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button class="w-8 h-8 bg-white/20 text-white rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <span class="text-white font-medium min-w-[20px] text-center">1</span>
                                <button class="w-8 h-8 bg-white/20 text-white rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                            </div>
                            <button class="text-gray-200 text-xs uppercase tracking-wider hover:text-black transition-colors">
                                REMOVE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-white border-opacity-20">
            <!-- Subtotal -->
            <div class="flex justify-between items-center mb-1">
                <span class="text-white text-base">Subtotal:</span>
                <span class="text-white font-semibold text-lg">$259</span>
            </div>
            
            <!-- Disclaimer -->
            <p class="text-gray-300 text-xs mb-4 leading-relaxed">
                Most items ship separately. Orders cannot be cancelled once placed.
            </p>
            
            <!-- Checkout Button -->
            <button class="w-full bg-white text-primary py-3 rounded-lg font-semibold text-base hover:bg-gray-100 transition-colors">
                CHECKOUT
            </button>
        </div>
    </div>
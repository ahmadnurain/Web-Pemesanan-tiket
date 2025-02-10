<!-- Navbar -->
<nav id="navbar" class="fixed w-full bg-transparent backdrop-blur-sm text-white z-20 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <span class="text-lg font-bold">Wisata Majalengka</span>
            </div>

            <!-- Hamburger Menu -->
            <div class="flex md:hidden">
                <button id="menu-btn" class="text-white focus:outline-none">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>

            <!-- Menu -->
            <div id="menu" class="hidden md:flex space-x-8">
                <a href="#" class="hover:text-gray-500 transition">Beranda</a>
                <a href="#" class="hover:text-gray-500 transition">Destinasi</a>
                <a href="#" class="hover:text-gray-500 transition">Kontak</a>
            </div>


        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden flex-col space-y-4 mt-4 md:hidden">
            <a href="#" class="block text-white hover:text-gray-500">Beranda</a>
            <a href="#" class="block text-white hover:text-gray-500">Destinasi</a>
            <a href="#" class="block text-white hover:text-gray-500">Kontak</a>
        </div>
    </div>
</nav>

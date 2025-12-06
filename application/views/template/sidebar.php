<!-- Sidebar PHP Version -->
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full hidden md:flex shrink-0 z-20">
    <div>
        <div class="p-6 mb-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-leaf text-green-500 text-2xl"></i>
                <div>
                    <h1 class="font-bold text-gray-800 text-lg leading-tight">AgriPlatform</h1>
                    <p class="text-xs text-gray-400">Farm Management</p>
                </div>
            </div>
        </div>
        
        <nav class="px-4 space-y-1">
            <!-- DASHBOARD LINK -->
            <!-- Perhatikan: Kita pakai site_url(), bukan onclick JS -->
            <a href="<?= site_url('petani/dashboard') ?>" 
               class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               <?= ($active_menu == 'dashboard') ? 'bg-green-600 text-white shadow-md shadow-green-200' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' ?>">
                <i class="fas fa-home w-5 <?= ($active_menu == 'dashboard') ? 'text-white' : 'text-gray-500' ?>"></i>
                <span>Dashboard</span>
            </a>

            <!-- LAHAN LINK -->
            <a href="<?= site_url('petani/lahan') ?>" 
               class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               <?= ($active_menu == 'lahan') ? 'bg-green-600 text-white shadow-md shadow-green-200' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' ?>">
                <i class="fas fa-leaf w-5 <?= ($active_menu == 'lahan') ? 'text-white' : 'text-gray-500' ?>"></i>
                <span>Dashboard Lahan</span>
            </a>

            <!-- SIKLUS LINK -->
            <a href="<?= site_url('petani/siklus') ?>" 
               class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               <?= ($active_menu == 'siklus') ? 'bg-green-600 text-white shadow-md shadow-green-200' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' ?>">
                <i class="fas fa-calendar-alt w-5 <?= ($active_menu == 'siklus') ? 'text-white' : 'text-gray-500' ?>"></i>
                <span>Siklus & Aktivitas</span>
            </a>
            
            <!-- Tambahkan menu lain sesuai kebutuhan -->
        </nav>
    </div>

    <!-- User Profile di Bawah -->
    <div class="p-4 border-t border-gray-100">
        <div class="bg-gray-50 rounded-xl p-3 flex items-center space-x-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                <?= substr($this->session->userdata('nama_lengkap'), 0, 1) ?>
            </div>
            <div class="flex-1 overflow-hidden">
                <h4 class="text-sm font-bold text-gray-800 truncate"><?= $this->session->userdata('nama_lengkap') ?></h4>
                <p class="text-xs text-gray-500 truncate">Petani</p>
            </div>
        </div>
        <a href="<?= site_url('auth/logout') ?>" class="w-full flex items-center px-2 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>

<!-- Main Content Wrapper Starts Here -->
<main class="flex-1 overflow-y-auto h-full bg-white relative">
    <!-- Mobile Header -->
    <div class="md:hidden sticky top-0 z-30 bg-white border-b p-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
                <i class="fas fa-leaf text-green-500"></i>
                <span class="font-bold">AgriPlatform</span>
        </div>
        <button class="text-gray-600"><i class="fas fa-bars"></i></button>
    </div>
    <div class="p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">
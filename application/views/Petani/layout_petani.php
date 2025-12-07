<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'AgriPlatform - Farm Management' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { green: '#22c55e', dark: '#0f172a' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: hidden !important; }
        /* Utility for hiding modals in PHP version */
        .hidden-modal { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div id="app" class="h-screen w-full overflow-hidden flex">
        
        <!-- SIDEBAR -->
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
                    <?php 
                    // MENU PETANI LENGKAP
                    $sidebar_items = [
                        ['id' => 'dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'link' => 'petani/dashboard'],
                        ['id' => 'lahan', 'icon' => 'fa-leaf', 'label' => 'Dashboard Lahan', 'link' => 'petani/lahan'],
                        ['id' => 'siklus', 'icon' => 'fa-calendar-alt', 'label' => 'Siklus & Aktivitas', 'link' => 'petani/siklus'],
                        ['id' => 'laporan', 'icon' => 'fa-info-circle', 'label' => 'Informasi & Bantuan', 'link' => 'petani/laporan'],
                        // Profil sekarang sudah diaktifkan
                        ['id' => 'profil', 'icon' => 'fa-user', 'label' => 'Profil', 'link' => 'petani/profil'],
                    ];
                    
                    foreach($sidebar_items as $item): 
                        $is_active = ($active_menu == $item['id']);
                    ?>
                        <a href="<?= base_url($item['link']) ?>" 
                           class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 block
                           <?= $is_active ? 'bg-green-600 text-white shadow-md shadow-green-200' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' ?>">
                            <i class="fas <?= $item['icon'] ?> w-5 <?= $is_active ? 'text-white' : 'text-gray-500 group-hover:text-green-600' ?>"></i>
                            <span><?= $item['label'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- USER PROFILE MINI -->
            <div class="p-4 border-t border-gray-100">
                <div class="bg-gray-50 rounded-xl p-3 flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                        <?= isset($user['avatar']) ? $user['avatar'] : 'P' ?>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-sm font-bold text-gray-800 truncate"><?= isset($user['name']) ? $user['name'] : 'Petani' ?></h4>
                        <p class="text-xs text-gray-500 truncate"><?= isset($user['role']) ? $user['role'] : 'User' ?></p>
                    </div>
                </div>
                <a href="<?= base_url('auth/logout') ?>" class="w-full flex items-center px-2 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="flex-1 overflow-y-auto h-full bg-white relative">
            <!-- MOBILE HEADER -->
            <div class="md:hidden sticky top-0 z-30 bg-white border-b p-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                     <i class="fas fa-leaf text-green-500"></i>
                     <span class="font-bold">AgriPlatform</span>
                </div>
                <button class="text-gray-600"><i class="fas fa-bars"></i></button>
            </div>

            <!-- CONTENT INJECTION START -->
            <div class="p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">
                <?php 
                    // Memuat file view konten secara dinamis
                    if(isset($content) && $content) {
                        $this->load->view($content); 
                    }
                ?>
            </div>
            <!-- CONTENT INJECTION END -->
        </main>
    </div>

    <!-- MODAL CONTAINER -->
    <div id="modal-container" class="hidden relative z-50">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Content will be injected or toggled -->
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if(modal) {
                if(modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('modal-active');
                } else {
                    modal.classList.add('hidden');
                    document.body.classList.remove('modal-active');
                }
            }
        }
    </script>

</body>
</html>
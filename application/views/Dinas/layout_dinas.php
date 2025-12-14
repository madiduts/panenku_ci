<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'SIM Pertanian - Dashboard Dinas' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        dinas: { primary: '#2563EB', dark: '#1E40AF', light: '#EFF6FF' }
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
        .modal-active { overflow: hidden; }
        #modal-container:empty { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div id="app" class="h-screen w-full overflow-hidden flex">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full hidden md:flex shrink-0 z-20">
            <div>
                <div class="p-6 mb-2">
                    <div class="flex items-center space-x-2">
                        <!-- BAGIAN LOGO SIDEBAR DIGANTI DISINI -->
                        <img src="<?= base_url('assets/img/logo.png') ?>" 
                             alt="Logo Panenku" 
                             class="w-10 h-10 object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        
                        <!-- Fallback: Ikon Gedung jika gambar tidak ada -->
                        <div class="bg-blue-600 text-white p-2 rounded-lg" style="display:none;">
                            <i class="fas fa-building text-xl"></i>
                        </div>

                        <div>
                            <h1 class="font-bold text-gray-800 text-lg leading-tight">Panenku</h1>
                            <p class="text-xs text-gray-400">Dinas Pertanian</p>
                        </div>
                    </div>
                </div>
                <nav class="px-4 space-y-1">
                    <?php 
                    $menu_items = [
                        ['id' => 'monitoring', 'icon' => 'fa-chart-pie', 'label' => 'Monitoring & Visualisasi', 'link' => 'dinas/dashboard'],
                        ['id' => 'validasi', 'icon' => 'fa-check-double', 'label' => 'Validasi Data', 'link' => 'dinas/validasi'],
                        ['id' => 'laporan', 'icon' => 'fa-file-alt', 'label' => 'Laporan / Reporting', 'link' => 'dinas/laporan'],
                        ['id' => 'master', 'icon' => 'fa-database', 'label' => 'Master Data Management', 'link' => 'dinas/master_data'],
                    ];
                    foreach($menu_items as $item): 
                        $is_active = ($active_menu == $item['id']);
                    ?>
                        <a href="<?= base_url($item['link']) ?>" 
                           class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 block mb-1
                           <?= $is_active ? 'bg-black text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                            <i class="fas <?= $item['icon'] ?> w-5 <?= $is_active ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span><?= $item['label'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
            
            <!-- USER PROFILE -->
            <div class="p-4 border-t border-gray-100">
                <div class="bg-gray-50 rounded-xl p-3 flex items-center space-x-3 mb-3 border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                        <?php if(isset($user['avatar_url']) && $user['avatar_url'] != ''): ?>
                            <img src="<?= $user['avatar_url'] ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-gray-400 text-lg mt-1"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-sm font-bold text-gray-800 truncate"><?= isset($user['name']) ? $user['name'] : 'Admin' ?></h4>
                        <p class="text-[10px] text-gray-500 truncate uppercase tracking-wider"><?= isset($user['role']) ? $user['role'] : 'Dinas' ?></p>
                    </div>
                </div>
                <a href="<?= base_url('auth/logout') ?>" class="w-full flex items-center px-2 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto h-full bg-slate-50 relative">
            <!-- Header Mobile -->
            <div class="md:hidden sticky top-0 z-30 bg-white border-b p-4 flex justify-between items-center shadow-sm">
                <div class="flex items-center space-x-2">
                     <!-- BAGIAN LOGO MOBILE DIGANTI DISINI -->
                     <img src="<?= base_url('assets/img/logo.png') ?>" 
                          alt="Logo" 
                          class="w-8 h-8 object-contain"
                          onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                     
                     <i class="fas fa-building text-blue-600 text-xl" style="display:none;"></i>
                     
                     <span class="font-bold text-gray-800">Panenku</span>
                </div>
                <button class="text-gray-600"><i class="fas fa-bars"></i></button>
            </div>

            <!-- Content Injection -->
            <div class="p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">
                <?php 
                if(isset($content) && $content) {
                    $this->load->view($content); 
                }
                ?>
            </div>
        </main>
    </div>

    <!-- MODAL CONTAINER -->
    <div id="modal-container"></div>

    <!-- GLOBAL SCRIPTS & CHART CONFIG -->
    <script>
        // Modal Logic (Generic)
        function closeModal() {
            document.getElementById('modal-container').innerHTML = '';
            document.body.classList.remove('modal-active');
        }

        // --- CHART JS INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', function() {
            // 1. CHART MONITORING
            const ctxHarvest = document.getElementById('harvestTrendsChart');
            if (ctxHarvest) {
                new Chart(ctxHarvest, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [
                            { label: 'Padi', data: [450, 480, 520, 490, 560, 590], borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.1)', tension: 0.4, fill: false },
                            { label: 'Jagung', data: [320, 340, 380, 360, 400, 420], borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.1)', tension: 0.4, fill: false },
                            { label: 'Kedelai', data: [180, 200, 220, 210, 240, 260], borderColor: '#8b5cf6', backgroundColor: 'rgba(139, 92, 246, 0.1)', tension: 0.4, fill: false }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }, scales: { y: { beginAtZero: true, max: 600, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } }, elements: { point: { radius: 3, hoverRadius: 5 } } }
                });
            }

            const ctxCommodity = document.getElementById('commodityPieChart');
            if (ctxCommodity) {
                new Chart(ctxCommodity, {
                    type: 'pie',
                    data: {
                        labels: ['Padi', 'Jagung', 'Kedelai', 'Lainnya'],
                        datasets: [{ data: [45, 30, 15, 10], backgroundColor: ['#22c55e', '#f59e0b', '#8b5cf6', '#9ca3af'], borderWidth: 0 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            const ctxVillage = document.getElementById('villageStatsChart');
            if (ctxVillage) {
                new Chart(ctxVillage, {
                    type: 'bar',
                    data: {
                        labels: ['Desa Makmur', 'Desa Sejahtera', 'Desa Subur', 'Desa Tani Jaya', 'Desa Hijau'],
                        datasets: [
                            { label: 'Jumlah Petani', data: [45, 38, 52, 41, 32], backgroundColor: '#3b82f6', barPercentage: 0.6, categoryPercentage: 0.8 },
                            { label: 'Luas (Ha)', data: [120, 95, 150, 110, 85], backgroundColor: '#22c55e', barPercentage: 0.6, categoryPercentage: 0.8 }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } } }, scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } } }
                });
            }

            // 2. CHART LAPORAN
            const ctxProduction = document.getElementById('productionByVillageChart');
            if (ctxProduction) {
                new Chart(ctxProduction, {
                    type: 'bar',
                    data: {
                        labels: ['Makmur', 'Sejahtera', 'Subur', 'Tani Jaya', 'Hijau'],
                        datasets: [
                            { label: 'Padi (Ton)', data: [300, 250, 400, 275, 200], backgroundColor: '#22c55e', barPercentage: 0.6 },
                            { label: 'Jagung (Ton)', data: [160, 120, 200, 140, 120], backgroundColor: '#f59e0b', barPercentage: 0.6 },
                            { label: 'Kedelai (Ton)', data: [40, 30, 40, 40, 30], backgroundColor: '#8b5cf6', barPercentage: 0.6 }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }, scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } } }
                });
            }
        });
    </script>
</body>
</html>
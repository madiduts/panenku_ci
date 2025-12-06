<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Pertanian - Dashboard Dinas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        dinas: {
                            primary: '#2563EB',
                            dark: '#1E40AF',
                            light: '#EFF6FF',
                        }
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div id="app" class="h-screen w-full overflow-hidden flex">
        <!-- Sidebar & Content injected by JS -->
    </div>

    <!-- MODAL CONTAINER -->
    <div id="modal-container"></div>

    <script>
        // --- STATE MANAGEMENT ---
        const state = {
            currentPage: 'validasi', // Default ke Validasi sesuai permintaan
            activeTabMaster: 'petani', 
            activeTabValidasi: 'masuk',
            user: {
                name: 'rzayyanrizka',
                role: 'Admin Dinas',
                avatar: 'RZ'
            }
        };

        // --- MOCK DATA ---
        const data = {
            stats: {
                totalPetani: 1250,
                totalLahan: 4500,
                laporanPending: 12,
                warningHama: 3
            },
            ews: [
                { lokasi: 'Desa Sukamaju', hama: 'Wereng Coklat', level: 'Waspada', luas: '15 Ha' },
                { lokasi: 'Desa Bojong', hama: 'Tikus', level: 'Bahaya', luas: '25 Ha' }
            ],
            // Validasi Data
            laporanMasuk: [
                { id: 'RPT001', petani: 'Budi Santoso', lokasi: 'Desa Makmur', jenis: 'Panen', komoditas: 'Padi', luas: '2.5', hasil: '12.5', tanggal: '01 Des', produktivitas: '5.00' },
                { id: 'RPT002', petani: 'Siti Aminah', lokasi: 'Desa Sejahtera', jenis: 'Tanam', komoditas: 'Jagung', luas: '1.8', hasil: '-', tanggal: '28 Nov', produktivitas: '-' },
                { id: 'RPT003', petani: 'Ahmad Yani', lokasi: 'Desa Subur', jenis: 'Panen', komoditas: 'Kedelai', luas: '1.2', hasil: '3.6', tanggal: '30 Nov', produktivitas: '3.00' },
                { id: 'RPT004', petani: 'Dewi Lestari', lokasi: 'Desa Tani Jaya', jenis: 'Pemupukan', komoditas: 'Padi', luas: '3', hasil: '-', tanggal: '03 Des', produktivitas: '-' },
            ],
            riwayatValidasi: [
                { id: 'RPT005', petani: 'Suparman', jenis: 'Panen', komoditas: 'Padi', tanggal: '27 Nov 2025', status: 'Disetujui', validator: 'Dr. Agus Wijaya', catatan: 'Data sesuai dengan lapangan' },
                { id: 'RPT006', petani: 'Rina Wati', jenis: 'Tanam', komoditas: 'Jagung', tanggal: '22 Nov 2025', status: 'Disetujui', validator: 'Dr. Agus Wijaya', catatan: 'Verified on site' },
                { id: 'RPT007', petani: 'Hendra Kusuma', jenis: 'Panen', komoditas: 'Padi', tanggal: '20 Nov 2025', status: 'Ditolak', validator: 'Ir. Siti Nurhaliza', catatan: 'Data produktivitas tidak sesuai dengan standar area' },
            ],
            // Laporan Data
            rekapDesa: [
                { desa: 'Desa Makmur', lahan: 120, petani: 45, padi_ha: 60, padi_ton: 300, jagung_ha: 40, jagung_ton: 160, kedelai_ha: 20, kedelai_ton: 40, prod: '4.17' },
                { desa: 'Desa Sejahtera', lahan: 95, petani: 38, padi_ha: 50, padi_ton: 250, jagung_ha: 30, jagung_ton: 120, kedelai_ha: 15, kedelai_ton: 30, prod: '4.21' },
                { desa: 'Desa Subur', lahan: 150, petani: 52, padi_ha: 80, padi_ton: 400, jagung_ha: 50, jagung_ton: 200, kedelai_ha: 20, kedelai_ton: 40, prod: '4.27' },
                { desa: 'Desa Tani Jaya', lahan: 110, petani: 41, padi_ha: 55, padi_ton: 275, jagung_ha: 35, jagung_ton: 140, kedelai_ha: 20, kedelai_ton: 40, prod: '4.14' },
                { desa: 'Desa Hijau', lahan: 85, petani: 32, padi_ha: 40, padi_ton: 200, jagung_ha: 30, jagung_ton: 120, kedelai_ha: 15, kedelai_ton: 30, prod: '4.12' },
            ],
            // Master Data
            masterKomoditas: [
                { id: 1, nama: 'Padi', varietas: 'IR64', musim: 'Sepanjang tahun', estimasi: '100-120 hari' },
                { id: 2, nama: 'Padi', varietas: 'Ciherang', musim: 'Sepanjang tahun', estimasi: '110-120 hari' },
                { id: 3, nama: 'Jagung', varietas: 'Hibrida', musim: 'Kemarau', estimasi: '90-100 hari' },
                { id: 4, nama: 'Kedelai', varietas: 'Anjasmoro', musim: 'Kemarau', estimasi: '75-80 hari' },
            ],
            masterHama: [
                { id: 1, nama: 'Wereng Coklat', latin: 'Nilaparvata lugens', komoditas: 'Padi', bahaya: 'Tinggi', penanganan: 'Insektisida sistemik, penanaman varietas tahan' },
                { id: 2, nama: 'Penggerek Batang', latin: 'Scirpophaga incertulas', komoditas: 'Padi', bahaya: 'Sedang', penanganan: 'Sanitasi lahan, penggunaan musuh alami' },
                { id: 3, nama: 'Ulat Grayak', latin: 'Spodoptera litura', komoditas: 'Jagung', bahaya: 'Tinggi', penanganan: 'Insektisida, penanaman refugia' },
                { id: 4, nama: 'Penggerek Tongkol', latin: 'Helicoverpa armigera', komoditas: 'Jagung', bahaya: 'Sedang', penanganan: 'Insektisida kontak, monitoring rutin' },
            ],
            masterPetani: [
                { id: 1, nama: 'Budi Santoso', nik: '3201012345678901', desa: 'Desa Makmur', lahan: '2.5 Ha', komoditas: 'Padi, Jagung', telp: '081234567890', status: 'Aktif' },
                { id: 2, nama: 'Siti Aminah', nik: '3201012345678902', desa: 'Desa Sejahtera', lahan: '1.8 Ha', komoditas: 'Jagung', telp: '081234567891', status: 'Aktif' },
                { id: 3, nama: 'Ahmad Yani', nik: '3201012345678903', desa: 'Desa Subur', lahan: '1.2 Ha', komoditas: 'Kedelai', telp: '081234567892', status: 'Aktif' },
                { id: 4, nama: 'Dewi Lestari', nik: '3201012345678904', desa: 'Desa Tani Jaya', lahan: '3 Ha', komoditas: 'Padi', telp: '081234567893', status: 'Aktif' },
                { id: 5, nama: 'Suparman', nik: '3201012345678905', desa: 'Desa Hijau', lahan: '2 Ha', komoditas: 'Padi, Kedelai', telp: '081234567894', status: 'Tidak Aktif' },
            ]
        };

        // --- RENDER FUNCTIONS ---
        function render() {
            const app = document.getElementById('app');
            app.innerHTML = renderLayout();
            
            // PENTING: Inisialisasi chart sesuai halaman yang aktif
            if (state.currentPage === 'monitoring') {
                initMonitoringCharts();
            } else if (state.currentPage === 'laporan') {
                initLaporanCharts();
            }
        }

        function renderLayout() {
            const sidebarItems = [
                { id: 'monitoring', icon: 'fa-chart-pie', label: 'Monitoring & Visualisasi' },
                { id: 'validasi', icon: 'fa-check-double', label: 'Validasi Data' },
                { id: 'laporan', icon: 'fa-file-alt', label: 'Laporan / Reporting' },
                { id: 'master', icon: 'fa-database', label: 'Master Data Management' },
            ];

            return `
            <!-- Sidebar -->
            <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full hidden md:flex shrink-0 z-20">
                <div>
                    <div class="p-6 mb-2">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-600 text-white p-2 rounded-lg"><i class="fas fa-building text-xl"></i></div>
                            <div>
                                <h1 class="font-bold text-gray-800 text-lg leading-tight">AgriPlatform</h1>
                                <p class="text-xs text-gray-400">Dinas Pertanian</p>
                            </div>
                        </div>
                    </div>
                    <nav class="px-4 space-y-1">
                        ${sidebarItems.map(item => `
                            <button onclick="navigate('${item.id}')" 
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                                ${state.currentPage === item.id 
                                    ? 'bg-black text-white shadow-md' 
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'}">
                                <i class="fas ${item.icon} w-5 ${state.currentPage === item.id ? 'text-white' : 'text-gray-400'}"></i>
                                <span>${item.label}</span>
                            </button>
                        `).join('')}
                    </nav>
                </div>
                <div class="p-4 border-t border-gray-100">
                    <div class="bg-gray-50 rounded-xl p-3 flex items-center space-x-3 mb-3 border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                            ${state.user.avatar}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <h4 class="text-sm font-bold text-gray-800 truncate">${state.user.name}</h4>
                            <p class="text-[10px] text-gray-500 truncate uppercase tracking-wider">${state.user.role}</p>
                        </div>
                    </div>
                    <button onclick="alert('Logout')" class="w-full flex items-center px-2 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Keluar</span>
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto h-full bg-slate-50 relative">
                <!-- Header Mobile -->
                <div class="md:hidden sticky top-0 z-30 bg-white border-b p-4 flex justify-between items-center shadow-sm">
                    <div class="flex items-center space-x-2">
                         <i class="fas fa-building text-blue-600"></i>
                         <span class="font-bold text-gray-800">Dinas Pertanian</span>
                    </div>
                    <button class="text-gray-600"><i class="fas fa-bars"></i></button>
                </div>

                <div class="p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">
                    ${getContent()}
                </div>
            </main>
            `;
        }

        function getContent() {
            switch(state.currentPage) {
                case 'monitoring': return getPageMonitoring();
                case 'validasi': return getPageValidasi();
                case 'laporan': return getPageLaporan();
                case 'master': return getPageMaster();
                default: return getPageMonitoring();
            }
        }

        // --- 1. PAGE: MONITORING & VISUALISASI ---
        function getPageMonitoring() {
            return `
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Monitoring & Visualisasi</h2>
                    <p class="text-gray-500 mt-1">Pantau statistik panen, sebaran lahan, dan sistem peringatan dini hama</p>
                </div>

                <!-- Top Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Total Lahan</p>
                        <h3 class="text-3xl font-bold text-gray-800">560 Ha</h3>
                        <p class="text-xs text-gray-400 mt-1">Across 5 villages</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Total Petani</p>
                        <h3 class="text-3xl font-bold text-gray-800">208</h3>
                        <p class="text-xs text-gray-400 mt-1">Registered farmers</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Produktivitas Rata-rata</p>
                        <h3 class="text-3xl font-bold text-gray-800">5.2 ton/ha</h3>
                        <p class="text-xs text-green-500 mt-1 font-medium"><i class="fas fa-arrow-up mr-1"></i> +8% dari bulan lalu</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Peringatan Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-800">3</h3>
                        <p class="text-xs text-red-500 mt-1 font-medium">1 tinggi, 1 sedang, 1 rendah</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Line Chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-1">Statistik Panen (Ton)</h3>
                        <p class="text-xs text-gray-500 mb-6">Tren produksi 6 bulan terakhir</p>
                        <div class="h-64 relative">
                            <canvas id="harvestTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-1">Distribusi Komoditas</h3>
                        <p class="text-xs text-gray-500 mb-6">Persentase luas tanam</p>
                        <div class="h-48 relative flex justify-center items-center">
                            <canvas id="commodityPieChart"></canvas>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-gray-500">
                            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Padi 45%</div>
                            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>Jagung 30%</div>
                            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>Kedelai 15%</div>
                            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>Lainnya 10%</div>
                        </div>
                    </div>
                </div>

                <!-- GIS Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                    <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-map-marker-alt mr-2"></i>Peta Sebaran Lahan (GIS)</h3>
                    <p class="text-xs text-gray-500 mb-6">Distribusi lahan pertanian per desa</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Makmur</h4>
                                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600">
                                <p>Luas: <span class="font-semibold">120 Ha</span></p>
                                <p>Petani: <span class="font-semibold">45 orang</span></p>
                                <p class="text-[10px] text-gray-400">Koordinat: -6.2088, 106.8456</p>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Sejahtera</h4>
                                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600">
                                <p>Luas: <span class="font-semibold">95 Ha</span></p>
                                <p>Petani: <span class="font-semibold">38 orang</span></p>
                                <p class="text-[10px] text-gray-400">Koordinat: -6.2188, 106.8556</p>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Subur</h4>
                                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600">
                                <p>Luas: <span class="font-semibold">150 Ha</span></p>
                                <p>Petani: <span class="font-semibold">52 orang</span></p>
                                <p class="text-[10px] text-gray-400">Koordinat: -6.1988, 106.8356</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart: Village Comparison -->
                    <div class="h-64 relative">
                        <canvas id="villageStatsChart"></canvas>
                    </div>
                </div>

                <!-- EWS Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                     <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>Early Warning System - Hama & Penyakit</h3>
                     <p class="text-xs text-gray-500 mb-6">Peringatan dini serangan hama pada tanaman</p>
                     
                     <div class="space-y-4">
                        <div class="border border-red-200 rounded-lg p-4 bg-white">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                <span class="font-bold text-red-600 text-sm">Wereng Coklat</span>
                                <span class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded font-bold">TINGGI</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <p class="text-gray-500">Komoditas: <span class="font-semibold text-gray-800">Padi</span></p>
                                    <p class="text-gray-500 mt-1">Lokasi: <span class="font-semibold text-red-600">Desa Makmur, Desa Subur</span></p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tanggal Deteksi: <span class="font-semibold text-gray-800">1 Desember 2025</span></p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3 pt-3 border-t border-red-50">Tindakan segera diperlukan. Koordinasikan dengan petani untuk penanganan.</p>
                        </div>

                        <div class="border border-orange-200 rounded-lg p-4 bg-white">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-exclamation-circle text-orange-500"></i>
                                <span class="font-bold text-gray-800 text-sm">Ulat Grayak</span>
                                <span class="bg-orange-200 text-orange-800 text-[10px] px-2 py-0.5 rounded font-bold">SEDANG</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <p class="text-gray-500">Komoditas: <span class="font-semibold text-gray-800">Jagung</span></p>
                                    <p class="text-gray-500 mt-1">Lokasi: <span class="font-semibold text-gray-800">Desa Sejahtera</span></p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tanggal Deteksi: <span class="font-semibold text-gray-800">3 Desember 2025</span></p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3 pt-3 border-t border-orange-50">Pantau perkembangan dan siapkan langkah preventif.</p>
                        </div>
                     </div>
                </div>
            `;
        }

        // --- 2. PAGE: VALIDASI DATA ---
        function getPageValidasi() {
            return `
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Validasi Data</h2>
                    <p class="text-gray-500 mt-1">Verifikasi laporan dari petani dan kelola riwayat validasi</p>
                </div>

                <!-- 3 Statistik Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="flex items-center space-x-2 text-orange-500">
                            <i class="far fa-clock"></i>
                            <span class="text-sm font-medium text-gray-600">Menunggu Validasi</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">4</h3>
                            <p class="text-xs text-gray-400 mt-1">Laporan pending</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="flex items-center space-x-2 text-green-500">
                            <i class="far fa-check-circle"></i>
                            <span class="text-sm font-medium text-gray-600">Disetujui</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">2</h3>
                            <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="flex items-center space-x-2 text-red-500">
                            <i class="far fa-times-circle"></i>
                            <span class="text-sm font-medium text-gray-600">Ditolak</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">1</h3>
                            <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs (Tanpa Angka) -->
                <div class="flex space-x-2 mb-6">
                    <button onclick="state.activeTabValidasi='masuk'; render()" class="px-4 py-2 rounded-full text-sm font-medium transition ${state.activeTabValidasi === 'masuk' ? 'bg-gray-200 text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-100'}">
                        Laporan Masuk
                    </button>
                    <button onclick="state.activeTabValidasi='riwayat'; render()" class="px-4 py-2 rounded-full text-sm font-medium transition ${state.activeTabValidasi === 'riwayat' ? 'bg-gray-200 text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-100'}">
                        Riwayat Validasi
                    </button>
                </div>

                <!-- Table Content -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    ${state.activeTabValidasi === 'masuk' ? `
                    <!-- TAB LAPORAN MASUK -->
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Laporan Menunggu Verifikasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Review dan validasi laporan yang masuk dari petani</p>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white text-gray-500 text-xs border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-medium">ID</th>
                                <th class="px-6 py-4 font-medium">Petani</th>
                                <th class="px-6 py-4 font-medium">Lokasi</th>
                                <th class="px-6 py-4 font-medium">Jenis</th>
                                <th class="px-6 py-4 font-medium">Komoditas</th>
                                <th class="px-6 py-4 font-medium">Luas (Ha)</th>
                                <th class="px-6 py-4 font-medium">Hasil (Ton)</th>
                                <th class="px-6 py-4 font-medium">Tanggal</th>
                                <th class="px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            ${data.laporanMasuk.map(item => `
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 text-gray-500">${item.id}</td>
                                <td class="px-6 py-4 text-gray-800 flex items-center gap-2">
                                    <i class="far fa-user text-gray-400"></i> ${item.petani}
                                </td>
                                <td class="px-6 py-4 text-gray-600"><i class="fas fa-map-marker-alt text-gray-300 mr-1"></i> ${item.lokasi}</td>
                                <td class="px-6 py-4"><span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">${item.jenis}</span></td>
                                <td class="px-6 py-4 text-gray-600">${item.komoditas}</td>
                                <td class="px-6 py-4 text-gray-600">${item.luas}</td>
                                <td class="px-6 py-4 text-gray-600">${item.hasil}</td>
                                <td class="px-6 py-4 text-gray-600"><i class="far fa-calendar text-gray-400 mr-1"></i> ${item.tanggal}</td>
                                <td class="px-6 py-4">
                                    <button onclick="openModalReview('${item.id}')" class="border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-black hover:text-white transition text-xs font-bold flex items-center gap-2">
                                        <i class="far fa-eye"></i> Review
                                    </button>
                                </td>
                            </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    ` : `
                    <!-- TAB RIWAYAT VALIDASI -->
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Riwayat Validasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar laporan yang sudah divalidasi</p>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white text-gray-500 text-xs border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-medium">ID</th>
                                <th class="px-6 py-4 font-medium">Petani</th>
                                <th class="px-6 py-4 font-medium">Jenis</th>
                                <th class="px-6 py-4 font-medium">Komoditas</th>
                                <th class="px-6 py-4 font-medium">Tanggal</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Validator</th>
                                <th class="px-6 py-4 font-medium">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            ${data.riwayatValidasi.map(item => `
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">${item.id}</td>
                                <td class="px-6 py-4 text-gray-800">${item.petani}</td>
                                <td class="px-6 py-4"><span class="bg-gray-50 border border-gray-200 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">${item.jenis}</span></td>
                                <td class="px-6 py-4 text-gray-600">${item.komoditas}</td>
                                <td class="px-6 py-4 text-gray-600">${item.tanggal}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold flex items-center gap-1 w-fit ${item.status === 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                        <i class="fas ${item.status === 'Disetujui' ? 'fa-check-circle' : 'fa-times-circle'}"></i> ${item.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-xs">${item.validator}</td>
                                <td class="px-6 py-4 text-gray-500 text-xs italic">${item.catatan}</td>
                            </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    `}
                </div>
            `;
        }

        // --- 3. PAGE: LAPORAN ---
        function getPageLaporan() {
            return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Laporan & Reporting</h2><p class="text-gray-500 mt-1">Export laporan dan lihat rekapitulasi data pertanian</p></div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8"><h3 class="font-bold text-gray-800 text-lg mb-1 flex items-center gap-2"><i class="fas fa-download"></i> Export Laporan</h3><p class="text-sm text-gray-500 mb-6">Unduh laporan dalam format PDF atau Excel</p><div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6"><div><label class="block text-xs font-bold text-gray-700 mb-2">Periode</label><select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none"><option>Desember 2025</option></select></div><div><label class="block text-xs font-bold text-gray-700 mb-2">Desa</label><select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none"><option>Semua Desa</option></select></div><div><label class="block text-xs font-bold text-gray-700 mb-2">Komoditas</label><select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none"><option>Semua Komoditas</option></select></div></div><div class="flex gap-4"><button class="flex-1 bg-black text-white py-3 rounded-lg font-bold text-sm hover:bg-gray-800 transition"><i class="fas fa-file-pdf mr-2"></i> Export PDF</button><button class="flex-1 bg-white border border-gray-200 text-gray-700 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition"><i class="fas fa-file-excel mr-2"></i> Export Excel</button></div></div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8"><div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm"><p class="text-xs font-bold text-gray-500 mb-3">Total Lahan</p><h3 class="text-2xl font-bold text-gray-800">560 Ha</h3></div><div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm"><p class="text-xs font-bold text-gray-500 mb-3">Total Petani</p><h3 class="text-2xl font-bold text-gray-800">208</h3></div><div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm"><p class="text-xs font-bold text-gray-500 mb-3">Produksi Padi</p><h3 class="text-2xl font-bold text-gray-800">1425 Ton</h3></div><div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm"><p class="text-xs font-bold text-gray-500 mb-3">Produksi Jagung</p><h3 class="text-2xl font-bold text-gray-800">740 Ton</h3></div><div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm"><p class="text-xs font-bold text-gray-500 mb-3">Produksi Kedelai</p><h3 class="text-2xl font-bold text-gray-800">180 Ton</h3></div></div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8"><h3 class="font-bold text-gray-800 mb-2">Grafik Produksi per Desa</h3><div class="h-72 relative w-full mb-8"><canvas id="productionByVillageChart"></canvas></div></div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="p-6 border-b border-gray-100"><h3 class="font-bold text-gray-800">Rekapitulasi Per Desa</h3></div><div class="overflow-x-auto"><table class="w-full text-sm text-left"><thead class="bg-white text-gray-500 text-xs border-b border-gray-100"><tr><th class="px-6 py-4">Desa</th><th class="px-6 py-4">Total Lahan</th><th class="px-6 py-4">Jumlah Petani</th><th class="px-6 py-4">Padi</th><th class="px-6 py-4">Jagung</th><th class="px-6 py-4">Kedelai</th><th class="px-6 py-4">Produktivitas</th></tr></thead><tbody class="divide-y divide-gray-50">${data.rekapDesa.map(d => `<tr class="hover:bg-gray-50"><td class="px-6 py-4">${d.desa}</td><td class="px-6 py-4">${d.lahan} Ha</td><td class="px-6 py-4">${d.petani}</td><td class="px-6 py-4">${d.padi_ton} Ton</td><td class="px-6 py-4">${d.jagung_ton} Ton</td><td class="px-6 py-4">${d.kedelai_ton} Ton</td><td class="px-6 py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold">${d.prod} Ton/Ha</span></td></tr>`).join('')}</tbody></table></div></div>
            `;
        }

        // --- 4. PAGE: MASTER DATA ---
        function getPageMaster() {
            return `
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Master Data Management</h2>
                    <p class="text-gray-500 mt-1">Kelola data komoditas, hama, dan petani</p>
                </div>

                <!-- 3 Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
                        <div class="flex items-center space-x-2 text-green-500"><i class="fas fa-leaf"></i><span class="text-sm font-medium text-gray-600">Total Komoditas</span></div>
                        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">Varietas terdaftar</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
                        <div class="flex items-center space-x-2 text-red-500"><i class="fas fa-bug"></i><span class="text-sm font-medium text-gray-600">Referensi Hama</span></div>
                        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">Database hama</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
                        <div class="flex items-center space-x-2 text-blue-500"><i class="far fa-user"></i><span class="text-sm font-medium text-gray-600">Data Petani</span></div>
                        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">4 aktif</p></div>
                    </div>
                </div>

                <!-- Tabs & Content -->
                <div class="bg-gray-100 p-1 rounded-lg w-full flex mb-6">
                    <button onclick="state.activeTabMaster='komoditas'; render()" class="flex-1 py-2 rounded-md text-sm font-bold transition ${state.activeTabMaster === 'komoditas' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800'}">Komoditas</button>
                    <button onclick="state.activeTabMaster='hama'; render()" class="flex-1 py-2 rounded-md text-sm font-bold transition ${state.activeTabMaster === 'hama' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800'}">Hama & Penyakit</button>
                    <button onclick="state.activeTabMaster='petani'; render()" class="flex-1 py-2 rounded-md text-sm font-bold transition ${state.activeTabMaster === 'petani' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800'}">Data Petani</button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 min-h-[400px]">
                    ${state.activeTabMaster === 'komoditas' ? getTabKomoditas() : 
                      state.activeTabMaster === 'hama' ? getTabHama() : 
                      getTabPetani()}
                </div>
            `;
        }

        // --- SUB-FUNCTIONS FOR MASTER TABS ---
        function getTabKomoditas() {
            return `
                <div class="flex justify-between items-center mb-6">
                    <div><h3 class="font-bold text-gray-800 text-lg">Kelola Komoditas</h3><p class="text-sm text-gray-500">Tambah dan kelola data komoditas dan varietasnya</p></div>
                    <button onclick="openModal('modal-tambah-komoditas')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Komoditas</button>
                </div>
                <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Komoditas</th><th class="py-4 pr-4">Varietas</th><th class="py-4 pr-4">Musim Tanam</th><th class="py-4 pr-4">Estimasi Panen</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">${data.masterKomoditas.map(item => `<tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500">${item.id}</td><td class="py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-700">${item.nama}</span></td><td class="py-4 text-gray-600">${item.varietas}</td><td class="py-4 text-gray-600">${item.musim}</td><td class="py-4 text-gray-600">${item.estimasi}</td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>`).join('')}</tbody></table>
            `;
        }

        function getTabHama() {
            return `
                <div class="flex justify-between items-center mb-6">
                    <div><h3 class="font-bold text-gray-800 text-lg">Kelola Referensi Hama & Penyakit</h3><p class="text-sm text-gray-500">Database hama dan cara penanganannya</p></div>
                    <button onclick="openModal('modal-tambah-hama')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Hama</button>
                </div>
                <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Hama</th><th class="py-4 pr-4">Nama Latin</th><th class="py-4 pr-4">Komoditas</th><th class="py-4 pr-4">Tingkat Bahaya</th><th class="py-4 pr-4">Penanganan</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">${data.masterHama.map(item => `<tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500">${item.id}</td><td class="py-4 text-gray-800 font-medium">${item.nama}</td><td class="py-4 text-gray-500 italic">${item.latin}</td><td class="py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-700">${item.komoditas}</span></td><td class="py-4"><span class="px-2 py-1 rounded text-[10px] font-bold text-white ${item.bahaya === 'Tinggi' ? 'bg-red-500' : 'bg-orange-400'}">${item.bahaya}</span></td><td class="py-4 text-gray-600 text-xs max-w-xs truncate" title="${item.penanganan}">${item.penanganan}</td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>`).join('')}</tbody></table>
            `;
        }

        function getTabPetani() {
            return `
                <div class="flex justify-between items-center mb-6">
                    <div><h3 class="font-bold text-gray-800 text-lg">Kelola Data Petani</h3><p class="text-sm text-gray-500">Database petani terdaftar dalam sistem</p></div>
                    <button onclick="openModal('modal-tambah-petani')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Petani</button>
                </div>
                <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama</th><th class="py-4 pr-4">NIK</th><th class="py-4 pr-4">Desa</th><th class="py-4 pr-4">Total Lahan</th><th class="py-4 pr-4">Komoditas</th><th class="py-4 pr-4">Telepon</th><th class="py-4 pr-4">Status</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">${data.masterPetani.map(item => `<tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500">${item.id}</td><td class="py-4 text-gray-800 font-medium">${item.nama}</td><td class="py-4 text-gray-500 text-xs">${item.nik}</td><td class="py-4 text-gray-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-gray-300"></i> ${item.desa}</td><td class="py-4 text-gray-600">${item.lahan}</td><td class="py-4 text-gray-600 text-xs">${item.komoditas}</td><td class="py-4 text-gray-500 text-xs">${item.telp}</td><td class="py-4"><span class="px-2 py-1 rounded text-[10px] font-bold ${item.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">${item.status}</span></td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>`).join('')}</tbody></table>
            `;
        }

        // --- CHART JS FUNCTIONS (FULLY RESTORED) ---
        function initMonitoringCharts() {
            setTimeout(() => {
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
            }, 100);
        }

        function initLaporanCharts() {
            setTimeout(() => {
                const ctx = document.getElementById('productionByVillageChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Makmur', 'Sejahtera', 'Subur', 'Tani Jaya', 'Hijau'],
                            datasets: [
                                { label: 'Padi (Ton)', data: [300, 250, 400, 275, 200], backgroundColor: '#22c55e', barPercentage: 0.6 },
                                { label: 'Jagung (Ton)', data: [160, 120, 200, 140, 120], backgroundColor: '#f59e0b', barPercentage: 0.6 },
                                { label: 'Kedelai (Ton)', data: [40, 30, 40, 40, 30], backgroundColor: '#8b5cf6', barPercentage: 0.6 }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                            scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } }
                        }
                    });
                }
            }, 100);
        }

        // --- ACTIONS ---
        function navigate(page) {
            state.currentPage = page;
            render();
        }

        function openModalReview(id) {
            const laporan = data.laporanMasuk.find(l => l.id === id);
            if (!laporan) return;
            const container = document.getElementById('modal-container');
            container.innerHTML = `
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 text-lg">Detail Laporan - ${id}</h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="p-6 text-sm">
                            <p class="text-gray-500 text-xs mb-4">Review dan validasi laporan dari petani</p>
                            
                            <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-6">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Petani</p>
                                    <p class="font-bold text-gray-800">${laporan.petani}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Lokasi</p>
                                    <p class="font-bold text-gray-800">${laporan.lokasi}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Jenis Aktivitas</p>
                                    <p class="font-bold text-gray-800">${laporan.jenis}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Komoditas</p>
                                    <p class="font-bold text-gray-800">${laporan.komoditas}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Luas Lahan</p>
                                    <p class="font-bold text-gray-800">${laporan.luas} Ha</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Hasil Panen</p>
                                    <p class="font-bold text-gray-800">${laporan.hasil} Ton</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Aktivitas</p>
                                    <p class="font-bold text-gray-800">1/12/2025</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Submit</p>
                                    <p class="font-bold text-gray-800">2/12/2025</p>
                                </div>
                            </div>

                            ${laporan.produktivitas !== '-' ? `
                            <div class="mb-6">
                                <p class="text-xs text-gray-500 mb-1">Produktivitas</p>
                                <p class="font-bold text-gray-900 text-xl">${laporan.produktivitas} Ton/Ha</p>
                            </div>
                            ` : ''}

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-2">Catatan Validasi</label>
                                <textarea class="w-full border border-gray-300 rounded-lg p-3 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none" placeholder="Masukkan catatan validasi..."></textarea>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-100 flex space-x-3 bg-gray-50">
                            <button onclick="closeModal(); alert('Laporan Disetujui!')" class="flex-1 bg-black text-white py-2.5 rounded-lg font-bold hover:bg-gray-800 shadow-md transition flex items-center justify-center gap-2">
                                <i class="far fa-check-circle"></i> Setujui
                            </button>
                            <button onclick="closeModal(); alert('Laporan Ditolak!')" class="flex-1 bg-red-600 text-white py-2.5 rounded-lg font-bold hover:bg-red-700 shadow-md transition flex items-center justify-center gap-2">
                                <i class="far fa-times-circle"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>`;
            document.body.classList.add('modal-active');
        }

        // --- NEW MODALS FOR MASTER DATA ---
        function openModal(modalId) {
            const container = document.getElementById('modal-container');
            let content = '';

            if (modalId === 'modal-tambah-komoditas') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Komoditas Baru</h3><p class="text-sm text-gray-500 mt-1">Masukkan informasi komoditas baru</p></div>
                    <div class="p-6 space-y-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Komoditas</label><input type="text" placeholder="Contoh: Padi" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Varietas</label><input type="text" placeholder="Contoh: IR64" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Musim Tanam</label><input type="text" placeholder="Contoh: Sepanjang tahun" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Panen</label><input type="text" placeholder="Contoh: 100-120 hari" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div></div>
                    <div class="p-6 border-t border-gray-100"><button onclick="closeModal(); alert('Komoditas ditambahkan!')" class="w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">Simpan</button></div>
                </div>`;
            } 
            else if (modalId === 'modal-tambah-hama') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Referensi Hama Baru</h3><p class="text-sm text-gray-500 mt-1">Masukkan informasi hama dan cara penanganannya</p></div>
                    <div class="p-6 space-y-4"><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Hama</label><input type="text" placeholder="Contoh: Wereng Coklat" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Latin</label><input type="text" placeholder="Contoh: Nilaparvata lugens" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div></div><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Komoditas Sasaran</label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"><option>Pilih komoditas</option><option>Padi</option></select></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Tingkat Bahaya</label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"><option>Pilih tingkat</option><option>Tinggi</option></select></div></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Cara Penanganan</label><textarea class="w-full border border-gray-300 rounded-lg p-2.5 text-sm h-24 resize-none outline-none" placeholder="Jelaskan cara penanganan hama..."></textarea></div></div>
                    <div class="p-6 border-t border-gray-100"><button onclick="closeModal(); alert('Data Hama ditambahkan!')" class="w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">Simpan</button></div>
                </div>`;
            }
            else if (modalId === 'modal-tambah-petani') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Data Petani Baru</h3><p class="text-sm text-gray-500 mt-1">Masukkan informasi petani baru</p></div>
                    <div class="p-6 space-y-4"><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label><input type="text" placeholder="Nama petani" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">NIK</label><input type="text" placeholder="3201012345678901" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div></div><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Desa</label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"><option>Pilih desa</option></select></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Nomor Telepon</label><input type="text" placeholder="081234567890" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div></div><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Total Lahan (Ha)</label><input type="text" placeholder="2.5" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div><div><label class="block text-xs font-bold text-gray-700 mb-1">Komoditas Utama</label><input type="text" placeholder="Padi, Jagung" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div></div></div>
                    <div class="p-6 border-t border-gray-100"><button onclick="closeModal(); alert('Data Petani ditambahkan!')" class="w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">Simpan</button></div>
                </div>`;
            }

            container.innerHTML = `<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">${content}</div>`;
            document.body.classList.add('modal-active');
        }

        function closeModal() {
            document.getElementById('modal-container').innerHTML = '';
            document.body.classList.remove('modal-active');
        }

        // Initialize
        render();

    </script>
</body>
</html>
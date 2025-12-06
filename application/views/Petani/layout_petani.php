<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriPlatform - Farm Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            green: '#22c55e', 
                            dark: '#0f172a',
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
        
        /* Modal Transition */
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: hidden !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div id="app" class="h-screen w-full overflow-hidden flex">
        <!-- Content injected by JS -->
    </div>

    <!-- MODAL CONTAINER (Hidden by default) -->
    <div id="modal-container"></div>

    <script>
        // --- STATE MANAGEMENT ---
        const state = {
            currentView: 'petani', 
            currentPage: 'lahan', // Default ke lahan untuk melihat tombol
            activeTabSiklus: 'aktif',
            activeTabInfo: 'cuaca',
            isEditingProfil: false,
            user: {
                name: 'rzayyanrizka1',
                role: 'Pemilik Lahan',
                avatar: 'R'
            }
        };

        // --- MOCK DATA ---
        const data = {
            lahan: [
                { id: 'A-01', lokasi: 'Desa Makmur, Kec. Subang', luas: '25 hektar', tanah: 'Aluvial', tanaman: 'Padi', status: 'Aktif', progress: 45, fase: 'Fase Vegetatif' },
                { id: 'B-02', lokasi: 'Desa Sejahtera, Kec. Karawang', luas: '30 hektar', tanah: 'Latosol', tanaman: 'Jagung', status: 'Aktif', progress: 95, fase: 'Siap Panen' },
                { id: 'C-03', lokasi: 'Desa Tani Maju, Kec. Subang', luas: '20 hektar', tanah: 'Aluvial', tanaman: 'Kedelai', status: 'Aktif', progress: 60, fase: 'Fase Pembungaan' },
                { id: 'D-04', lokasi: 'Desa Sukamaju, Kec. Purwakarta', luas: '15 hektar', tanah: 'Andosol', tanaman: '-', status: 'Istirahat', progress: 20, fase: 'Penanaman' },
                { id: 'E-05', lokasi: 'Desa Mandiri, Kec. Karawang', luas: '18 hektar', tanah: 'Latosol', tanaman: '-', status: 'Istirahat', progress: 0, fase: 'Persiapan' },
            ],
            harga: [
                { nama: 'Padi Kering Giling', lokasi: 'Jawa Barat', harga: 'Rp 6.500', unit: '/kg', tren: 'naik', persentase: '+5%' },
                { nama: 'Jagung Pipilan Kering', lokasi: 'Jawa Barat', harga: 'Rp 4.800', unit: '/kg', tren: 'naik', persentase: '+3%' },
                { nama: 'Kedelai Lokal', lokasi: 'Jawa Barat', harga: 'Rp 9.500', unit: '/kg', tren: 'turun', persentase: '-2%' },
                { nama: 'Cabai Merah Keriting', lokasi: 'Jawa Barat', harga: 'Rp 45.000', unit: '/kg', tren: 'naik', persentase: '+15%' },
                { nama: 'Cabai Rawit Merah', lokasi: 'Jawa Barat', harga: 'Rp 65.000', unit: '/kg', tren: 'naik', persentase: '+20%' },
                { nama: 'Tomat', lokasi: 'Jawa Barat', harga: 'Rp 8.500', unit: '/kg', tren: 'turun', persentase: '-5%' },
                { nama: 'Bawang Merah', lokasi: 'Jawa Barat', harga: 'Rp 38.000', unit: '/kg', tren: 'naik', persentase: '+8%' },
                { nama: 'Bawang Putih', lokasi: 'Jawa Barat', harga: 'Rp 42.000', unit: '/kg', tren: 'naik', persentase: '+2%' },
            ],
            kontak: [
                { nama: 'Dinas Pertanian Kab. Subang', tag: 'Dinas Pertanian', desc: 'Informasi program pertanian, bantuan pupuk, dan bimbingan teknis', telp: '0260-411234' },
                { nama: 'PPL Kecamatan', tag: 'Penyuluh Pertanian', desc: 'Konsultasi teknis budidaya, penanganan hama, dan praktek pertanian', telp: '0812-3456-7890' },
                { nama: 'Pusat Informasi Pasar', tag: 'Info Pasar & Harga', desc: 'Informasi harga komoditas, akses pasar, dan peluang penjualan', telp: '0260-555-123' },
            ],
            profil: {
                namaLengkap: 'rzayyanrizka1',
                role: 'Kebun Makmur',
                email: 'petani@example.com',
                alamat: 'Desa Makmur, Kec. Subang, Jawa Barat',
                namaPertanian: 'Kebun Makmur',
                telepon: '0812-3456-7890',
                bio: 'Petani dengan pengalaman 15 tahun di bidang pertanian padi dan palawija.'
            }
        };

        function render() {
            const app = document.getElementById('app');
            app.innerHTML = renderLayout(state.currentView);
        }

        // --- 1. SIDEBAR & LAYOUT UTAMA ---
        function renderLayout(role) {
            const sidebarItems = [
                { id: 'dashboard', icon: 'fa-home', label: 'Dashboard' },
                { id: 'lahan', icon: 'fa-leaf', label: 'Dashboard Lahan' },
                { id: 'siklus', icon: 'fa-calendar-alt', label: 'Siklus & Aktivitas' },
                { id: 'info', icon: 'fa-info-circle', label: 'Informasi & Bantuan' },
                { id: 'profil', icon: 'fa-user', label: 'Profil' },
            ];

            return `
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
                        ${sidebarItems.map(item => `
                            <button onclick="navigate('${item.id}')" 
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                                ${state.currentPage === item.id 
                                    ? 'bg-green-600 text-white shadow-md shadow-green-200' 
                                    : 'text-gray-600 hover:bg-green-50 hover:text-green-700'}">
                                <i class="fas ${item.icon} w-5 ${state.currentPage === item.id ? 'text-white' : 'text-gray-500 group-hover:text-green-600'}"></i>
                                <span>${item.label}</span>
                            </button>
                        `).join('')}
                    </nav>
                </div>

                <div class="p-4 border-t border-gray-100">
                    <div class="bg-gray-50 rounded-xl p-3 flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                            ${state.user.avatar}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <h4 class="text-sm font-bold text-gray-800 truncate">${state.user.name}</h4>
                            <p class="text-xs text-gray-500 truncate">${state.user.role}</p>
                        </div>
                    </div>
                    <button onclick="logout()" class="w-full flex items-center px-2 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Keluar</span>
                    </button>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto h-full bg-white relative">
                <div class="md:hidden sticky top-0 z-30 bg-white border-b p-4 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                         <i class="fas fa-leaf text-green-500"></i>
                         <span class="font-bold">AgriPlatform</span>
                    </div>
                    <button class="text-gray-600"><i class="fas fa-bars"></i></button>
                </div>
                <div class="p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">
                    ${getContent(role, state.currentPage)}
                </div>
            </main>
            `;
        }

        // --- 2. ROUTING KONTEN ---
        function getContent(role, page) {
            switch(page) {
                case 'dashboard': return getPetaniDashboard();
                case 'lahan': return getPetaniLahan();
                case 'siklus': return getPetaniSiklus();
                case 'info': return getPetaniInfo();
                case 'profil': return getPetaniProfil();
                default: return getPetaniDashboard();
            }
        }

        // --- PAGE FUNCTIONS --- 
        function getPetaniDashboard() {
            return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2><p class="text-gray-500 mt-1">Ringkasan kondisi lahan dan cuaca hari ini</p></div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4"><div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-leaf text-lg"></i></div><div><p class="text-xs text-gray-400 font-medium">Total Lahan</p><h3 class="text-lg font-bold text-gray-800">8 lahan</h3><p class="text-[10px] text-gray-400">Total luas 156 ha</p></div></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4"><div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-chart-line text-lg"></i></div><div><p class="text-xs text-gray-400 font-medium">Lahan Aktif</p><h3 class="text-lg font-bold text-gray-800">5 lahan</h3><p class="text-[10px] text-gray-400">3 lahan sedang istirahat</p></div></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4"><div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-calendar-alt text-lg"></i></div><div><p class="text-xs text-gray-400 font-medium">Siklus Tanam</p><h3 class="text-lg font-bold text-gray-800">6 siklus</h3><p class="text-[10px] text-gray-400">2 siap panen</p></div></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4"><div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500"><i class="fas fa-sun text-lg"></i></div><div><p class="text-xs text-gray-400 font-medium">Suhu Hari Ini</p><h3 class="text-lg font-bold text-gray-800">28°C</h3><p class="text-[10px] text-gray-400">Cerah berawan</p></div></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm h-fit"><div class="mb-6"><h3 class="text-gray-500 font-medium text-sm flex items-center gap-2"><i class="fas fa-cloud text-blue-400"></i> Cuaca Hari Ini</h3><p class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1 text-gray-300"></i> Lokasi Lahan Anda</p></div><div class="flex justify-between items-center mb-8"><div><h2 class="text-5xl font-bold text-gray-800 tracking-tight">28°C</h2><p class="text-gray-500 mt-1 text-sm">Cerah Berawan</p></div><div class="text-yellow-400 text-6xl"><i class="fas fa-sun"></i></div></div><div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4 mb-6"><div class="text-left"><p class="text-[10px] text-gray-400 uppercase tracking-wide">Kelembaban</p><p class="font-bold text-gray-700 text-lg">65% <span class="text-blue-500 text-xs"><i class="fas fa-tint"></i></span></p></div><div class="text-left border-l border-gray-100 pl-4"><p class="text-[10px] text-gray-400 uppercase tracking-wide">Angin</p><p class="font-bold text-gray-700 text-lg">12 <span class="text-sm font-normal text-gray-500">km/h</span></p></div></div><h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Prakiraan 5 Hari</h4><div class="flex justify-between text-center px-1">${['Sen', 'Sel', 'Rab', 'Kam', 'Jum'].map((day, idx) => `<div class="flex flex-col items-center space-y-2"><span class="text-[10px] text-gray-400 font-medium">${day}</span><i class="fas ${['fa-sun', 'fa-cloud-sun', 'fa-cloud-rain', 'fa-cloud-showers-heavy', 'fa-sun'][idx]} text-${['yellow-400', 'yellow-400', 'blue-400', 'blue-600', 'yellow-400'][idx]} text-sm"></i><span class="text-xs font-bold text-gray-700">${[28, 29, 27, 26, 28][idx]}°</span></div>`).join('')}</div></div>
                    <div class="lg:col-span-2"><div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Lahan Aktif</h3><p class="text-sm text-gray-400">Status lahan yang sedang ditanami</p></div><div class="bg-white border border-gray-100 rounded-2xl p-1 shadow-sm"><div class="divide-y divide-gray-50">${data.lahan.filter(l => l.status === 'Aktif').map(l => `<div class="p-4 hover:bg-gray-50 transition rounded-xl"><div class="flex justify-between items-start mb-3"><div class="flex items-start space-x-3"><div class="bg-green-50 w-10 h-10 rounded-lg flex items-center justify-center text-green-600 shrink-0"><i class="fas fa-seedling"></i></div><div><h4 class="font-bold text-gray-800 text-sm">Lahan ${l.id} • ${l.tanaman}</h4><p class="text-xs text-gray-400 mt-0.5">${l.luas} • ${l.fase}</p></div></div><span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">${l.progress}%</span></div><div class="w-full bg-gray-100 rounded-full h-1.5 mt-2"><div class="bg-green-500 h-1.5 rounded-full" style="width: ${l.progress}%"></div></div></div>`).join('')}</div></div></div>
                </div>
            `;
        }
        function getPetaniLahan() {
             return `
                <div class="flex justify-between items-center mb-8"><div><h2 class="text-2xl font-bold text-gray-900">Dashboard Lahan</h2><p class="text-gray-500 mt-1">Kelola semua lahan pertanian Anda</p></div><button onclick="openModal('modal-tambah-lahan')" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 shadow-lg flex items-center"><i class="fas fa-plus mr-2 text-xs"></i>Tambah Lahan</button></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"><div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm"><div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-map-marker-alt"></i></div><div><p class="text-xs text-gray-400">Total Lahan</p><h3 class="text-2xl font-bold text-gray-800">5</h3></div></div><div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm"><div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-expand"></i></div><div><p class="text-xs text-gray-400">Total Luas</p><h3 class="text-2xl font-bold text-gray-800">108 ha</h3></div></div><div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm"><div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-map-pin"></i></div><div><p class="text-xs text-gray-400">Lahan Aktif</p><h3 class="text-2xl font-bold text-gray-800">3</h3></div></div></div>
                <div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Daftar Lahan</h3><p class="text-sm text-gray-400">Semua lahan yang terdaftar dalam sistem</p></div>
                <div class="space-y-4">${data.lahan.map(l => `<div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition"><div class="flex justify-between items-start mb-6"><div class="flex items-center space-x-3"><h4 class="font-bold text-gray-800">Lahan ${l.id}</h4><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide ${l.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">${l.status}</span></div><button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12"><div><div class="flex items-center text-gray-400 text-xs mb-1"><i class="fas fa-map-marker-alt mr-2 w-4"></i> Lokasi</div><p class="text-sm font-medium text-gray-800 pl-6">${l.lokasi}</p></div><div><div class="flex items-center text-gray-400 text-xs mb-1"><i class="fas fa-expand mr-2 w-4"></i> Luas</div><p class="text-sm font-medium text-gray-800 pl-6">${l.luas}</p></div><div><div class="flex items-center text-gray-400 text-xs mb-1"><p class="text-xs">Jenis Tanah</p></div><p class="text-sm font-bold text-gray-800 mt-1">${l.tanah}</p><p class="text-[10px] text-gray-400 italic mt-1">Sistem irigasi teknis, akses jalan baik</p></div><div><div class="flex items-center text-gray-400 text-xs mb-1"><p class="text-xs">Tanaman Saat Ini</p></div><p class="text-sm font-bold text-gray-800 mt-1">${l.tanaman}</p></div></div></div>`).join('')}</div>
            `;
        }
        function getPetaniSiklus() {
            return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Siklus & Aktivitas</h2><p class="text-gray-500 mt-1">Kelola siklus tanam dan aktivitas lahan</p></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <button onclick="openModal('modal-mulai')" class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group"><div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl group-hover:bg-green-600 group-hover:text-white transition"><i class="fas fa-seedling"></i></div><div><h3 class="font-bold text-gray-800">Mulai Siklus Baru</h3><p class="text-xs text-gray-400 mt-1">Tanam tanaman baru</p></div></button>
                    <button onclick="openModal('modal-panen')" class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group"><div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xl group-hover:bg-orange-600 group-hover:text-white transition"><i class="fas fa-cube"></i></div><div><h3 class="font-bold text-gray-800">Lapor Hasil Panen</h3><p class="text-xs text-gray-400 mt-1">Catat hasil panen</p></div></button>
                    <button onclick="openModal('modal-hama')" class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group"><div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:bg-red-600 group-hover:text-white transition"><i class="fas fa-bug"></i></div><div><h3 class="font-bold text-gray-800">Lapor Serangan Hama</h3><p class="text-xs text-gray-400 mt-1">Laporkan hama/penyakit</p></div></button>
                </div>
                <div class="flex space-x-4 border-b border-gray-200 mb-6">
                    <button onclick="state.activeTabSiklus='aktif'; render()" class="pb-3 text-sm font-medium border-b-2 transition ${state.activeTabSiklus === 'aktif' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'}">Siklus Tanam Aktif</button>
                    <button onclick="state.activeTabSiklus='riwayat'; render()" class="pb-3 text-sm font-medium border-b-2 transition ${state.activeTabSiklus === 'riwayat' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'}">Riwayat Panen</button>
                    <button onclick="state.activeTabSiklus='laporan'; render()" class="pb-3 text-sm font-medium border-b-2 transition ${state.activeTabSiklus === 'laporan' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'}">Laporan Hama</button>
                </div>
                <div>
                     ${state.activeTabSiklus === 'aktif' ? `<div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Siklus Tanam yang Sedang Berjalan</h3><p class="text-sm text-gray-400">Daftar tanaman yang sedang dalam masa tanam</p></div><div class="space-y-4"><div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm"><div class="flex justify-between items-start mb-6"><div class="flex items-center space-x-4"><div class="w-10 h-10 bg-green-50 rounded text-green-600 flex items-center justify-center"><i class="fas fa-seedling"></i></div><div><h4 class="font-bold text-gray-800">Padi - Lahan A-01</h4><p class="text-xs text-gray-500">25 hektar</p></div></div><span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Tumbuh</span></div><div class="grid grid-cols-2 gap-4 text-sm"><div><p class="text-gray-400 text-xs mb-1">Tanggal Tanam</p><p class="font-bold text-gray-800">15 Mar 2024</p></div><div class="text-right"><p class="text-gray-400 text-xs mb-1">Estimasi Panen</p><p class="font-bold text-gray-800">15 Agt 2024</p></div></div></div></div>` : state.activeTabSiklus === 'laporan' ? `<div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Laporan Hama & Penyakit</h3><p class="text-sm text-gray-400">Riwayat serangan hama dan penanganannya</p></div><div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm"><div class="flex justify-between items-start mb-4"><div class="flex items-center space-x-4"><div class="w-10 h-10 bg-red-50 rounded text-red-600 flex items-center justify-center"><i class="fas fa-bug"></i></div><div><h4 class="font-bold text-gray-800">Wereng Coklat</h4><p class="text-xs text-gray-500">Lahan A-01 • 10 Jun 2024</p></div></div><div class="flex space-x-2"><span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Sedang</span><span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">Ditangani</span></div></div><div class="mt-4"><p class="text-gray-400 text-xs mb-1">Tindakan:</p><p class="font-bold text-gray-800 text-sm">Penyemprotan pestisida organik</p></div></div>` : `<div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Riwayat Hasil Panen</h3><p class="text-sm text-gray-400">Data panen dari berbagai siklus tanam</p></div><div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm"><div class="flex justify-between items-start mb-4"><div class="flex items-center space-x-4"><div class="w-10 h-10 bg-orange-50 rounded text-orange-600 flex items-center justify-center"><i class="fas fa-cube"></i></div><div><h4 class="font-bold text-gray-800">Kedelai - Lahan C-03</h4><p class="text-xs text-gray-500">20 Nov 2024</p></div></div><span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">Baik</span></div><div class="mt-4"><p class="text-gray-400 text-xs mb-1">Hasil Panen</p><p class="font-bold text-gray-800 text-xl">4.2 ton/ha</p><p class="text-xs text-gray-400 italic mt-2">Panen berjalan lancar, kualitas baik</p></div></div>`}
                </div>
            `;
        }
        function getPetaniInfo() {
            return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Informasi & Bantuan</h2><p class="text-gray-500 mt-1">Info cuaca, harga pasar, dan kontak bantuan</p></div>
                <div class="w-full bg-gray-100 p-1 rounded-lg flex mb-8">
                    <button onclick="state.activeTabInfo='cuaca'; render()" class="flex-1 py-2 rounded-md text-sm font-medium transition-all ${state.activeTabInfo === 'cuaca' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900'}">Cuaca & Rekomendasi</button>
                    <button onclick="state.activeTabInfo='harga'; render()" class="flex-1 py-2 rounded-md text-sm font-medium transition-all ${state.activeTabInfo === 'harga' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900'}">Harga Pasar</button>
                    <button onclick="state.activeTabInfo='kontak'; render()" class="flex-1 py-2 rounded-md text-sm font-medium transition-all ${state.activeTabInfo === 'kontak' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900'}">Kontak Bantuan</button>
                </div>
                <div class="animate-fade-in">${getInfoContent()}</div>
            `;
        }

        function getInfoContent() {
            if (state.activeTabInfo === 'cuaca') return `<div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8"><div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-cloud text-blue-500"></i> Prakiraan Cuaca 5 Hari</h3><p class="text-sm text-gray-500 mt-1">Pantau kondisi cuaca untuk perencanaan aktivitas</p></div><div class="grid grid-cols-2 md:grid-cols-5 gap-4"><div class="border-2 border-blue-500 bg-blue-50 rounded-xl p-4 text-center"><p class="text-xs font-bold text-gray-800 mb-1">Hari Ini</p><p class="text-[10px] text-gray-500 mb-3">4 Des</p><i class="fas fa-sun text-yellow-400 text-2xl mb-2"></i><h4 class="text-xl font-bold text-gray-800">28°C</h4><p class="text-[10px] text-gray-500 mb-2">Cerah Berawan</p></div>${['Besok', 'Jumat', 'Sabtu', 'Minggu'].map((d, i) => `<div class="border border-gray-100 rounded-xl p-4 text-center"><p class="text-xs font-bold text-gray-800 mb-1">${d}</p><p class="text-[10px] text-gray-500 mb-3">${5+i} Des</p><i class="fas ${i===0?'fa-sun text-yellow-500':i===1?'fa-cloud-rain text-blue-400':i===2?'fa-cloud-showers-heavy text-blue-600':'fa-cloud text-gray-300'} text-2xl mb-2"></i><h4 class="text-xl font-bold text-gray-800">${[29, 27, 26, 27][i]}°C</h4><p class="text-[10px] text-gray-500 mb-2">${['Cerah', 'Hujan Ringan', 'Hujan', 'Berawan'][i]}</p></div>`).join('')}</div></div>`;
            if (state.activeTabInfo === 'harga') return `<div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8"><div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-dollar-sign text-green-500"></i> Harga Komoditas Pertanian</h3><p class="text-sm text-gray-500 mt-1">Update harga pasar terkini (per 4 Desember 2024)</p></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">${data.harga.map(item => `<div class="border border-gray-100 rounded-xl p-4 flex justify-between items-start hover:shadow-md transition"><div><h4 class="font-bold text-gray-800 text-sm">${item.nama}</h4><p class="text-xs text-gray-400 mt-0.5">${item.lokasi}</p><div class="mt-2 flex items-baseline"><span class="text-xl font-bold text-gray-900">${item.harga}</span><span class="text-xs text-gray-500 ml-1">${item.unit}</span></div></div><span class="text-xs font-bold px-2 py-1 rounded-full ${item.tren === 'naik' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">${item.tren === 'naik' ? '<i class="fas fa-arrow-up mr-1"></i>' : '<i class="fas fa-arrow-down mr-1"></i>'}${item.persentase}</span></div>`).join('')}</div></div>`;
            return `<div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8"><div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-phone-alt text-green-500"></i> Kontak Bantuan Pertanian</h3><p class="text-sm text-gray-500 mt-1">Hubungi layanan bantuan untuk konsultasi dan dukungan</p></div><div class="space-y-4 mb-8">${data.kontak.map(k => `<div class="border border-gray-100 rounded-xl p-6 flex flex-col md:flex-row gap-4 items-start md:items-center bg-white hover:border-green-200 transition"><div class="bg-green-50 w-12 h-12 rounded-lg flex items-center justify-center text-green-600 shrink-0"><i class="fas fa-phone"></i></div><div class="flex-1"><span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded mb-2 inline-block">${k.tag}</span><h4 class="font-bold text-gray-800">${k.nama}</h4><p class="text-xs text-gray-500 mt-1">${k.desc}</p></div><div class="flex gap-2 w-full md:w-auto"><button class="bg-gray-900 text-white text-xs font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 flex items-center gap-2 flex-1 justify-center md:flex-none"><i class="fas fa-phone-alt"></i> ${k.telp}</button><button class="bg-white border border-gray-200 text-gray-700 text-xs font-medium px-4 py-2.5 rounded-lg hover:bg-gray-50 flex items-center gap-2 flex-1 justify-center md:flex-none"><i class="far fa-comment-alt"></i> Kirim Pesan</button></div></div>`).join('')}</div></div>`;
        }

        // --- NAVIGATION ---
        function navigate(pageId) {
            state.currentPage = pageId;
            render();
        }
        function logout() {
            state.currentView = 'login';
            alert("Keluar sistem...");
        }
        
        // --- NEW: TOGGLE EDIT PROFILE ---
        function toggleEditProfile(isEditing) {
            state.isEditingProfil = isEditing;
            render();
        }

        // --- NEW: getPetaniProfil Function Definition ---
        function getPetaniProfil() {
            if (state.isEditingProfil) {
                return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2><p class="text-gray-500 mt-1">Kelola informasi profil dan akun Anda</p></div>
                
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row overflow-hidden animate-fade-in">
                    <!-- Left Column (Edit Mode) -->
                    <div class="w-full md:w-1/3 p-8 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col items-center justify-center text-center">
                        <div class="w-32 h-32 rounded-full bg-green-500 text-white flex items-center justify-center text-6xl font-normal mb-6">${state.user.avatar}</div>
                        <h3 class="text-xl font-bold text-gray-900">${data.profil.namaLengkap}</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-6">${data.profil.role}</p>
                        
                        <button onclick="toggleEditProfile(false)" class="flex items-center space-x-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition">
                            <i class="far fa-edit"></i>
                            <span>Batal Edit</span>
                        </button>
                    </div>

                    <!-- Right Column (Edit Form) -->
                    <div class="w-full md:w-2/3 p-8">
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-800 text-lg">Informasi Profil</h3>
                            <p class="text-gray-500 text-sm mt-1">Detail informasi akun dan kontak Anda</p>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" value="${data.profil.namaLengkap}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lahan/Pertanian</label>
                                    <input type="text" value="${data.profil.namaPertanian}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" value="${data.profil.email}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                    <input type="text" value="${data.profil.telepon}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <input type="text" value="${data.profil.alamat}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                <textarea class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none transition">${data.profil.bio}</textarea>
                            </div>

                            <div class="pt-4 flex items-center space-x-3">
                                <button onclick="toggleEditProfile(false); alert('Perubahan disimpan!')" class="px-6 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg transition">Simpan Perubahan</button>
                                <button onclick="toggleEditProfile(false)" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 opacity-50 pointer-events-none select-none">
                     <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-green-500 mb-1">8</h4><p class="text-gray-500 text-sm">Total Lahan</p></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-blue-500 mb-1">156</h4><p class="text-gray-500 text-sm">Total Luas (ha)</p></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-orange-500 mb-1">24</h4><p class="text-gray-500 text-sm">Siklus Selesai</p></div>
                </div>
                `;
            } else {
                return `
                <div class="mb-8"><h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2><p class="text-gray-500 mt-1">Kelola informasi profil dan akun Anda</p></div>
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row overflow-hidden animate-fade-in">
                    <div class="w-full md:w-1/3 p-8 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col items-center justify-center text-center">
                        <div class="w-32 h-32 rounded-full bg-green-500 text-white flex items-center justify-center text-6xl font-normal mb-6">${state.user.avatar}</div>
                        <h3 class="text-xl font-bold text-gray-900">${data.profil.namaLengkap}</h3><p class="text-gray-500 text-sm mt-1 mb-6">${data.profil.role}</p>
                        <button onclick="toggleEditProfile(true)" class="flex items-center space-x-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition"><i class="far fa-edit"></i><span>Edit Profil</span></button>
                    </div>
                    <div class="w-full md:w-2/3 p-8">
                        <div class="mb-6"><h3 class="font-bold text-gray-800 text-lg">Informasi Profil</h3><p class="text-gray-500 text-sm mt-1">Detail informasi akun dan kontak Anda</p></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                            <div><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-user w-5"></i> Nama Lengkap</label><p class="font-medium text-gray-800">${data.profil.namaLengkap}</p></div>
                            <div><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-map-marker-alt w-5"></i> Nama Pertanian</label><p class="font-medium text-gray-800">${data.profil.namaPertanian}</p></div>
                            <div><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-envelope w-5"></i> Email</label><p class="font-medium text-gray-800">${data.profil.email}</p></div>
                            <div><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-phone-alt w-5"></i> No. Telepon</label><p class="font-medium text-gray-800">${data.profil.telepon}</p></div>
                            <div class="md:col-span-2"><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-map-pin w-5"></i> Alamat</label><p class="font-medium text-gray-800">${data.profil.alamat}</p></div>
                            <div class="md:col-span-2"><label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-id-card w-5"></i> Bio</label><p class="font-medium text-gray-800 leading-relaxed">${data.profil.bio}</p></div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-green-500 mb-1">8</h4><p class="text-gray-500 text-sm">Total Lahan</p></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-blue-500 mb-1">156</h4><p class="text-gray-500 text-sm">Total Luas (ha)</p></div>
                    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center"><h4 class="text-3xl font-bold text-orange-500 mb-1">24</h4><p class="text-gray-500 text-sm">Siklus Selesai</p></div>
                </div>
                `;
            }
        }

        // --- MODAL LOGIC ---
        function openModal(modalId) {
            const container = document.getElementById('modal-container');
            let content = '';

            // 1. MODAL TAMBAH LAHAN
            if (modalId === 'modal-tambah-lahan') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Tambah Lahan Baru</h3>
                        <p class="text-sm text-gray-500 mt-1">Masukkan informasi lahan pertanian yang akan ditambahkan</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Lahan <span class="text-red-500">*</span></label><input type="text" placeholder="Contoh: Lahan A-01" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label><input type="text" placeholder="Desa, Kecamatan" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Luas (ha) <span class="text-red-500">*</span></label><input type="number" placeholder="25" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Aktif</option><option>Istirahat</option></select></div>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Jenis Tanah <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option disabled selected>Pilih jenis tanah</option><option>Aluvial</option><option>Latosol</option><option>Andosol</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanaman Saat Ini</label><input type="text" placeholder="Contoh: Padi, Jagung" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Catatan</label><textarea class="w-full border border-gray-300 rounded-lg p-2.5 text-sm h-20 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none" placeholder="Informasi tambahan tentang lahan"></textarea></div>
                    </div>
                    <div class="p-6 border-t border-gray-100 flex space-x-3">
                        <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</button>
                        <button onclick="closeModal(); alert('Lahan berhasil ditambahkan!')" class="flex-1 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg">Tambah Lahan</button>
                    </div>
                </div>`;
            } 
            // 2. MODAL MULAI SIKLUS
            else if (modalId === 'modal-mulai') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Mulai Siklus Tanam Baru</h3>
                        <p class="text-sm text-gray-500 mt-1">Catat informasi penanaman baru</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Lahan <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Pilih lahan</option><option>Lahan A-01</option><option>Lahan B-02</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanaman <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Pilih tanaman</option><option>Padi</option><option>Jagung</option><option>Kedelai</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Luas Tanam (ha) <span class="text-red-500">*</span></label><input type="number" value="25" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Tanam <span class="text-red-500">*</span></label><div class="relative"><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"><i class="far fa-calendar"></i></div><input type="text" value="06 Des 2025" class="w-full border border-gray-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Panen <span class="text-red-500">*</span></label><div class="relative"><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"><i class="far fa-calendar"></i></div><input type="text" value="06 Mar 2026" class="w-full border border-gray-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div></div>
                        </div>
                    </div>
                    <div class="p-6 border-t border-gray-100 flex space-x-3">
                        <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</button>
                        <button onclick="closeModal(); alert('Siklus baru dimulai!')" class="flex-1 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg">Mulai Siklus</button>
                    </div>
                </div>`;
            } 
            // 3. MODAL PANEN
            else if (modalId === 'modal-panen') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Laporan Hasil Panen</h3>
                        <p class="text-sm text-gray-500 mt-1">Catat hasil panen dari lahan Anda</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Lahan <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Pilih lahan</option></select></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanaman <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Pilih tanaman</option></select></div>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Panen <span class="text-red-500">*</span></label><div class="relative"><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"><i class="far fa-calendar"></i></div><input type="text" value="06 Des 2025" class="w-full border border-gray-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Hasil Panen (ton/ha) <span class="text-red-500">*</span></label><input type="number" value="4.5" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Kualitas <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Baik</option><option>Sedang</option><option>Buruk</option></select></div>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Catatan</label><textarea class="w-full border border-gray-300 rounded-lg p-2.5 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none" placeholder="Catatan tambahan tentang panen"></textarea></div>
                    </div>
                    <div class="p-6 border-t border-gray-100 flex space-x-3">
                        <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</button>
                        <button onclick="closeModal(); alert('Laporan panen disimpan!')" class="flex-1 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg">Simpan Laporan</button>
                    </div>
                </div>`;
            } 
            // 4. MODAL HAMA
            else if (modalId === 'modal-hama') {
                content = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Laporan Serangan Hama/Penyakit</h3>
                        <p class="text-sm text-gray-500 mt-1">Catat serangan hama atau penyakit pada tanaman</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Lahan <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Pilih lahan</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Jenis Hama/Penyakit <span class="text-red-500">*</span></label><input type="text" placeholder="Contoh: Wereng Coklat, Busuk Batang" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tingkat Keparahan <span class="text-red-500">*</span></label><select class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"><option>Ringan</option><option selected>Sedang</option><option>Berat</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Laporan <span class="text-red-500">*</span></label><div class="relative"><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"><i class="far fa-calendar"></i></div><input type="text" value="06 Des 2025" class="w-full border border-gray-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none"></div></div>
                        <div><label class="block text-xs font-bold text-gray-700 mb-1">Tindakan yang Diambil <span class="text-red-500">*</span></label><textarea class="w-full border border-gray-300 rounded-lg p-2.5 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none" placeholder="Jelaskan tindakan penanganan yang dilakukan"></textarea></div>
                    </div>
                    <div class="p-6 border-t border-gray-100 flex space-x-3">
                        <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</button>
                        <button onclick="closeModal(); alert('Laporan hama disimpan!')" class="flex-1 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg">Simpan Laporan</button>
                    </div>
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
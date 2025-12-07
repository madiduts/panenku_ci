<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Siklus & Aktivitas</h2>
    <p class="text-gray-500 mt-1">Kelola siklus tanam dan aktivitas lahan</p>
</div>

<!-- ACTION BUTTONS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Tombol hanya contoh UI, arahkan ke Modal masing-masing -->
    <button class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group">
        <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl group-hover:bg-green-600 group-hover:text-white transition"><i class="fas fa-seedling"></i></div>
        <div><h3 class="font-bold text-gray-800">Mulai Siklus Baru</h3><p class="text-xs text-gray-400 mt-1">Tanam tanaman baru</p></div>
    </button>
    <button class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group">
        <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xl group-hover:bg-orange-600 group-hover:text-white transition"><i class="fas fa-cube"></i></div>
        <div><h3 class="font-bold text-gray-800">Lapor Hasil Panen</h3><p class="text-xs text-gray-400 mt-1">Catat hasil panen</p></div>
    </button>
    <button class="bg-white border border-gray-100 p-6 rounded-xl shadow-sm hover:shadow-md transition text-left flex items-center space-x-4 group">
        <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:bg-red-600 group-hover:text-white transition"><i class="fas fa-bug"></i></div>
        <div><h3 class="font-bold text-gray-800">Lapor Serangan Hama</h3><p class="text-xs text-gray-400 mt-1">Laporkan hama/penyakit</p></div>
    </button>
</div>

<!-- TABS NAVIGATION (PHP based links) -->
<?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'aktif'; ?>
<div class="flex space-x-4 border-b border-gray-200 mb-6">
    <a href="?tab=aktif" class="pb-3 text-sm font-medium border-b-2 transition <?= $tab == 'aktif' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">Siklus Tanam Aktif</a>
    <a href="?tab=riwayat" class="pb-3 text-sm font-medium border-b-2 transition <?= $tab == 'riwayat' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">Riwayat Panen</a>
    <a href="?tab=laporan" class="pb-3 text-sm font-medium border-b-2 transition <?= $tab == 'laporan' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">Laporan Hama</a>
</div>

<!-- TAB CONTENT -->
<div>
    <?php if($tab == 'aktif'): ?>
        <div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Siklus Tanam yang Sedang Berjalan</h3></div>
        <div class="space-y-4">
            <!-- Example Item -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-green-50 rounded text-green-600 flex items-center justify-center"><i class="fas fa-seedling"></i></div>
                        <div><h4 class="font-bold text-gray-800">Padi - Lahan A-01</h4><p class="text-xs text-gray-500">25 hektar</p></div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Tumbuh</span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-gray-400 text-xs mb-1">Tanggal Tanam</p><p class="font-bold text-gray-800">15 Mar 2024</p></div>
                    <div class="text-right"><p class="text-gray-400 text-xs mb-1">Estimasi Panen</p><p class="font-bold text-gray-800">15 Agt 2024</p></div>
                </div>
            </div>
        </div>

    <?php elseif($tab == 'laporan'): ?>
        <div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Laporan Hama & Penyakit</h3></div>
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-red-50 rounded text-red-600 flex items-center justify-center"><i class="fas fa-bug"></i></div>
                    <div><h4 class="font-bold text-gray-800">Wereng Coklat</h4><p class="text-xs text-gray-500">Lahan A-01 • 10 Jun 2024</p></div>
                </div>
                <div class="flex space-x-2">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Sedang</span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">Ditangani</span>
                </div>
            </div>
            <div class="mt-4"><p class="text-gray-400 text-xs mb-1">Tindakan:</p><p class="font-bold text-gray-800 text-sm">Penyemprotan pestisida organik</p></div>
        </div>

    <?php else: ?>
        <div class="mb-4"><h3 class="font-bold text-lg text-gray-800">Riwayat Hasil Panen</h3></div>
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-orange-50 rounded text-orange-600 flex items-center justify-center"><i class="fas fa-cube"></i></div>
                    <div><h4 class="font-bold text-gray-800">Kedelai - Lahan C-03</h4><p class="text-xs text-gray-500">20 Nov 2024</p></div>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">Baik</span>
            </div>
            <div class="mt-4"><p class="text-gray-400 text-xs mb-1">Hasil Panen</p><p class="font-bold text-gray-800 text-xl">4.2 ton/ha</p></div>
        </div>
    <?php endif; ?>
</div>
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Laporan & Reporting</h2>
    <p class="text-gray-500 mt-1">Export laporan dan lihat rekapitulasi data pertanian</p>
</div>

<!-- Section Export -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
    <h3 class="font-bold text-gray-800 text-lg mb-1 flex items-center gap-2"><i class="fas fa-download"></i> Export Laporan</h3>
    <p class="text-sm text-gray-500 mb-6">Unduh laporan dalam format PDF atau Excel</p>
    
    <!-- Filter -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-2">Periode</label>
            <select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                <option>Desember 2025</option>
                <option>November 2025</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-2">Desa</label>
            <select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                <option>Semua Desa</option>
                <option>Desa Makmur</option>
                <option>Desa Sejahtera</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-2">Komoditas</label>
            <select class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                <option>Semua Komoditas</option>
                <option>Padi</option>
                <option>Jagung</option>
            </select>
        </div>
    </div>
    
    <!-- Tombol Action -->
    <div class="flex gap-4">
        <button class="flex-1 bg-black text-white py-3 rounded-lg font-bold text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
        <button class="flex-1 bg-white border border-gray-200 text-gray-700 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition flex items-center justify-center gap-2">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- Statistik Ringkas -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Total Lahan</p>
        <h3 class="text-2xl font-bold text-gray-800">560 Ha</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Total Petani</p>
        <h3 class="text-2xl font-bold text-gray-800">208</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Padi</p>
        <h3 class="text-2xl font-bold text-gray-800">1425 Ton</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Jagung</p>
        <h3 class="text-2xl font-bold text-gray-800">740 Ton</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Kedelai</p>
        <h3 class="text-2xl font-bold text-gray-800">180 Ton</h3>
    </div>
</div>

<!-- Chart Container (Di-init oleh layout_dinas.php) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="font-bold text-gray-800 mb-2">Grafik Produksi per Desa</h3>
    <div class="h-72 relative w-full mb-8">
        <canvas id="productionByVillageChart"></canvas>
    </div>
</div>

<!-- Tabel Rekapitulasi -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Rekapitulasi Per Desa</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-white text-gray-500 text-xs border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Desa</th>
                    <th class="px-6 py-4">Total Lahan</th>
                    <th class="px-6 py-4">Jumlah Petani</th>
                    <th class="px-6 py-4">Padi</th>
                    <th class="px-6 py-4">Jagung</th>
                    <th class="px-6 py-4">Kedelai</th>
                    <th class="px-6 py-4">Produktivitas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(isset($rekapDesa)): foreach($rekapDesa as $d): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><?= $d->desa ?></td>
                    <td class="px-6 py-4"><?= $d->lahan ?> Ha</td>
                    <td class="px-6 py-4"><?= $d->petani ?></td>
                    <td class="px-6 py-4"><?= $d->padi_ton ?> Ton</td>
                    <td class="px-6 py-4"><?= $d->jagung_ton ?> Ton</td>
                    <td class="px-6 py-4"><?= $d->kedelai_ton ?> Ton</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold"><?= $d->prod ?> Ton/Ha</span>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
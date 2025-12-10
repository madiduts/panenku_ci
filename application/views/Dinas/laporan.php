<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Laporan & Reporting</h2>
    <p class="text-gray-500 mt-1">Export laporan dan lihat rekapitulasi data pertanian</p>
</div>

<!-- Section Export -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
    <h3 class="font-bold text-gray-800 text-lg mb-1 flex items-center gap-2"><i class="fas fa-download"></i> Export Laporan</h3>
    <p class="text-sm text-gray-500 mb-6">Unduh laporan dalam format PDF atau Excel</p>
    
    <!-- FORM WRAPPER (PENTING: Agar filter terkirim) -->
    <form method="GET" target="_blank"> 
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Desa</label>
                <select name="desa" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="">Semua Desa</option>
                    <!-- Opsi desa bisa diloop dari DB jika ada master desa -->
                    <option value="Desa Suka Makmur">Desa Suka Makmur</option>
                    <option value="Desa Sejahtera">Desa Sejahtera</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Komoditas</label>
                <select name="komoditas" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="">Semua Komoditas</option>
                    <option value="Padi">Padi</option>
                    <option value="Jagung">Jagung</option>
                </select>
            </div>
        </div>
        
        <div class="flex gap-4">
            <!-- Tombol PDF (Form Action diarahkan ke method export_pdf) -->
            <button type="submit" formaction="<?= base_url('dinas/laporan/export_pdf') ?>" 
                class="flex-1 bg-black text-white py-3 rounded-lg font-bold text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
            
            <!-- Tombol Excel (Form Action diarahkan ke method export_excel) -->
            <button type="submit" formaction="<?= base_url('dinas/laporan/export_excel') ?>" 
                class="flex-1 bg-white border border-gray-200 text-gray-700 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition flex items-center justify-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </form>
</div>

<!-- Statistik Ringkas -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Total Luas Lahan</p>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_lahan']) ?> Ha</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Total Petani</p>
        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_petani']) ?></h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Padi</p>
        <h3 class="text-2xl font-bold text-green-600"><?= number_format($stats['prod_padi']) ?> Ton</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Jagung</p>
        <h3 class="text-2xl font-bold text-yellow-600"><?= number_format($stats['prod_jagung']) ?> Ton</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-3">Produksi Kedelai</p>
        <h3 class="text-2xl font-bold text-orange-600"><?= number_format($stats['prod_kedelai']) ?> Ton</h3>
    </div>
</div>

<!-- Tabel Rekapitulasi -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-bold text-gray-800">Rekapitulasi Per Desa</h3>
    </div>
    <div class="overflow-x-auto">
        <?php if(empty($rekapDesa)): ?>
            <div class="p-10 text-center text-gray-400">Belum ada data panen yang divalidasi.</div>
        <?php else: ?>
        <table class="w-full text-sm text-left">
            <thead class="bg-white text-gray-500 text-xs border-b border-gray-100 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-4">Desa</th>
                    <th class="px-6 py-4 text-center">Jml Lahan</th>
                    <th class="px-6 py-4 text-center">Total Luas</th>
                    <th class="px-6 py-4 text-center">Jml Petani</th>
                    <th class="px-6 py-4 text-center">Padi (Ton)</th>
                    <th class="px-6 py-4 text-center">Jagung (Ton)</th>
                    <th class="px-6 py-4 text-center">Kedelai (Ton)</th>
                    <th class="px-6 py-4 text-right">Produktivitas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach($rekapDesa as $d): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800"><?= $d->desa ?></td>
                    <td class="px-6 py-4 text-center"><?= $d->jumlah_lahan ?></td>
                    <td class="px-6 py-4 text-center font-mono"><?= $d->luas_total ?> Ha</td>
                    <td class="px-6 py-4 text-center"><?= $d->jumlah_petani ?></td>
                    <td class="px-6 py-4 text-center font-bold text-green-600"><?= $d->padi_ton ?></td>
                    <td class="px-6 py-4 text-center font-bold text-yellow-600"><?= $d->jagung_ton ?></td>
                    <td class="px-6 py-4 text-center font-bold text-orange-600"><?= $d->kedelai_ton ?></td>
                    <td class="px-6 py-4 text-right">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold border border-blue-100">
                            <?= $d->produktivitas ?> Ton/Ha
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
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
            <h3 class="text-3xl font-bold text-gray-800"><?= $stats['laporanPending'] ?></h3>
            <p class="text-xs text-gray-400 mt-1">Laporan pending</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
        <div class="flex items-center space-x-2 text-green-500">
            <i class="far fa-check-circle"></i>
            <span class="text-sm font-medium text-gray-600">Disetujui</span>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-gray-800">24</h3>
            <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
        <div class="flex items-center space-x-2 text-red-500">
            <i class="far fa-times-circle"></i>
            <span class="text-sm font-medium text-gray-600">Ditolak</span>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-gray-800">5</h3>
            <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
        </div>
    </div>
</div>

<!-- Tabs (PHP Links) -->
<?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'masuk'; ?>
<div class="flex space-x-2 mb-6">
    <a href="?tab=masuk" class="px-4 py-2 rounded-full text-sm font-medium transition <?= $tab === 'masuk' ? 'bg-gray-200 text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">
        Laporan Masuk
    </a>
    <a href="?tab=riwayat" class="px-4 py-2 rounded-full text-sm font-medium transition <?= $tab === 'riwayat' ? 'bg-gray-200 text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">
        Riwayat Validasi
    </a>
</div>

<!-- Table Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    <?php if($tab === 'masuk'): ?>
    <!-- TAB LAPORAN MASUK -->
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Laporan Menunggu Verifikasi</h3>
        <p class="text-sm text-gray-500 mt-1">Review dan validasi laporan yang masuk dari petani</p>
    </div>
    <div class="overflow-x-auto">
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
                <?php foreach($laporanMasuk as $item): ?>
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-6 py-4 text-gray-500"><?= $item->id ?></td>
                    <td class="px-6 py-4 text-gray-800 flex items-center gap-2">
                        <i class="far fa-user text-gray-400"></i> <?= $item->petani ?>
                    </td>
                    <td class="px-6 py-4 text-gray-600"><i class="fas fa-map-marker-alt text-gray-300 mr-1"></i> <?= $item->lokasi ?></td>
                    <td class="px-6 py-4"><span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold"><?= $item->jenis ?></span></td>
                    <td class="px-6 py-4 text-gray-600"><?= $item->komoditas ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= $item->luas ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= $item->hasil ?></td>
                    <td class="px-6 py-4 text-gray-600"><i class="far fa-calendar text-gray-400 mr-1"></i> <?= $item->tanggal ?></td>
                    <td class="px-6 py-4">
                        <!-- Kirim data via attributes untuk Modal JS -->
                        <button onclick="openModalReview(this)" 
                            data-id="<?= $item->id ?>"
                            data-petani="<?= $item->petani ?>"
                            data-lokasi="<?= $item->lokasi ?>"
                            data-jenis="<?= $item->jenis ?>"
                            data-komoditas="<?= $item->komoditas ?>"
                            data-luas="<?= $item->luas ?>"
                            data-hasil="<?= $item->hasil ?>"
                            class="border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-black hover:text-white transition text-xs font-bold flex items-center gap-2">
                            <i class="far fa-eye"></i> Review
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <!-- TAB RIWAYAT VALIDASI -->
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Riwayat Validasi</h3>
        <p class="text-sm text-gray-500 mt-1">Daftar laporan yang sudah divalidasi</p>
    </div>
    <div class="overflow-x-auto">
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
                <?php foreach($riwayatValidasi as $item): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-500"><?= $item->id ?></td>
                    <td class="px-6 py-4 text-gray-800"><?= $item->petani ?></td>
                    <td class="px-6 py-4"><span class="bg-gray-50 border border-gray-200 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase"><?= $item->jenis ?></span></td>
                    <td class="px-6 py-4 text-gray-600"><?= $item->komoditas ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= $item->tanggal ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold flex items-center gap-1 w-fit <?= $item->status === 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <i class="fas <?= $item->status === 'Disetujui' ? 'fa-check-circle' : 'fa-times-circle' ?>"></i> <?= $item->status ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-xs"><?= $item->validator ?></td>
                    <td class="px-6 py-4 text-gray-500 text-xs italic"><?= $item->catatan ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- SCRIPT KHUSUS HALAMAN VALIDASI (MODAL REVIEW) -->
<script>
    function openModalReview(btn) {
        // Ambil data dari tombol
        const data = btn.dataset;
        const container = document.getElementById('modal-container');
        
        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 text-lg">Detail Laporan - ${data.id}</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    </div>
                    
                    <div class="p-6 text-sm">
                        <p class="text-gray-500 text-xs mb-4">Review dan validasi laporan dari petani</p>
                        
                        <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-6">
                            <div><p class="text-xs text-gray-500 mb-1">Nama Petani</p><p class="font-bold text-gray-800">${data.petani}</p></div>
                            <div><p class="text-xs text-gray-500 mb-1">Lokasi</p><p class="font-bold text-gray-800">${data.lokasi}</p></div>
                            <div><p class="text-xs text-gray-500 mb-1">Jenis Aktivitas</p><p class="font-bold text-gray-800">${data.jenis}</p></div>
                            <div><p class="text-xs text-gray-500 mb-1">Komoditas</p><p class="font-bold text-gray-800">${data.komoditas}</p></div>
                            <div><p class="text-xs text-gray-500 mb-1">Luas Lahan</p><p class="font-bold text-gray-800">${data.luas} Ha</p></div>
                            <div><p class="text-xs text-gray-500 mb-1">Hasil Panen</p><p class="font-bold text-gray-800">${data.hasil} Ton</p></div>
                        </div>

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
</script>
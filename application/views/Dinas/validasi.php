<!-- 
    FILE: application/views/dinas/validasi.php
    VERSION: Global Notifications (Badges Always Visible)
-->

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Validasi Data</h2>
        <p class="text-gray-500 mt-1 text-sm">Pusat verifikasi data pertanian (Lahan, Panen, Hama).</p>
    </div>
    
    <!-- Statistik Cepat (Header) -->
    <div class="flex space-x-6 text-sm">
        <div class="text-right">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Lahan Baru</p>
            <p class="text-xl font-bold text-blue-600"><?= isset($countLahan) ? $countLahan : 0 ?></p>
        </div>
        <div class="w-px bg-gray-200 h-10"></div>
        <div class="text-right">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Panen</p>
            <p class="text-xl font-bold text-gray-800"><?= isset($countPanen) ? $countPanen : 0 ?></p>
        </div>
        <div class="w-px bg-gray-200 h-10"></div>
        <div class="text-right">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Hama</p>
            <p class="text-xl font-bold text-red-600"><?= isset($countHama) ? $countHama : 0 ?></p>
        </div>
    </div>
</div>

<?php 
    // Ambil tab aktif (Default: lahan)
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'lahan'; 
?>

<!-- NAVIGASI TAB (PERBAIKAN FOKUS DI SINI) -->
<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8 overflow-x-auto">
        
        <!-- 1. TAB LAHAN -->
        <a href="?tab=lahan" class="<?= $tab === 'lahan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center transition group">
            <i class="fas fa-map-marked-alt mr-2"></i> 
            Validasi Lahan
            
            <!-- BADGE: Selalu Muncul jika > 0 -->
            <?php if(isset($countLahan) && $countLahan > 0): ?>
                <span class="ml-2 bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full text-[10px] font-bold animate-pulse shadow-sm border border-blue-200">
                    <?= $countLahan ?> Baru
                </span>
            <?php endif; ?>
        </a>

        <!-- 2. TAB PANEN -->
        <a href="?tab=panen" class="<?= $tab === 'panen' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700' ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center transition group">
            <i class="fas fa-wheat mr-2"></i> 
            Laporan Panen
            
            <!-- BADGE: Selalu Muncul jika > 0 -->
            <?php if(isset($countPanen) && $countPanen > 0): ?>
                <span class="ml-2 bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-[10px] font-bold border border-gray-200">
                    <?= $countPanen ?>
                </span>
            <?php endif; ?>
        </a>
        
        <!-- 3. TAB HAMA -->
        <a href="?tab=hama" class="<?= $tab === 'hama' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-red-600' ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center transition group">
            <i class="fas fa-bug mr-2"></i> 
            Laporan Hama
            
            <!-- BADGE: Selalu Muncul jika > 0 -->
            <?php if(isset($countHama) && $countHama > 0): ?>
                <span class="ml-2 bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-[10px] font-bold animate-pulse shadow-sm border border-red-200">
                    <?= $countHama ?> Baru
                </span>
            <?php endif; ?>
        </a>
        
        <!-- 4. TAB RIWAYAT -->
        <a href="?tab=riwayat" class="<?= $tab === 'riwayat' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition">
            <i class="fas fa-history mr-2"></i> Riwayat Validasi
        </a>
    </nav>
</div>

<!-- KONTEN AREA -->
<div class="min-h-[400px] animate-fade-in-up">

    <!-- 1. KONTEN LAHAN -->
    <?php if($tab === 'lahan'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 bg-blue-50/30 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Registrasi Lahan Baru</h3>
                    <p class="text-[10px] text-gray-500">Verifikasi data sebelum menyetujui.</p>
                </div>
                <!-- Indikator Total di Header Tabel -->
                <?php if(!empty($laporanLahan)): ?>
                    <span class="text-xs font-bold text-blue-600 bg-white px-2 py-1 rounded border border-blue-100">Total: <?= count($laporanLahan) ?></span>
                <?php endif; ?>
            </div>
            
            <?php if(empty($laporanLahan)): ?>
                <div class="p-16 text-center text-gray-400">
                    <i class="fas fa-map-marked text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada pengajuan lahan baru.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b">
                            <tr>
                                <th class="px-6 py-4">Pemilik</th>
                                <th class="px-6 py-4">Lokasi Desa</th>
                                <th class="px-6 py-4">Luas (Ha)</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($laporanLahan as $lahan): ?>
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900"><?= htmlspecialchars($lahan->nama_petani) ?></span>
                                    <div class="text-xs text-gray-500"><i class="fas fa-phone-alt mr-1"></i> <?= htmlspecialchars($lahan->kontak_petani) ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($lahan->lokasi_desa) ?></td>
                                <td class="px-6 py-4 font-mono font-bold"><?= htmlspecialchars($lahan->luas_lahan) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold border border-gray-200">
                                        <?= htmlspecialchars($lahan->kategori_lahan) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick='openModalLahan(<?= json_encode($lahan) ?>)' 
                                        class="bg-white border border-blue-200 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                        Verifikasi
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    <!-- 2. KONTEN PANEN -->
    <?php elseif($tab === 'panen'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <?php if(empty($laporanPanen)): ?>
                <div class="p-10 text-center text-gray-400">Belum ada laporan panen.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b">
                            <tr>
                                <th class="px-6 py-4">Petani</th>
                                <th class="px-6 py-4">Komoditas</th>
                                <th class="px-6 py-4">Hasil (Ton)</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($laporanPanen as $item): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <?= htmlspecialchars($item->nama_petani) ?>
                                    <div class="text-xs text-gray-500 font-normal"><?= htmlspecialchars($item->lokasi_desa) ?></div>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($item->nama_komoditas) ?></td>
                                <td class="px-6 py-4 font-bold text-green-600"><?= htmlspecialchars($item->jumlah_ton) ?></td>
                                <td class="px-6 py-4 text-gray-500 text-xs"><?= date('d M Y', strtotime($item->tanggal_realisasi)) ?></td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick='openModalPanen(<?= json_encode($item) ?>)' class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-black hover:text-white transition">Review</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    <!-- 3. KONTEN HAMA -->
    <?php elseif($tab === 'hama'): ?>
        <?php if(empty($laporanHama)): ?>
            <div class="bg-white rounded-xl border-l-4 border-green-500 p-10 text-center shadow-sm">
                <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                <h3 class="font-bold text-gray-900">Aman Terkendali</h3>
                <p class="text-gray-500 text-sm">Tidak ada laporan hama.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($laporanHama as $hama): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 w-full bg-gray-100 relative cursor-pointer group" onclick='openModalHama(<?= json_encode($hama) ?>)'>
                        <?php if(!empty($hama->foto_bukti)): ?>
                            <img src="<?= base_url('uploads/hama/'.$hama->foto_bukti) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                <span class="text-white text-xs font-bold border border-white px-3 py-1 rounded-full">Lihat Detail</span>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-400 flex-col"><i class="fas fa-image-slash text-2xl"></i></div>
                        <?php endif; ?>
                        <div class="absolute top-2 right-2"><span class="bg-white/90 text-red-600 text-[10px] font-bold px-2 py-1 rounded border border-red-100 uppercase"><?= $hama->tingkat_keparahan ?></span></div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900"><?= $hama->nama_hama ?></h4>
                        <p class="text-xs text-gray-500 mb-2">Oleh: <?= $hama->nama_petani ?></p>
                        <button onclick='openModalHama(<?= json_encode($hama) ?>)' class="w-full border border-red-500 text-red-600 hover:bg-red-600 hover:text-white py-2 rounded-lg text-xs font-bold transition">Validasi</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- CONTAINER MODAL -->
<div id="modal-container"></div>

<!-- JAVASCRIPT LOGIC -->
<script>
    const BASE_URL = "<?= base_url() ?>";

    // --- 1. MODAL LAHAN (BARU) ---
    function openModalLahan(data) {
        const container = document.getElementById('modal-container');
        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
                <form action="${BASE_URL}dinas/validasi/submit_validasi_lahan" method="POST" 
                      class="bg-white w-full max-w-md rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border-t-4 border-blue-500">
                    <input type="hidden" name="lahan_id" value="${data.id}">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                        <h3 class="font-bold text-gray-900 text-lg">Verifikasi Lahan</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-black"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-blue-500 font-bold uppercase">Luas</span>
                                <span class="text-lg font-bold text-gray-800">${data.luas_lahan} Ha</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-blue-500 font-bold uppercase">Kategori</span>
                                <span class="text-sm font-bold text-gray-800">${data.kategori_lahan}</span>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div><p class="text-xs text-gray-400">Pemilik</p><p class="font-bold text-gray-800">${data.nama_petani}</p></div>
                            <div><p class="text-xs text-gray-400">Lokasi</p><p class="font-bold text-gray-800">${data.lokasi_desa}</p></div>
                            <div><p class="text-xs text-gray-400">Kontak</p><p class="font-bold text-gray-800">${data.kontak_petani}</p></div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-bold text-gray-700 mb-2">Catatan Verifikasi</label>
                            <textarea name="catatan" required class="w-full border border-gray-300 rounded-xl p-3 text-sm h-20 focus:ring-2 focus:ring-blue-500 outline-none resize-none bg-gray-50" placeholder="Lahan valid / Data tidak sesuai..."></textarea>
                        </div>
                    </div>
                    <div class="p-5 border-t border-gray-100 bg-gray-50 shrink-0 flex gap-3">
                        <button type="submit" name="status" value="Valid" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold hover:bg-blue-700 shadow-lg">Valid</button>
                        <button type="submit" name="status" value="Reject" class="flex-1 bg-white border border-red-200 text-red-600 py-2.5 rounded-xl font-bold hover:bg-red-50">Tolak</button>
                    </div>
                </form>
            </div>`;
    }

    // --- 2. MODAL PANEN ---
    function openModalPanen(data) {
        const container = document.getElementById('modal-container');
        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
                <form action="${BASE_URL}dinas/validasi/submit_validasi_panen" method="POST" 
                      class="bg-white w-full max-w-md rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
                    <input type="hidden" name="panen_id" value="${data.id}">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                        <h3 class="font-bold text-gray-900 text-lg">Validasi Panen</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-black"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                        <div class="grid grid-cols-2 gap-4 bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                            <div><p class="text-xs text-blue-500 font-bold uppercase">Petani</p><p class="font-bold text-gray-800">${data.nama_petani}</p></div>
                            <div><p class="text-xs text-blue-500 font-bold uppercase">Hasil</p><p class="font-bold text-gray-900">${data.jumlah_ton} Ton</p></div>
                        </div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Catatan Validasi</label>
                        <textarea name="catatan" required class="w-full border border-gray-300 rounded-xl p-3 text-sm h-24 focus:ring-2 focus:ring-black outline-none resize-none bg-gray-50" placeholder="Catatan..."></textarea>
                    </div>
                    <div class="p-5 border-t border-gray-100 bg-gray-50 shrink-0 flex gap-3">
                        <button type="submit" name="status" value="Valid" class="flex-1 bg-black text-white py-2.5 rounded-xl font-bold hover:bg-gray-800 shadow-lg">Setujui</button>
                        <button type="submit" name="status" value="Reject" class="flex-1 bg-white border border-red-200 text-red-600 py-2.5 rounded-xl font-bold hover:bg-red-50">Tolak</button>
                    </div>
                </form>
            </div>`;
    }

    // --- 3. MODAL HAMA ---
    function openModalHama(data) {
        const container = document.getElementById('modal-container');
        const imgHtml = data.foto_bukti 
            ? `<div class="relative h-48 w-full rounded-lg overflow-hidden mb-4 border border-gray-200 bg-gray-100">
                 <img src="${BASE_URL}uploads/hama/${data.foto_bukti}" class="w-full h-full object-contain">
               </div>` 
            : `<div class="h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 mb-4 text-xs border border-dashed border-gray-300">Tidak ada foto bukti</div>`;

        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
                <form action="${BASE_URL}dinas/validasi/submit_validasi_hama" method="POST" 
                      class="bg-white w-full max-w-lg rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border-t-4 border-red-500">
                    <input type="hidden" name="lapor_hama_id" value="${data.id}">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Validasi Hama</h3>
                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded font-bold uppercase tracking-wider">${data.tingkat_keparahan}</span>
                        </div>
                        <button type="button" onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 hover:bg-red-100 hover:text-red-600 transition"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                        ${imgHtml}
                        <div class="mb-4">
                            <h4 class="font-bold text-gray-900 text-lg mb-1">${data.nama_hama}</h4>
                            <p class="text-xs text-gray-500">
                                Dilaporkan oleh <span class="font-bold text-gray-800">${data.nama_petani}</span> 
                                di ${data.lokasi_desa}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1">${data.tanggal_lapor}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Rekomendasi Penanganan (Wajib)</label>
                            <textarea name="rekomendasi" required 
                                class="w-full border border-gray-300 rounded-xl p-3 text-sm h-28 focus:ring-2 focus:ring-red-500 outline-none resize-none shadow-sm transition bg-gray-50 focus:bg-white" 
                                placeholder="Contoh: Semprot pestisida X dosis 5ml/liter..."></textarea>
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-100 bg-gray-50 shrink-0 flex gap-3">
                        <button type="submit" name="status" value="Diterima" class="flex-1 bg-red-600 text-white py-2.5 rounded-xl font-bold hover:bg-red-700 shadow-lg hover:shadow-red-500/30 transition text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Rekomendasi
                        </button>
                    </div>
                </form>
            </div>`;
    }

    function closeModal() {
        document.getElementById('modal-container').innerHTML = '';
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fadeIn 0.2s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
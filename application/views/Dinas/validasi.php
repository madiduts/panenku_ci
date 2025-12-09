<!-- 
    [ENGLISH CORNER] 
    - Screen Real Estate: The amount of space available on a display for an application to provide output.
    - Empty State: A UI state when there is no data to display.
    - Card View: A UI design pattern that groups related information in a container resembling a physical card.
-->

<!-- Header Ringkas -->
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Validasi Laporan</h2>
        <p class="text-gray-500 mt-1 text-sm">Verifikasi data panen dan penanganan hama secara real-time.</p>
    </div>
    
    <!-- Statistik Cepat (Optional, biar ga kosong melompong header-nya) -->
    <div class="flex space-x-4">
        <div class="text-right">
            <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">Pending Panen</span>
            <span class="text-xl font-bold text-gray-800"><?= number_format($stats['laporanPending'] ?? 0) ?></span>
        </div>
        <div class="w-px bg-gray-200 h-10"></div>
        <div class="text-right">
            <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">Pending Hama</span>
            <span class="text-xl font-bold text-red-600"><?= number_format(count($laporanHama ?? [])) ?></span>
        </div>
    </div>
</div>

<!-- Tabs Navigation (Desain Lebih Modern & Menyatu) -->
<?php 
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'panen'; 
    $countHama = isset($laporanHama) ? count($laporanHama) : 0;
?>
<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <!-- Tab Panen -->
        <a href="?tab=panen" class="<?= $tab === 'panen' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center transition-colors duration-200">
            <i class="fas fa-wheat mr-2 <?= $tab === 'panen' ? 'text-black' : 'text-gray-400' ?>"></i>
            Laporan Panen
        </a>

        <!-- Tab Hama (Dengan Badge Dinamis) -->
        <a href="?tab=hama" class="<?= $tab === 'hama' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-red-600 hover:border-red-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center transition-colors duration-200">
            <i class="fas fa-bug mr-2 <?= $tab === 'hama' ? 'text-red-500' : 'text-gray-400' ?>"></i>
            Laporan Hama
            <?php if($countHama > 0): ?>
                <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-extrabold animate-pulse">
                    <?= $countHama ?> Baru
                </span>
            <?php endif; ?>
        </a>

        <!-- Tab Riwayat -->
        <a href="?tab=riwayat" class="<?= $tab === 'riwayat' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors duration-200">
            <i class="fas fa-history mr-2 <?= $tab === 'riwayat' ? 'text-black' : 'text-gray-400' ?>"></i>
            Riwayat
        </a>
    </nav>
</div>

<!-- DATA CONTAINER -->
<div class="min-h-[400px] animate-fade-in-up">

    <!-- ========================================== -->
    <!-- VIEW 1: TABEL PANEN (Tetap Tabel karena Data Padat) -->
    <!-- ========================================== -->
    <?php if($tab === 'panen'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <?php if(empty($laporanPanen)): ?>
                <!-- Empty State Panen -->
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="bg-gray-50 rounded-full p-6 mb-4">
                        <i class="fas fa-check text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-gray-900 font-bold text-lg">Semua Bersih!</h3>
                    <p class="text-gray-500 max-w-sm mt-1">Tidak ada laporan panen yang perlu divalidasi saat ini.</p>
                </div>
            <?php else: ?>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Info Petani</th>
                            <th class="px-6 py-4">Komoditas</th>
                            <th class="px-6 py-4 text-center">Luas (Ha)</th>
                            <th class="px-6 py-4 text-center">Hasil (Ton)</th>
                            <th class="px-6 py-4">Tanggal Lapor</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($laporanPanen as $item): ?>
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900"><?= htmlspecialchars($item->petani) ?></p>
                                <p class="text-xs text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($item->lokasi) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 py-1 px-2 rounded text-xs font-bold"><?= htmlspecialchars($item->komoditas) ?></span>
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-gray-600"><?= htmlspecialchars($item->luas) ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-green-600 font-bold text-base"><?= htmlspecialchars($item->hasil) ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                <?= date('d M Y', strtotime($item->tanggal)) ?>
                                <br><span class="text-gray-400"><?= date('H:i', strtotime($item->tanggal)) ?> WIB</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openModalPanen(<?= json_encode($item) ?>)' class="group-hover:bg-black group-hover:text-white bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                                    Review
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    <!-- ========================================== -->
    <!-- VIEW 2: GRID KARTU HAMA (Perubahan Besar Disini) -->
    <!-- ========================================== -->
    <?php elseif($tab === 'hama'): ?>
        
        <?php if(empty($laporanHama)): ?>
            <!-- Empty State Hama (Fixed UI) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500 p-10 flex flex-col items-center justify-center text-center animate-fade-in">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shield-virus text-4xl text-green-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Aman Terkendali</h3>
                <p class="text-gray-500 mt-2 max-w-md">Tidak ada laporan serangan hama aktif dari petani. Tetap pantau dashboard untuk pembaruan.</p>
            </div>
        <?php else: ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($laporanHama as $hama): ?>
                <!-- Hama Card -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 overflow-hidden flex flex-col transition-all duration-300 transform hover:-translate-y-1">
                    
                    <!-- Header Foto (Thumbnail Besar) -->
                    <div class="h-48 w-full bg-gray-100 relative group cursor-pointer" onclick='openModalHama(<?= json_encode($hama) ?>)'>
                        <?php if(!empty($hama->foto_bukti)): ?>
                            <img src="<?= base_url('uploads/hama/'.$hama->foto_bukti) ?>" alt="Bukti Hama" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <span class="text-white font-bold text-sm bg-black/50 px-3 py-1 rounded-full"><i class="fas fa-eye mr-1"></i> Lihat Detail</span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-image-slash text-3xl mb-2"></i>
                                <span class="text-xs">Tidak ada foto</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge Severity -->
                        <div class="absolute top-3 right-3">
                            <?php 
                                $badgeColor = match($hama->tingkat_keparahan) {
                                    'Ringan' => 'bg-green-100 text-green-800 border-green-200',
                                    'Sedang' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'Berat/Puso' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                            <span class="<?= $badgeColor ?> border px-2 py-1 rounded-lg text-xs font-bold shadow-sm uppercase">
                                <?= $hama->tingkat_keparahan ?>
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg leading-tight"><?= htmlspecialchars($hama->nama_hama) ?></h4>
                                <p class="text-xs text-gray-500 mt-1"><i class="far fa-user mr-1"></i> <?= htmlspecialchars($hama->nama_petani) ?></p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center text-xs text-gray-600 bg-gray-50 p-2 rounded">
                                <i class="fas fa-map-marker-alt w-5 text-center text-red-400 mr-2"></i>
                                <?= htmlspecialchars($hama->lokasi_desa) ?>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 bg-gray-50 p-2 rounded">
                                <i class="far fa-calendar w-5 text-center text-blue-400 mr-2"></i>
                                <?= date('d M Y', strtotime($hama->tanggal_lapor)) ?>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button onclick='openModalHama(<?= json_encode($hama) ?>)' class="mt-auto w-full bg-white border-2 border-red-50 text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 py-2.5 rounded-lg font-bold text-sm transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-clipboard-check"></i> Validasi Laporan
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ========================================== -->
    <!-- VIEW 3: RIWAYAT (Simple Table) -->
    <!-- ========================================== -->
    <?php elseif($tab === 'riwayat'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center text-gray-400">
            <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
            <p>Fitur riwayat sedang dalam pengembangan.</p>
        </div>
    <?php endif; ?>

</div>

<!-- MODAL CONTAINER (DINAMIS) -->
<div id="modal-container"></div>

<!-- JAVASCRIPT LOGIC -->
<script>
    const BASE_URL = "<?= base_url() ?>";

    /**
     * Modal untuk Validasi Panen
     */
    function openModalPanen(data) {
        const container = document.getElementById('modal-container');
        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-100 transition-all">
                    <form action="${BASE_URL}dinas/dashboard_d/submit_validasi_panen" method="POST">
                        <input type="hidden" name="panen_id" value="${data.id}">
                        
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-bold text-gray-900 text-lg">Validasi Hasil Panen</h3>
                            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Summary Grid -->
                            <div class="grid grid-cols-2 gap-4 bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <div>
                                    <p class="text-xs text-blue-500 uppercase font-bold mb-1">Petani</p>
                                    <p class="font-bold text-gray-800">${data.petani}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-500 uppercase font-bold mb-1">Hasil Panen</p>
                                    <p class="font-bold text-gray-900 text-lg">${data.hasil} Ton</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs text-blue-500 uppercase font-bold mb-1">Lokasi Lahan</p>
                                    <p class="font-bold text-gray-700 text-sm">${data.lokasi}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Validator <span class="text-red-500">*</span></label>
                                <textarea name="catatan" required class="w-full border border-gray-300 rounded-xl p-3 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none bg-gray-50 focus:bg-white transition" placeholder="Berikan catatan validasi..."></textarea>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                            <button type="submit" name="status" value="Valid" class="flex-1 bg-black text-white py-3 rounded-xl font-bold hover:bg-gray-800 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                <i class="fas fa-check-circle mr-2"></i> Setujui
                            </button>
                            <button type="submit" name="status" value="Reject" class="flex-1 bg-white border border-red-200 text-red-600 py-3 rounded-xl font-bold hover:bg-red-50 transition">
                                <i class="fas fa-times-circle mr-2"></i> Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>`;
    }

    /**
     * Modal untuk Validasi Hama (Lebih Fokus ke Gambar & Solusi)
     */
    function openModalHama(data) {
        const container = document.getElementById('modal-container');
        
        // Handling Image Display
        const imgDisplay = data.foto_bukti 
            ? `<div class="relative h-56 w-full rounded-xl overflow-hidden mb-5 border border-gray-200 group">
                 <img src="${BASE_URL}uploads/hama/${data.foto_bukti}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                 <a href="${BASE_URL}uploads/hama/${data.foto_bukti}" target="_blank" class="absolute bottom-3 right-3 bg-black/70 text-white text-xs px-3 py-1.5 rounded-full hover:bg-black transition"><i class="fas fa-expand mr-1"></i> Zoom</a>
               </div>` 
            : `<div class="h-24 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 mb-5 border border-dashed border-gray-300">
                 <span class="text-xs font-medium"><i class="fas fa-camera-slash mr-2"></i>Tidak ada foto bukti</span>
               </div>`;

        container.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-red-900/40 backdrop-blur-sm p-4 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border-t-4 border-red-500 flex flex-col max-h-[90vh]">
                    
                    <form action="${BASE_URL}dinas/dashboard_d/submit_validasi_hama" method="POST" class="flex flex-col h-full">
                        <input type="hidden" name="lapor_hama_id" value="${data.id}"> <!-- Pastikan ID sesuai DB -->
                        
                        <div class="p-5 border-b flex justify-between items-center bg-white shrink-0">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Laporan Serangan Hama</h3>
                                <p class="text-xs text-red-500 font-bold uppercase tracking-wide">${data.tingkat_keparahan}</p>
                            </div>
                            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-red-100 hover:text-red-600 transition"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <div class="p-6 overflow-y-auto custom-scrollbar grow">
                            ${imgDisplay}
                            
                            <div class="flex items-start gap-4 mb-6">
                                <div class="bg-red-50 p-3 rounded-lg text-center min-w-[80px]">
                                    <i class="fas fa-bug text-2xl text-red-500 mb-1"></i>
                                    <p class="text-[10px] text-red-600 font-bold uppercase">Jenis Hama</p>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg">${data.nama_hama}</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Dilaporkan oleh <b>${data.nama_petani}</b> di ${data.lokasi_desa}.</p>
                                    <p class="text-xs text-gray-400 mt-1"><i class="far fa-clock mr-1"></i> ${data.tanggal_lapor}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi Penanganan (Penyuluh)</label>
                                <textarea name="rekomendasi" required class="w-full border border-gray-300 rounded-xl p-4 text-sm h-32 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none shadow-sm transition" placeholder="Tuliskan rekomendasi pestisida atau tindakan teknis yang harus dilakukan petani..."></textarea>
                                <p class="text-xs text-gray-400 mt-2">*Rekomendasi ini akan masuk notifikasi aplikasi petani.</p>
                            </div>
                        </div>

                        <div class="p-5 border-t bg-gray-50 shrink-0">
                            <button type="submit" name="status" value="Diterima" class="w-full bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 shadow-lg hover:shadow-red-500/30 transition flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim Rekomendasi & Validasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>`;
    }

    function closeModal() {
        document.getElementById('modal-container').innerHTML = '';
    }
</script>

<style>
    /* Custom Scrollbar for Modal */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    
    /* Animation Utilities */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>
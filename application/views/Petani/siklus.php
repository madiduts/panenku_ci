<!-- HEADER & TOMBOL START -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Siklus Tanam Aktif</h2>
        <p class="text-gray-500 mt-1">Pantau perkembangan tanaman di lahan Anda</p>
    </div>
    <!-- Tombol Tambah Siklus (SUDAH DIAKTIFKAN) -->
    <button onclick="toggleModal('modal-tambah-siklus')" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 shadow-lg flex items-center cursor-pointer transition transform hover:-translate-y-0.5">
        <i class="fas fa-seedling mr-2"></i> Mulai Tanam Baru
    </button>
</div>

<!-- FLASHDATA NOTIFICATIONS -->
<?php if($this->session->flashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 animate-fade-in" role="alert">
        <strong class="font-bold"><i class="fas fa-check-circle mr-1"></i> Berhasil!</strong>
        <span class="block sm:inline"><?= $this->session->flashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 animate-fade-in" role="alert">
        <strong class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Gagal!</strong>
        <span class="block sm:inline"><?= $this->session->flashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- LIST SIKLUS -->
<div class="space-y-6 pb-10">
    <?php if(isset($siklus_list) && !empty($siklus_list)): ?>
        <?php foreach($siklus_list as $s): 
            // Hitung umur tanaman (Hari Setelah Tanam - HST)
            $tgl_tanam = new DateTime($s['tanggal_tanam']);
            $tgl_skrg  = new DateTime();
            $umur      = $tgl_tanam->diff($tgl_skrg)->days;
            
            // Hitung sisa hari panen
            $tgl_panen = new DateTime($s['estimasi_panen']);
            
            // Perbaikan Logika Sisa Hari (Biar tidak minus kalau lewat)
            $diff = $tgl_skrg->diff($tgl_panen);
            $sisa_hari = $diff->days;
            $is_late   = ($tgl_skrg > $tgl_panen);
            
            // Progress Bar sederhana
            $total_durasi = $tgl_tanam->diff($tgl_panen)->days;
            $progress = ($total_durasi > 0) ? round(($umur / $total_durasi) * 100) : 0;
            if($progress > 100) $progress = 100;
        ?>
        
        <!-- CARD SIKLUS -->
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 rounded-xl bg-green-50 flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800"><?= $s['nama_komoditas'] ?></h3>
                        <p class="text-sm text-gray-500 flex items-center mt-1">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            <?= $s['lokasi_desa'] ?> <!-- Menggunakan lokasi_desa dari join M_siklus -->
                        </p>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 mb-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                        Fase Vegetatif
                    </div>
                    <p class="text-xs text-gray-400">Estimasi Panen</p>
                    <p class="text-sm font-bold text-gray-800">
                        <?= date('d M Y', strtotime($s['estimasi_panen'])) ?>
                    </p>
                </div>
            </div>

            <!-- PROGRESS BAR SECTION -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <span class="text-3xl font-bold text-gray-800"><?= $umur ?></span>
                        <span class="text-sm text-gray-500 font-medium">HST (Hari Setelah Tanam)</span>
                    </div>
                    <span class="text-sm font-bold text-green-600"><?= $progress ?>%</span>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2.5 rounded-full transition-all duration-1000" style="width: <?= $progress ?>%"></div>
                </div>
                
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    <span>Tanam: <?= date('d M', strtotime($s['tanggal_tanam'])) ?></span>
                    <span>
                        <?php if($is_late): ?>
                            <span class="text-red-500 font-bold">Lewat <?= $sisa_hari ?> hari (Segera Panen!)</span>
                        <?php else: ?>
                            Panen dalam <?= $sisa_hari ?> hari
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-50 pt-4">
                <button class="text-gray-500 hover:text-green-600 text-sm font-medium px-3 py-2 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-history mr-1"></i> Riwayat Pupuk
                </button>
                <button class="bg-white border border-gray-200 text-gray-700 hover:border-green-500 hover:text-green-600 text-sm font-bold px-4 py-2 rounded-lg transition shadow-sm">
                    <i class="fas fa-bug mr-2"></i> Lapor Hama
                </button>
            </div>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- EMPTY STATE (Penting!) -->
        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-seedling text-3xl"></i>
            </div>
            <h3 class="text-gray-900 font-bold text-lg">Belum Ada Tanaman</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Anda belum memulai siklus tanam di lahan manapun. Silakan mulai tanam untuk memantau perkembangannya.</p>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL TAMBAH SIKLUS -->
<div id="modal-tambah-siklus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-100">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Mulai Tanam Baru</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pastikan lahan dalam kondisi siap tanam</p>
            </div>
            <button onclick="toggleModal('modal-tambah-siklus')" class="text-gray-400 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- FORM START -->
        <!-- Action mengarah ke Controller Siklus -> method tambah -->
        <form action="<?= base_url('petani/siklus/tambah') ?>" method="POST">
            <div class="p-6 space-y-5">
                
                <!-- Input 1: Pilih Lahan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Pilih Lahan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <select name="lahan_id" required class="w-full pl-10 pr-8 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih lahan yang tersedia...</option>
                            <?php if(isset($lahan_opsi) && !empty($lahan_opsi)): ?>
                                <?php foreach($lahan_opsi as $l): ?>
                                    <option value="<?= $l['lahan_id'] ?>">
                                        <?= $l['lokasi_desa'] ?> (<?= $l['luas_lahan'] ?> Ha) - <?= $l['kategori_lahan'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada lahan tersedia (atau belum dimuat)</option>
                            <?php endif; ?>
                        </select>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">* Hanya menampilkan lahan Anda</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <!-- Input 2: Komoditas -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Komoditas <span class="text-red-500">*</span>
                        </label>
                        <select name="komoditas_id" required class="w-full py-2.5 px-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none bg-white">
                            <option value="" disabled selected>Pilih Tanaman...</option>
                            <?php if(isset($komoditas_opsi) && !empty($komoditas_opsi)): ?>
                                <?php foreach($komoditas_opsi as $k): ?>
                                    <option value="<?= $k['komoditas_id'] ?>"><?= $k['nama_komoditas'] ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="1">Padi (Default)</option>
                                <option value="2">Jagung (Default)</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Input 3: Tanggal Tanam -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Tanggal Tanam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_tanam" required 
                               value="<?= date('Y-m-d') ?>"
                               class="w-full py-2.5 px-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 flex space-x-3 bg-gray-50/50">
                <button type="button" onclick="toggleModal('modal-tambah-siklus')" class="flex-1 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 shadow-md hover:shadow-lg transition flex justify-center items-center group">
                    <span>Mulai Tanam</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Toggle Modal (Bisa ditaruh di footer global sebenarnya)
    function toggleModal(modalID){
        const modal = document.getElementById(modalID);
        if(modal) {
            modal.classList.toggle("hidden");
            modal.classList.toggle("flex");
        }
    }
</script>
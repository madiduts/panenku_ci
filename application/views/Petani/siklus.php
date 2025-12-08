<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Siklus Tanam Aktif</h2>
        <p class="text-gray-500 mt-1">Pantau perkembangan tanaman di lahan Anda</p>
    </div>
    <!-- Tombol Tambah Siklus -->
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

<!-- LIST SIKLUS (LOOPING START) -->
<div class="space-y-6 pb-10">
    <?php if(isset($siklus_list) && !empty($siklus_list)): ?>
        <?php foreach($siklus_list as $s): 
            // Logika Hitung Umur & Progress
            $tgl_tanam = new DateTime($s['tanggal_tanam']);
            $tgl_skrg  = new DateTime();
            $umur      = $tgl_tanam->diff($tgl_skrg)->days;
            
            $tgl_panen = new DateTime($s['estimasi_panen']);
            $diff      = $tgl_skrg->diff($tgl_panen);
            $sisa_hari = $diff->days; 
            
            // Cek apakah lewat waktu panen
            $is_late   = ($diff->invert == 1); 
            
            $total_durasi = $tgl_tanam->diff($tgl_panen)->days;
            $progress = ($total_durasi > 0) ? round(($umur / $total_durasi) * 100) : 0;
            if($progress > 100) $progress = 100;
        ?>
        
        <!-- CARD ITEM -->
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
                            <?= $s['lokasi_desa'] ?>
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

            <!-- PROGRESS BAR -->
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

            <!-- ACTION BUTTONS (TRIGGER MODAL) -->
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-50 pt-4">
                <!-- Tombol Pupuk -->
                <button type="button" onclick="openModalPupuk(<?= $s['siklus_id'] ?>)" class="text-gray-500 hover:text-green-600 text-sm font-medium px-3 py-2 rounded-lg hover:bg-green-50 transition cursor-pointer border border-transparent hover:border-green-100">
                    <i class="fas fa-history mr-1"></i> Riwayat Pupuk
                </button>
                
                <!-- Tombol Hama -->
                <button type="button" onclick="openModalHama(<?= $s['siklus_id'] ?>)" class="bg-white border border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600 text-sm font-bold px-4 py-2 rounded-lg transition shadow-sm cursor-pointer">
                    <i class="fas fa-bug mr-2"></i> Lapor Hama
                </button>
            </div>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- EMPTY STATE -->
        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-seedling text-3xl"></i>
            </div>
            <h3 class="text-gray-900 font-bold text-lg">Belum Ada Tanaman</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Anda belum memulai siklus tanam di lahan manapun.</p>
        </div>
    <?php endif; ?>
</div>
<!-- LOOPING END -->


<!-- ============================================= -->
<!-- MODAL SECTION (HARUS DI LUAR LOOPING) -->
<!-- ============================================= -->

<!-- 1. MODAL TAMBAH SIKLUS -->
<div id="modal-tambah-siklus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Mulai Tanam Baru</h3>
            <button onclick="toggleModal('modal-tambah-siklus')" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('petani/siklus/tambah') ?>" method="POST">
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Lahan <span class="text-red-500">*</span></label>
                    <select name="lahan_id" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm bg-white">
                        <option value="" disabled selected>Pilih lahan...</option>
                        <?php if(isset($lahan_opsi) && !empty($lahan_opsi)): ?>
                            <?php foreach($lahan_opsi as $l): ?>
                                <option value="<?= $l['lahan_id'] ?>"><?= $l['lokasi_desa'] ?> (<?= $l['luas_lahan'] ?> Ha)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Komoditas <span class="text-red-500">*</span></label>
                        <select name="komoditas_id" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm bg-white">
                            <?php if(isset($komoditas_opsi) && !empty($komoditas_opsi)): ?>
                                <?php foreach($komoditas_opsi as $k): ?>
                                    <option value="<?= $k['komoditas_id'] ?>"><?= $k['nama_komoditas'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Tanam <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tanam" value="<?= date('Y-m-d') ?>" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex space-x-3">
                <button type="button" onclick="toggleModal('modal-tambah-siklus')" class="flex-1 py-2 border rounded-xl text-sm font-bold text-gray-600">Batal</button>
                <button type="submit" class="flex-1 py-2 bg-green-600 text-white rounded-xl text-sm font-bold">Mulai Tanam</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. MODAL RIWAYAT PUPUK -->
<div id="modal-pupuk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 overflow-hidden">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-flask mr-2 text-green-500"></i>Catat Pemupukan</h3>
        <form action="<?= base_url('petani/siklus/simpan_pupuk') ?>" method="POST">
            <input type="hidden" name="siklus_id" id="input_siklus_id_pupuk">
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-600">Jenis Pupuk</label>
                    <input type="text" name="jenis_pupuk" required placeholder="Contoh: Urea" class="w-full border p-2.5 rounded-xl text-sm">
                </div>
                <div class="flex space-x-3">
                    <div class="flex-1">
                        <label class="text-xs font-bold text-gray-600">Jumlah</label>
                        <input type="number" step="0.1" name="jumlah_sebar" required class="w-full border p-2.5 rounded-xl text-sm">
                    </div>
                    <div class="w-1/3">
                         <label class="text-xs font-bold text-gray-600">Satuan</label>
                         <select name="satuan" class="w-full border p-2.5 rounded-xl text-sm bg-white">
                             <option value="kg">Kg</option>
                             <option value="liter">Liter</option>
                             <option value="karung">Karung</option>
                         </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">Tanggal Sebar</label>
                    <input type="date" name="tanggal_sebar" value="<?= date('Y-m-d') ?>" class="w-full border p-2.5 rounded-xl text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full border p-2.5 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-2 border-t pt-4">
                <button type="button" onclick="closeModal('modal-pupuk')" class="px-4 py-2 text-gray-500 text-sm font-bold">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. MODAL LAPOR HAMA (UPDATED WITH UPLOAD & PREVIEW) -->
<div id="modal-hama" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 overflow-hidden transform transition-all scale-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bug mr-2 text-red-500"></i>Lapor Serangan Hama
        </h3>
        
        <!-- PENTING: enctype Multipart -->
        <form action="<?= base_url('petani/siklus/lapor_hama') ?>" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="siklus_id" id="input_siklus_id_hama">
            
            <div class="space-y-4">
                <!-- Dropdown Jenis Hama -->
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase">Jenis Hama</label>
                    <select name="hama_id" required class="w-full border border-gray-300 p-2.5 rounded-xl text-sm bg-white focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="" disabled selected>Pilih Hama...</option>
                        <?php if(isset($hama_opsi) && !empty($hama_opsi)): ?>
                            <?php foreach($hama_opsi as $h): ?>
                                <option value="<?= $h['hama_id'] ?>"><?= $h['nama_hama'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Dropdown Keparahan -->
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase">Tingkat Keparahan</label>
                    <select name="tingkat_keparahan" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm bg-white focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="Ringan">Ringan (Sedikit daun rusak)</option>
                        <option value="Sedang">Sedang (Terlihat jelas)</option>
                        <option value="Berat/Puso">Berat (Gagal Panen)</option>
                    </select>
                </div>

                <!-- Input Foto Bukti (Dengan Preview) -->
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase">Foto Bukti (Opsional)</label>
                    <div class="flex items-center justify-center w-full mt-2">
                        <label id="dropzone-area" class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 hover:bg-gray-50 hover:border-red-400 rounded-xl cursor-pointer transition relative overflow-hidden bg-gray-50">
                            
                            <!-- Default State -->
                            <div id="default-preview" class="flex flex-col items-center justify-center pt-7 pb-6">
                                <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-500 font-medium">Klik untuk ambil foto</p>
                                <p class="text-[10px] text-gray-400 mt-1">(JPG/PNG, Max 2MB)</p>
                            </div>

                            <!-- Active State: Preview Gambar -->
                            <div id="image-preview-container" class="hidden absolute inset-0 w-full h-full bg-white flex items-center justify-center">
                                <img id="image-preview" src="" alt="Preview" class="object-cover h-full w-full opacity-90">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity">
                                    <p class="text-white text-xs font-bold"><i class="fas fa-sync mr-1"></i> Ganti Foto</p>
                                </div>
                            </div>

                            <!-- Input File Asli -->
                            <input type="file" name="foto_bukti" id="input-foto" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)">
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-2 border-t border-gray-50 pt-4">
                <button type="button" onclick="closeModal('modal-hama')" class="px-4 py-2 text-gray-500 text-sm font-bold hover:bg-gray-50 rounded-lg">Batal</button>
                
                <!-- Tombol Lapor dengan ID & Onclick -->
                <button type="submit" id="btn-lapor" onclick="showLoading()" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-red-700 shadow-md flex items-center transition-all">
                    <span>Lapor Dinas</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- JAVASCRIPT ENGINE -->
<!-- ============================================= -->
<script>
    // 1. Fungsi Toggle Umum
    function toggleModal(modalID){
        const modal = document.getElementById(modalID);
        if(modal) {
            modal.classList.toggle("hidden");
            modal.classList.toggle("flex");
        }
    }

    // 2. Fungsi Buka Modal Pupuk (Isi ID Siklus)
    function openModalPupuk(id) {
        document.getElementById('input_siklus_id_pupuk').value = id; 
        const modal = document.getElementById('modal-pupuk');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // 3. Fungsi Buka Modal Hama (Isi ID Siklus)
    function openModalHama(id) {
        document.getElementById('input_siklus_id_hama').value = id; 
        
        // Reset Preview saat buka modal baru
        resetPreview();
        
        const modal = document.getElementById('modal-hama');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // 4. Fungsi Tutup Modal
    function closeModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // 5. Preview Image Logic
    function previewImage(input) {
        const defaultView = document.getElementById('default-preview');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');
        const dropzone = document.getElementById('dropzone-area');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                defaultView.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                dropzone.classList.add('border-green-500');
                dropzone.classList.remove('border-gray-300');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 6. Reset Preview (Dipanggil saat modal dibuka)
    function resetPreview() {
        document.getElementById('default-preview').classList.remove('hidden');
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('input-foto').value = ""; // Reset input file
        document.getElementById('dropzone-area').classList.remove('border-green-500');
        document.getElementById('dropzone-area').classList.add('border-gray-300');
        
        // Reset Tombol Loading ke semula
        const btn = document.getElementById('btn-lapor');
        btn.innerHTML = '<span>Lapor Dinas</span>';
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    }

    // 7. Loading State Logic
    function showLoading() {
        const btn = document.getElementById('btn-lapor');
        // Hanya visual change, form tetap submit normal
        // Timeout kecil agar validasi HTML5 sempat jalan (misal required)
        setTimeout(() => {
             btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
             btn.classList.add('opacity-75', 'cursor-not-allowed');
        }, 100);
    }
</script>
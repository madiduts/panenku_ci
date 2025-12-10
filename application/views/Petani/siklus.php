<!-- ======================================================= -->
<!-- HEADER & TOMBOL UTAMA -->
<!-- ======================================================= -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Siklus Tanam Aktif</h2>
        <p class="text-gray-500 mt-1">Pantau perkembangan tanaman di lahan Anda</p>
    </div>
    <button onclick="toggleModal('modal-tambah-siklus')" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 shadow-lg flex items-center cursor-pointer transition transform hover:-translate-y-0.5">
        <i class="fas fa-seedling mr-2"></i> Mulai Tanam Baru
    </button>
</div>

<!-- ======================================================= -->
<!-- NOTIFIKASI FLASH DATA (Success/Error) -->
<!-- ======================================================= -->
<?php if($this->session->flashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 animate-fade-in">
        <strong class="font-bold"><i class="fas fa-check-circle mr-1"></i> Berhasil!</strong>
        <span class="block sm:inline"><?= $this->session->flashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 animate-fade-in">
        <strong class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Gagal!</strong>
        <span class="block sm:inline"><?= $this->session->flashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- ======================================================= -->
<!-- DAFTAR SIKLUS (LOOPING) -->
<!-- ======================================================= -->
<div class="space-y-6 pb-20">
    <?php if(isset($siklus_list) && !empty($siklus_list)): ?>
        <?php foreach($siklus_list as $s): 
            // Logika Hitung Umur & Progress
            $tgl_tanam = new DateTime($s['tanggal_tanam']);
            $tgl_skrg  = new DateTime();
            $umur      = $tgl_tanam->diff($tgl_skrg)->days;
            
            $tgl_panen = new DateTime($s['estimasi_panen']);
            $diff      = $tgl_skrg->diff($tgl_panen);
            $sisa_hari = $diff->days; 
            $is_late   = ($diff->invert == 1); 
            
            $total_durasi = $tgl_tanam->diff($tgl_panen)->days;
            $progress = ($total_durasi > 0) ? round(($umur / $total_durasi) * 100) : 0;
            if($progress > 100) $progress = 100;
        ?>
        
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition relative overflow-hidden group">
            <!-- Dekorasi Background -->
            <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                <i class="fas fa-leaf text-8xl text-green-500 transform rotate-12"></i>
            </div>

            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6 relative z-10">
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 rounded-xl bg-green-50 flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                        <i class="fas fa-seedling text-2xl"></i>
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
                        Status: Aktif
                    </div>
                    <p class="text-xs text-gray-400">Estimasi Panen</p>
                    <p class="text-sm font-bold text-gray-800">
                        <?= date('d M Y', strtotime($s['estimasi_panen'])) ?>
                    </p>
                </div>
            </div>

            <!-- Progress Bar Section -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 relative z-10">
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

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-50 pt-4 relative z-10">
                <button type="button" onclick="openModalPupuk(<?= $s['siklus_id'] ?>)" class="text-gray-600 hover:text-green-700 bg-white hover:bg-green-50 border border-gray-200 hover:border-green-200 text-sm font-bold px-4 py-2 rounded-lg transition shadow-sm flex items-center">
                    <i class="fas fa-flask mr-2 text-green-500"></i> Catat Pupuk
                </button>
                <button type="button" onclick="openModalHama(<?= $s['siklus_id'] ?>)" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 hover:border-red-200 text-sm font-bold px-4 py-2 rounded-lg transition shadow-sm flex items-center">
                    <i class="fas fa-bug mr-2"></i> Lapor Hama
                </button>
            </div>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-seedling text-3xl"></i>
            </div>
            <h3 class="text-gray-900 font-bold text-lg">Belum Ada Tanaman</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Anda belum memulai siklus tanam di lahan manapun.</p>
        </div>
    <?php endif; ?>
</div>


<!-- ======================================================= -->
<!-- BAGIAN MODAL (POP UP) -->
<!-- ======================================================= -->

<!-- 1. MODAL NOTIFIKASI / REKOMENDASI (FITUR BARU) -->
<!-- Modal ini tersembunyi (hidden) secara default, akan dibuka oleh JS jika ada data -->
<div id="modal-rekomendasi" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border-t-8 border-green-500 transform scale-100 transition-all">
        
        <!-- Header Modal -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-start bg-green-50/30">
            <div>
                <h3 class="font-bold text-gray-900 text-xl">Laporan Terverifikasi</h3>
                <p class="text-sm text-green-600 font-bold mt-1 flex items-center">
                    <i class="fas fa-check-circle mr-1.5"></i> Respon Resmi Dinas Pertanian
                </p>
            </div>
            <button onclick="closeModal('modal-rekomendasi')" class="text-gray-400 hover:text-gray-600 transition bg-white rounded-full p-1 shadow-sm hover:shadow-md">
                <i class="fas fa-times text-lg w-6 h-6 flex items-center justify-center"></i>
            </button>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Info Hama (Card Merah) -->
            <div class="flex items-start space-x-4 bg-red-50 p-4 rounded-xl border border-red-100 shadow-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-500 shrink-0">
                    <i class="fas fa-bug text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-0.5">Jenis Serangan</p>
                    <h4 id="rek-nama-hama" class="font-bold text-gray-900 text-lg leading-tight">...</h4>
                    <div class="flex items-center gap-3 mt-2">
                        <span id="rek-keparahan" class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded">...</span>
                        <span id="rek-tgl" class="text-xs text-red-600"><i class="far fa-clock mr-1"></i> ...</span>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Dinas (Card Hijau - FOKUS UTAMA) -->
            <div>
                <label class="flex items-center text-xs font-bold text-gray-500 uppercase mb-2">
                    <i class="fas fa-user-md mr-1.5 text-blue-500"></i> Rekomendasi Penanganan
                </label>
                <div class="bg-white border-l-4 border-blue-500 rounded-r-xl shadow-sm p-5 text-sm text-gray-700 leading-relaxed relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5"><i class="fas fa-clipboard-check text-6xl text-blue-500"></i></div>
                    <p id="rek-catatan" class="relative z-10 font-medium text-base">
                        ...
                    </p>
                </div>
            </div>

            <!-- Penanganan Umum (Accordion Style) -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 flex items-center justify-between cursor-pointer" onclick="document.getElementById('info-tambahan').classList.toggle('hidden')">
                    <span class="text-xs font-bold text-gray-600 flex items-center">
                        <i class="fas fa-book-open mr-2 text-gray-400"></i> Info Teknis (SOP)
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </div>
                <div id="info-tambahan" class="hidden bg-white px-4 py-3 text-xs text-gray-500 border-t border-gray-200">
                    <p id="rek-umum" class="italic">...</p>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button onclick="closeModal('modal-rekomendasi')" class="bg-black text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-lg transform hover:-translate-y-0.5 flex items-center">
                <i class="fas fa-check mr-2"></i> Saya Mengerti
            </button>
        </div>
    </div>
</div>

<!-- 2. MODAL TAMBAH SIKLUS -->
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
                    <select name="lahan_id" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-green-500 outline-none">
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
                        <select name="komoditas_id" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-green-500 outline-none">
                            <?php if(isset($komoditas_opsi) && !empty($komoditas_opsi)): ?>
                                <?php foreach($komoditas_opsi as $k): ?>
                                    <option value="<?= $k['komoditas_id'] ?>"><?= $k['nama_komoditas'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Tanam <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tanam" value="<?= date('Y-m-d') ?>" required class="w-full p-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex space-x-3 bg-gray-50">
                <button type="button" onclick="toggleModal('modal-tambah-siklus')" class="flex-1 py-2.5 border rounded-xl text-sm font-bold text-gray-600 hover:bg-white transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 shadow-md transition">Mulai Tanam</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. MODAL RIWAYAT PUPUK -->
<div id="modal-pupuk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-green-50/30">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-flask mr-2 text-green-600"></i>Catat Pemupukan</h3>
        </div>
        <form action="<?= base_url('petani/siklus/simpan_pupuk') ?>" method="POST">
            <input type="hidden" name="siklus_id" id="input_siklus_id_pupuk">
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Jenis Pupuk</label>
                    <input type="text" name="jenis_pupuk" required placeholder="Contoh: Urea, NPK" class="w-full border p-2.5 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div class="flex space-x-3">
                    <div class="flex-1">
                        <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Jumlah</label>
                        <input type="number" step="0.1" name="jumlah_sebar" required class="w-full border p-2.5 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="w-1/3">
                         <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Satuan</label>
                         <select name="satuan" class="w-full border p-2.5 rounded-xl text-sm bg-white focus:ring-2 focus:ring-green-500 outline-none">
                             <option value="kg">Kg</option>
                             <option value="liter">Liter</option>
                             <option value="karung">Karung</option>
                         </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Tanggal Sebar</label>
                    <input type="date" name="tanggal_sebar" value="<?= date('Y-m-d') ?>" class="w-full border p-2.5 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2" class="w-full border p-2.5 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="p-5 border-t bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('modal-pupuk')" class="px-4 py-2 text-gray-500 text-sm font-bold hover:bg-white rounded-lg transition">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-green-700 shadow-md">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL LAPOR HAMA -->
<div id="modal-hama" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-50/30">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>Lapor Hama
            </h3>
        </div>
        
        <form action="<?= base_url('petani/siklus/lapor_hama') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="siklus_id" id="input_siklus_id_hama">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Jenis Hama</label>
                    <select name="hama_id" required class="w-full border border-gray-300 p-2.5 rounded-xl text-sm bg-white focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="" disabled selected>Pilih Hama...</option>
                        <?php if(isset($hama_opsi) && !empty($hama_opsi)): ?>
                            <?php foreach($hama_opsi as $h): ?>
                                <option value="<?= $h['hama_id'] ?>"><?= $h['nama_hama'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Tingkat Keparahan</label>
                    <select name="tingkat_keparahan" class="w-full border border-gray-300 p-2.5 rounded-xl text-sm bg-white focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="Ringan">Ringan (Sedikit)</option>
                        <option value="Sedang">Sedang (Terlihat Jelas)</option>
                        <option value="Berat/Puso">Berat (Gagal Panen)</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600 uppercase mb-1 block">Foto Bukti</label>
                    <label id="dropzone-area" class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 hover:bg-gray-50 hover:border-red-400 rounded-xl cursor-pointer transition relative overflow-hidden bg-gray-50 items-center justify-center">
                        <div id="default-preview" class="flex flex-col items-center justify-center pt-2">
                            <i class="fas fa-camera text-gray-400 text-2xl mb-1"></i>
                            <p class="text-xs text-gray-500">Ambil/Pilih Foto</p>
                        </div>
                        <div id="image-preview-container" class="hidden absolute inset-0 w-full h-full bg-white">
                            <img id="image-preview" src="" class="object-cover h-full w-full opacity-90">
                        </div>
                        <input type="file" name="foto_bukti" id="input-foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
            </div>
            
            <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('modal-hama')" class="px-4 py-2 text-gray-500 text-sm font-bold hover:bg-white rounded-lg transition">Batal</button>
                <button type="submit" id="btn-lapor" onclick="showLoading()" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-red-700 shadow-md flex items-center transition">
                    <span>Kirim Laporan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- JAVASCRIPT ENGINE -->
<!-- ============================================= -->
<script>
    function toggleModal(modalID){
        const modal = document.getElementById(modalID);
        if(modal) {
            modal.classList.toggle("hidden");
            modal.classList.toggle("flex");
        }
    }

    function openModalPupuk(id) {
        document.getElementById('input_siklus_id_pupuk').value = id; 
        const modal = document.getElementById('modal-pupuk');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openModalHama(id) {
        document.getElementById('input_siklus_id_hama').value = id; 
        resetPreview();
        const modal = document.getElementById('modal-hama');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

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

    function resetPreview() {
        document.getElementById('default-preview').classList.remove('hidden');
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('input-foto').value = ""; 
        document.getElementById('dropzone-area').classList.remove('border-green-500');
        document.getElementById('dropzone-area').classList.add('border-gray-300');
        
        const btn = document.getElementById('btn-lapor');
        btn.innerHTML = '<span>Kirim Laporan</span>';
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    }

    function showLoading() {
        const btn = document.getElementById('btn-lapor');
        setTimeout(() => {
             btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
             btn.classList.add('opacity-75', 'cursor-not-allowed');
        }, 100);
    }

    // --- LOGIKA UTAMA: AUTO-OPEN MODAL REKOMENDASI ---
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Cek Data dari PHP (Controller)
        <?php if(isset($highlight_hama) && !empty($highlight_hama)): ?>
            
            // Konversi PHP Array ke JS Object
            const data = <?= json_encode($highlight_hama) ?>;
            
            // 2. Populate Data ke Modal UI
            document.getElementById('rek-nama-hama').innerText = data.nama_hama || 'Hama Tidak Dikenal';
            document.getElementById('rek-keparahan').innerText = (data.tingkat_keparahan || 'Unknown').toUpperCase();
            
            // Format Tanggal
            const dateStr = data.tanggal_lapor || new Date().toISOString();
            const dateObj = new Date(dateStr);
            document.getElementById('rek-tgl').innerHTML = '<i class="far fa-clock mr-1"></i> ' + dateObj.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
            
            // Catatan Dinas
            const catatan = data.catatan_validasi ? data.catatan_validasi : "Tidak ada instruksi khusus. Silakan ikuti SOP umum.";
            document.getElementById('rek-catatan').innerText = catatan;
            
            // Info Umum
            document.getElementById('rek-umum').innerText = data.penanganan_umum ? data.penanganan_umum : "Data penanganan umum belum tersedia di database master.";

            // 3. Tampilkan Modal
            const modal = document.getElementById('modal-rekomendasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // 4. Bersihkan URL (Hapus ?hama_id=...) agar bersih saat refresh
            const url = new URL(window.location);
            url.searchParams.delete('hama_id');
            window.history.replaceState({}, document.title, url);
            
        <?php endif; ?>
    });
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
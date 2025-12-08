<!-- HEADER & TOMBOL TAMBAH -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard Lahan</h2>
        <p class="text-gray-500 mt-1">Kelola semua lahan pertanian Anda</p>
    </div>
    <!-- Tombol trigger modal -->
    <button onclick="toggleModal('modal-tambah-lahan')" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 shadow-lg flex items-center cursor-pointer transition transform hover:-translate-y-0.5">
        <i class="fas fa-plus mr-2 text-xs"></i>Tambah Lahan
    </button>
</div>

<!-- FLASHDATA NOTIFICATIONS (Penting untuk Feedback User) -->
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

<!-- INFO CARDS (Ringkasan Statistik) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Total Lahan -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
            <i class="fas fa-map-marker-alt text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Lahan</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= isset($lahan_list) ? count($lahan_list) : 0 ?></h3>
        </div>
    </div>
    
    <!-- Card 2: Total Luas -->
    <!-- Note: Logika hitung total luas bisa ditambahkan di Controller Lahan.php nanti -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <i class="fas fa-expand text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Estimasi Luas</p>
            <h3 class="text-2xl font-bold text-gray-800">
                <?php 
                    $total_luas = 0;
                    if(isset($lahan_list)) {
                        foreach($lahan_list as $l) {
                            // Menghapus teks ' ha' untuk penjumlahan
                            $total_luas += floatval(str_replace(' ha', '', $l->luas));
                        }
                    }
                    echo $total_luas . ' ha';
                ?>
            </h3>
        </div>
    </div>

    <!-- Card 3: Lahan Aktif -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
            <i class="fas fa-seedling text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Lahan Aktif</p>
            <h3 class="text-2xl font-bold text-gray-800">
                <?php 
                    $aktif = 0;
                    if(isset($lahan_list)) {
                        foreach($lahan_list as $l) {
                            if($l->status == 'Aktif') $aktif++;
                        }
                    }
                    echo $aktif;
                ?>
            </h3>
        </div>
    </div>
</div>

<div class="mb-4 flex justify-between items-end">
    <div>
        <h3 class="font-bold text-lg text-gray-800">Daftar Lahan</h3>
        <p class="text-sm text-gray-400">Aset lahan yang terdaftar dalam sistem</p>
    </div>
</div>

<!-- DAFTAR LAHAN LIST -->
<div class="space-y-4 pb-10">
    <?php if(isset($lahan_list) && !empty($lahan_list)): ?>
        <?php foreach($lahan_list as $l): 
            $is_aktif = ($l->status === 'Aktif');
            $status_class = $is_aktif ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-600 border-gray-200';
            $icon_class = $is_aktif ? 'text-green-500' : 'text-gray-400';
        ?>
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-200 group">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center <?= $icon_class ?>">
                        <i class="fas fa-layer-group text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-base"><?= $l->lokasi ?></h4> <!-- Menampilkan Lokasi Desa sebagai Judul -->
                        <p class="text-xs text-gray-400 font-mono mt-0.5"><?= $l->id ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border <?= $status_class ?>">
                        <?= $l->status ?>
                    </span>
                    <!-- Tombol Opsi (Titik Tiga) -->
                    <button class="text-gray-300 hover:text-gray-600 p-2 rounded-full hover:bg-gray-50 transition">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 border-t border-gray-50 pt-4">
                <!-- Detail 1: Kategori -->
                <div>
                    <div class="flex items-center text-gray-400 text-xs mb-1.5">
                        <i class="fas fa-mountain mr-2 opacity-50"></i> Kategori Tanah
                    </div>
                    <p class="text-sm font-semibold text-gray-700"><?= $l->tanah ?></p>
                </div>

                <!-- Detail 2: Luas -->
                <div>
                    <div class="flex items-center text-gray-400 text-xs mb-1.5">
                        <i class="fas fa-ruler-combined mr-2 opacity-50"></i> Luas Area
                    </div>
                    <p class="text-sm font-semibold text-gray-700"><?= $l->luas ?></p>
                </div>

                <!-- Detail 3: Tanaman -->
                <div>
                    <div class="flex items-center text-gray-400 text-xs mb-1.5">
                        <i class="fas fa-leaf mr-2 opacity-50"></i> Komoditas
                    </div>
                    <p class="text-sm font-semibold <?= $is_aktif ? 'text-green-600' : 'text-gray-400 italic' ?>">
                        <?= $l->tanaman ?>
                    </p>
                </div>

                <!-- Detail 4: Progress (Visual) -->
                <div>
                    <div class="flex items-center text-gray-400 text-xs mb-1.5">
                        <i class="fas fa-chart-line mr-2 opacity-50"></i> Progress
                    </div>
                    <?php if($is_aktif): ?>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                            <!-- FIX: Gunakan variabel $l->progress, bukan angka mati 45% -->
                            <div class="bg-green-500 h-1.5 rounded-full transition-all duration-1000" style="width: <?= $l->progress ?>%"></div>
                        </div>
                        <p class="text-[10px] text-green-600 mt-1 font-bold"><?= $l->progress ?>%</p>
                    <?php else: ?>
                        <span class="text-xs text-gray-400">-</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-folder-open text-2xl"></i>
            </div>
            <h3 class="text-gray-900 font-medium">Belum ada data lahan</h3>
            <p class="text-gray-500 text-sm mt-1">Mulai tambahkan aset lahan Anda untuk mencatat siklus tanam.</p>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL TAMBAH LAHAN -->
<!-- Backdrop & Container -->
<div id="modal-tambah-lahan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-100">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Tambah Lahan Baru</h3>
                <p class="text-xs text-gray-500 mt-0.5">Lahan baru akan berstatus <span class="font-semibold text-gray-700">Istirahat</span></p>
            </div>
            <button onclick="toggleModal('modal-tambah-lahan')" class="text-gray-400 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- FORM START -->
        <!-- PENTING: Action mengarah ke Controller Lahan -> method tambah -->
        <form action="<?= base_url('petani/lahan/tambah') ?>" method="POST">
            <div class="p-6 space-y-5">
                
                <!-- Input: Nama Desa / Lokasi -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Lokasi / Nama Desa <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-map-marked-alt"></i>
                        </span>
                        <input type="text" name="lokasi_desa" required 
                               placeholder="Contoh: Desa Sukamaju Blok A" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition shadow-sm placeholder-gray-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <!-- Input: Luas -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Luas (Hektar) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-ruler-combined"></i>
                            </span>
                            <input type="number" step="0.1" name="luas_lahan" required 
                                   placeholder="0.0" 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition shadow-sm">
                        </div>
                    </div>
                    
                    <!-- Input: Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-tree"></i>
                            </span>
                            <select name="kategori_lahan" required 
                                    class="w-full pl-10 pr-8 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition shadow-sm bg-white appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="Sawah Irigasi">Sawah Irigasi</option>
                                <option value="Tadah Hujan">Tadah Hujan</option>
                                <option value="Ladang">Ladang / Tegal</option>
                                <option value="Perkebunan">Perkebunan</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 flex space-x-3 bg-gray-50/50">
                <button type="button" onclick="toggleModal('modal-tambah-lahan')" class="flex-1 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition duration-200">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 shadow-md hover:shadow-lg transition duration-200 flex justify-center items-center group">
                    <span>Simpan Data</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform text-xs"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Modal (Jika belum ada di layout utama) -->
<script>
    function toggleModal(modalID){
        document.getElementById(modalID).classList.toggle("hidden");
        document.getElementById(modalID).classList.toggle("flex");
    }
</script>
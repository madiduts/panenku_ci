<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard Lahan</h2>
        <p class="text-gray-500 mt-1">Kelola semua lahan pertanian Anda</p>
    </div>
    <!-- Tombol trigger modal -->
    <button onclick="toggleModal('modal-tambah-lahan')" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 shadow-lg flex items-center cursor-pointer">
        <i class="fas fa-plus mr-2 text-xs"></i>Tambah Lahan
    </button>
</div>

<!-- INFO CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-map-marker-alt"></i></div>
        <div><p class="text-xs text-gray-400">Total Lahan</p><h3 class="text-2xl font-bold text-gray-800"><?= count($lahan_list) ?></h3></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-expand"></i></div>
        <div><p class="text-xs text-gray-400">Total Luas</p><h3 class="text-2xl font-bold text-gray-800">108 ha</h3></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 flex items-center space-x-4 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-map-pin"></i></div>
        <div><p class="text-xs text-gray-400">Lahan Aktif</p><h3 class="text-2xl font-bold text-gray-800">3</h3></div>
    </div>
</div>

<div class="mb-4">
    <h3 class="font-bold text-lg text-gray-800">Daftar Lahan</h3>
    <p class="text-sm text-gray-400">Semua lahan yang terdaftar dalam sistem</p>
</div>

<!-- LOOPING DAFTAR LAHAN -->
<div class="space-y-4">
    <?php foreach($lahan_list as $l): 
        $status_color = ($l->status === 'Aktif') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
    ?>
    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-3">
                <h4 class="font-bold text-gray-800">Lahan <?= $l->id ?></h4>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide <?= $status_color ?>">
                    <?= $l->status ?>
                </span>
            </div>
            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
            <div>
                <div class="flex items-center text-gray-400 text-xs mb-1"><i class="fas fa-map-marker-alt mr-2 w-4"></i> Lokasi</div>
                <p class="text-sm font-medium text-gray-800 pl-6"><?= $l->lokasi ?></p>
            </div>
            <div>
                <div class="flex items-center text-gray-400 text-xs mb-1"><i class="fas fa-expand mr-2 w-4"></i> Luas</div>
                <p class="text-sm font-medium text-gray-800 pl-6"><?= $l->luas ?></p>
            </div>
            <div>
                <div class="flex items-center text-gray-400 text-xs mb-1"><p class="text-xs">Jenis Tanah</p></div>
                <p class="text-sm font-bold text-gray-800 mt-1"><?= $l->tanah ?></p>
                <p class="text-[10px] text-gray-400 italic mt-1">Sistem irigasi teknis, akses jalan baik</p>
            </div>
            <div>
                <div class="flex items-center text-gray-400 text-xs mb-1"><p class="text-xs">Tanaman Saat Ini</p></div>
                <p class="text-sm font-bold text-gray-800 mt-1"><?= $l->tanaman ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- MODAL TAMBAH LAHAN (Disembunyikan, dipanggil via ID) -->
<div id="modal-tambah-lahan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
        <button onclick="toggleModal('modal-tambah-lahan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Tambah Lahan Baru</h3>
            <p class="text-sm text-gray-500 mt-1">Aset lahan baru akan berstatus <span class="font-bold text-gray-800">Istirahat</span> secara default.</p>
        </div>

        <!-- Perhatikan action URL-nya -->
        <form action="<?= base_url('petani/lahan/simpan') ?>" method="POST">
            <div class="p-6 space-y-4">
                
                <!-- INPUT 1: Lokasi (Di UI kita sebut Nama/Lokasi agar user paham) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi / Nama Desa <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_desa" required placeholder="Contoh: Desa Sukamaju Blok A" 
                           class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- INPUT 2: Luas -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Luas (Hektar) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="luas_lahan" required placeholder="0.0" 
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm">
                    </div>
                    
                    <!-- INPUT 3: Kategori (PENTING: Harus sesuai ENUM di Database) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Lahan <span class="text-red-500">*</span></label>
                        <select name="kategori_lahan" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm bg-white">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Sawah Irigasi">Sawah Irigasi</option>
                            <option value="Tadah Hujan">Tadah Hujan</option>
                            <option value="Ladang">Ladang / Tegal</option>
                            <option value="Perkebunan">Perkebunan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex space-x-3">
                <button type="button" onclick="toggleModal('modal-tambah-lahan')" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 shadow-lg">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
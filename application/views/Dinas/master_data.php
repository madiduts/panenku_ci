<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Master Data Management</h2>
    <p class="text-gray-500 mt-1">Kelola data komoditas, hama, kategori lahan, dan petani</p>
</div>

<!-- 4 Overview Cards (Updated Grid) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-green-500"><i class="fas fa-leaf"></i><span class="text-sm font-medium text-gray-600">Komoditas</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800"><?= $total_komoditas ?? 0 ?></h3><p class="text-xs text-gray-400 mt-1">Varietas</p></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-red-500"><i class="fas fa-bug"></i><span class="text-sm font-medium text-gray-600">Hama</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800"><?= $total_hama ?? 0 ?></h3><p class="text-xs text-gray-400 mt-1">Jenis Penyakit</p></div>
    </div>
    <!-- Card Baru -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-orange-500"><i class="fas fa-map"></i><span class="text-sm font-medium text-gray-600">Kategori Lahan</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800"><?= $total_lahan_kat ?? 0 ?></h3><p class="text-xs text-gray-400 mt-1">Tipe Lahan</p></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-blue-500"><i class="far fa-user"></i><span class="text-sm font-medium text-gray-600">Petani</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800"><?= $total_petani ?? 0 ?></h3><p class="text-xs text-gray-400 mt-1">Akun Aktif</p></div>
    </div>
</div>

<!-- Tabs Navigation -->
<?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'komoditas'; ?>
<div class="bg-gray-100 p-1 rounded-lg w-full flex mb-6 overflow-x-auto">
    <a href="?tab=komoditas" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition whitespace-nowrap <?= $tab === 'komoditas' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Komoditas</a>
    <a href="?tab=hama" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition whitespace-nowrap <?= $tab === 'hama' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Hama & Penyakit</a>
    <!-- Tab Baru -->
    <a href="?tab=lahan" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition whitespace-nowrap <?= $tab === 'lahan' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Kategori Lahan</a>
    <a href="?tab=petani" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition whitespace-nowrap <?= $tab === 'petani' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Data Petani</a>
</div>

<!-- FLASH DATA -->
<?php if($this->session->flashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 min-h-[400px]">
    
    <!-- === TAB KOMODITAS === -->
    <?php if($tab === 'komoditas'): ?>
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Daftar Komoditas</h3><p class="text-sm text-gray-500">Referensi tanaman</p></div>
        <button onclick="openModalTambah('komoditas')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100 uppercase">
                <tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Komoditas</th><th class="py-4 pr-4">Kategori</th><th class="py-4 pr-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach($masterKomoditas as $item): ?>
                <tr class="hover:bg-gray-50 group">
                    <td class="py-4 text-gray-500"><?= $item->komoditas_id ?></td>
                    <td class="py-4 font-bold text-gray-800"><?= $item->nama_komoditas ?></td>
                    <td class="py-4"><span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-bold"><?= $item->kategori ?></span></td>
                    <td class="py-4 text-center">
                        <a href="<?= base_url('dinas/master_data/hapus_komoditas/'.$item->komoditas_id) ?>" onclick="return confirm('Hapus?')" class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- === TAB HAMA === -->
    <?php elseif($tab === 'hama'): ?>
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Referensi Hama</h3><p class="text-sm text-gray-500">Database penyakit tanaman</p></div>
        <button onclick="openModalTambah('hama')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100 uppercase">
                <tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Hama</th><th class="py-4 pr-4">Penanganan</th><th class="py-4 pr-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach($masterHama as $item): ?>
                <tr class="hover:bg-gray-50 group">
                    <td class="py-4 text-gray-500"><?= $item->hama_id ?></td>
                    <td class="py-4 font-bold text-gray-800"><?= $item->nama_hama ?></td>
                    <td class="py-4 text-gray-600 text-xs max-w-md truncate" title="<?= $item->deskripsi_penanganan ?>"><?= $item->deskripsi_penanganan ?></td>
                    <td class="py-4 text-center">
                        <a href="<?= base_url('dinas/master_data/hapus_hama/'.$item->hama_id) ?>" onclick="return confirm('Hapus?')" class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- === TAB KATEGORI LAHAN (BARU) === -->
    <?php elseif($tab === 'lahan'): ?>
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Kategori Lahan</h3><p class="text-sm text-gray-500">Jenis lahan yang tersedia</p></div>
        <button onclick="openModalTambah('lahan')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100 uppercase">
                <tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Kategori</th><th class="py-4 pr-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(empty($masterLahan)): ?>
                    <tr><td colspan="3" class="py-8 text-center text-gray-400">Belum ada kategori lahan.</td></tr>
                <?php else: ?>
                    <?php foreach($masterLahan as $item): ?>
                    <tr class="hover:bg-gray-50 group">
                        <td class="py-4 text-gray-500"><?= $item->kategori_id ?></td>
                        <td class="py-4 font-bold text-gray-800"><?= $item->nama_kategori ?></td>
                        <td class="py-4 text-center">
                            <a href="<?= base_url('dinas/master_data/hapus_kategori_lahan/'.$item->kategori_id) ?>" onclick="return confirm('Hapus kategori ini?')" class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- === TAB PETANI === -->
    <?php elseif($tab === 'petani'): ?>
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Data Petani</h3><p class="text-sm text-gray-500">Akun petani terdaftar</p></div>
        <button onclick="openModalTambah('petani')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100 uppercase">
                <tr><th class="py-4 pr-4">Nama</th><th class="py-4 pr-4">Kontak</th><th class="py-4 pr-4">Lahan</th><th class="py-4 pr-4">Status</th><th class="py-4 pr-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach($masterPetani as $item): ?>
                <tr class="hover:bg-gray-50 group">
                    <td class="py-4 font-bold text-gray-800"><?= $item->full_name ?></td>
                    <td class="py-4 text-gray-500 text-xs"><?= $item->phone_number ?></td>
                    <td class="py-4 font-mono font-bold text-blue-600"><?= $item->jumlah_lahan ?></td>
                    <td class="py-4"><span class="px-2 py-1 rounded text-[10px] font-bold <?= $item->is_active == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-500' ?>"><?= $item->is_active == 1 ? 'Aktif' : 'Non-Aktif' ?></span></td>
                    <td class="py-4 text-center">
                        <a href="<?= base_url('dinas/master_data/hapus_petani/'.$item->user_id) ?>" onclick="return confirm('Non-aktifkan?')" class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="fas fa-ban"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>

<!-- CONTAINER MODAL -->
<div id="modal-container"></div>

<script>
    const BASE_URL = "<?= base_url() ?>";

    function openModalTambah(type) {
        const container = document.getElementById('modal-container');
        let content = '';

        if (type === 'komoditas') {
            content = `
            <form action="${BASE_URL}dinas/master_data/tambah_komoditas" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in relative">
                <div class="p-5 border-b border-gray-100"><h3 class="text-lg font-bold text-gray-900">Tambah Komoditas</h3></div>
                <div class="p-6 space-y-4">
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Nama</label><input type="text" name="nama" required class="w-full border p-2.5 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Kategori</label><select name="kategori" class="w-full border p-2.5 rounded-lg text-sm bg-white"><option>Pangan</option><option>Hortikultura</option><option>Perkebunan</option></select></div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex gap-3"><button type="button" onclick="closeModal()" class="flex-1 py-2 text-gray-500 font-bold text-sm">Batal</button><button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg font-bold text-sm">Simpan</button></div>
            </form>`;
        } 
        else if (type === 'hama') {
            content = `
            <form action="${BASE_URL}dinas/master_data/tambah_hama" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                <div class="p-5 border-b border-gray-100"><h3 class="text-lg font-bold text-gray-900">Tambah Hama</h3></div>
                <div class="p-6 space-y-4">
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Hama</label><input type="text" name="nama" required class="w-full border p-2.5 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Penanganan</label><textarea name="penanganan" required class="w-full border p-2.5 rounded-lg text-sm h-24"></textarea></div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex gap-3"><button type="button" onclick="closeModal()" class="flex-1 py-2 text-gray-500 font-bold text-sm">Batal</button><button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg font-bold text-sm">Simpan</button></div>
            </form>`;
        } 
        else if (type === 'lahan') { // MODAL LAHAN (BARU)
            content = `
            <form action="${BASE_URL}dinas/master_data/tambah_kategori_lahan" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in relative">
                <div class="p-5 border-b border-gray-100"><h3 class="text-lg font-bold text-gray-900">Tambah Kategori Lahan</h3></div>
                <div class="p-6 space-y-4">
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Kategori</label><input type="text" name="nama" required class="w-full border p-2.5 rounded-lg text-sm" placeholder="Contoh: Sawah Tadah Hujan"></div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex gap-3"><button type="button" onclick="closeModal()" class="flex-1 py-2 text-gray-500 font-bold text-sm">Batal</button><button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg font-bold text-sm">Simpan</button></div>
            </form>`;
        }
        else {
            content = `
            <form action="${BASE_URL}dinas/master_data/tambah_petani" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
                <div class="p-5 border-b border-gray-100"><h3 class="text-lg font-bold text-gray-900">Registrasi Petani</h3></div>
                <div class="p-6 space-y-4">
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Nama</label><input type="text" name="nama" required class="w-full border p-2.5 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Email</label><input type="email" name="email" class="w-full border p-2.5 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Telp</label><input type="text" name="telp" required class="w-full border p-2.5 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Alamat</label><textarea name="alamat" class="w-full border p-2.5 rounded-lg text-sm h-20"></textarea></div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex gap-3"><button type="button" onclick="closeModal()" class="flex-1 py-2 text-gray-500 font-bold text-sm">Batal</button><button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg font-bold text-sm">Simpan</button></div>
            </form>`;
        }

        container.innerHTML = `<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">${content}</div>`;
    }

    function closeModal() {
        document.getElementById('modal-container').innerHTML = '';
    }
</script>
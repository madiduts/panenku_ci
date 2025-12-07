<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Master Data Management</h2>
    <p class="text-gray-500 mt-1">Kelola data komoditas, hama, dan petani</p>
</div>

<!-- 3 Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-green-500"><i class="fas fa-leaf"></i><span class="text-sm font-medium text-gray-600">Total Komoditas</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">Varietas terdaftar</p></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-red-500"><i class="fas fa-bug"></i><span class="text-sm font-medium text-gray-600">Referensi Hama</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">Database hama</p></div>
    </div>
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-32 flex flex-col justify-between">
        <div class="flex items-center space-x-2 text-blue-500"><i class="far fa-user"></i><span class="text-sm font-medium text-gray-600">Data Petani</span></div>
        <div><h3 class="text-3xl font-bold text-gray-800">5</h3><p class="text-xs text-gray-400 mt-1">4 aktif</p></div>
    </div>
</div>

<!-- Tabs (PHP Links) -->
<?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'komoditas'; ?>
<div class="bg-gray-100 p-1 rounded-lg w-full flex mb-6">
    <a href="?tab=komoditas" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition <?= $tab === 'komoditas' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Komoditas</a>
    <a href="?tab=hama" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition <?= $tab === 'hama' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Hama & Penyakit</a>
    <a href="?tab=petani" class="flex-1 py-2 text-center rounded-md text-sm font-bold transition <?= $tab === 'petani' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-800' ?>">Data Petani</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 min-h-[400px]">
    
    <?php if($tab === 'komoditas'): ?>
    <!-- TAB KOMODITAS -->
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Kelola Komoditas</h3><p class="text-sm text-gray-500">Tambah dan kelola data komoditas dan varietasnya</p></div>
        <button onclick="openModalTambah('komoditas')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Komoditas</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Komoditas</th><th class="py-4 pr-4">Varietas</th><th class="py-4 pr-4">Musim Tanam</th><th class="py-4 pr-4">Estimasi Panen</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">
            <?php foreach($masterKomoditas as $item): ?>
            <tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500"><?= $item->id ?></td><td class="py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-700"><?= $item->nama ?></span></td><td class="py-4 text-gray-600"><?= $item->varietas ?></td><td class="py-4 text-gray-600"><?= $item->musim ?></td><td class="py-4 text-gray-600"><?= $item->estimasi ?></td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>

    <?php elseif($tab === 'hama'): ?>
    <!-- TAB HAMA -->
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Kelola Referensi Hama & Penyakit</h3><p class="text-sm text-gray-500">Database hama dan cara penanganannya</p></div>
        <button onclick="openModalTambah('hama')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Hama</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama Hama</th><th class="py-4 pr-4">Nama Latin</th><th class="py-4 pr-4">Komoditas</th><th class="py-4 pr-4">Tingkat Bahaya</th><th class="py-4 pr-4">Penanganan</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">
            <?php foreach($masterHama as $item): ?>
            <tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500"><?= $item->id ?></td><td class="py-4 text-gray-800 font-medium"><?= $item->nama ?></td><td class="py-4 text-gray-500 italic"><?= $item->latin ?></td><td class="py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-700"><?= $item->komoditas ?></span></td><td class="py-4"><span class="px-2 py-1 rounded text-[10px] font-bold text-white <?= $item->bahaya === 'Tinggi' ? 'bg-red-500' : 'bg-orange-400' ?>"><?= $item->bahaya ?></span></td><td class="py-4 text-gray-600 text-xs max-w-xs truncate" title="<?= $item->penanganan ?>"><?= $item->penanganan ?></td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>

    <?php elseif($tab === 'petani'): ?>
    <!-- TAB PETANI -->
    <div class="flex justify-between items-center mb-6">
        <div><h3 class="font-bold text-gray-800 text-lg">Kelola Data Petani</h3><p class="text-sm text-gray-500">Database petani terdaftar dalam sistem</p></div>
        <button onclick="openModalTambah('petani')" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 flex items-center transition"><i class="fas fa-plus mr-2"></i> Tambah Petani</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm"><thead class="bg-white text-gray-800 text-xs font-bold border-b border-gray-100"><tr><th class="py-4 pr-4">ID</th><th class="py-4 pr-4">Nama</th><th class="py-4 pr-4">NIK</th><th class="py-4 pr-4">Desa</th><th class="py-4 pr-4">Total Lahan</th><th class="py-4 pr-4">Komoditas</th><th class="py-4 pr-4">Telepon</th><th class="py-4 pr-4">Status</th><th class="py-4 pr-4 text-center">Aksi</th></tr></thead><tbody class="divide-y divide-gray-50">
            <?php foreach($masterPetani as $item): ?>
            <tr class="hover:bg-gray-50 group"><td class="py-4 text-gray-500"><?= $item->id ?></td><td class="py-4 text-gray-800 font-medium"><?= $item->nama ?></td><td class="py-4 text-gray-500 text-xs"><?= $item->nik ?></td><td class="py-4 text-gray-600 flex items-center gap-1"><i class="fas fa-map-marker-alt text-gray-300"></i> <?= $item->desa ?></td><td class="py-4 text-gray-600"><?= $item->lahan ?></td><td class="py-4 text-gray-600 text-xs"><?= $item->komoditas ?></td><td class="py-4 text-gray-500 text-xs"><?= $item->telp ?></td><td class="py-4"><span class="px-2 py-1 rounded text-[10px] font-bold <?= $item->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>"><?= $item->status ?></span></td><td class="py-4 text-center"><button class="text-gray-400 hover:text-black border border-gray-200 hover:border-black rounded p-1.5 mx-1 transition"><i class="far fa-edit"></i></button><button class="text-gray-400 hover:text-red-600 border border-gray-200 hover:border-red-600 rounded p-1.5 mx-1 transition"><i class="far fa-trash-alt"></i></button></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
    <?php endif; ?>

</div>

<!-- SCRIPT UNTUK MODAL TAMBAH (Simple UI Injection) -->
<script>
    function openModalTambah(type) {
        const container = document.getElementById('modal-container');
        let content = '';

        if (type === 'komoditas') {
            content = `<div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative"><div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Komoditas Baru</h3></div><div class="p-6 space-y-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama</label><input type="text" class="w-full border p-2.5 rounded-lg text-sm"></div></div><div class="p-6 border-t"><button onclick="closeModal()" class="w-full bg-black text-white py-3 rounded-lg font-bold">Simpan</button></div></div>`;
        } else if (type === 'hama') {
            content = `<div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative"><div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Hama Baru</h3></div><div class="p-6 space-y-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Hama</label><input type="text" class="w-full border p-2.5 rounded-lg text-sm"></div></div><div class="p-6 border-t"><button onclick="closeModal()" class="w-full bg-black text-white py-3 rounded-lg font-bold">Simpan</button></div></div>`;
        } else {
            content = `<div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative"><div class="p-6 border-b border-gray-100"><h3 class="text-xl font-bold text-gray-900">Tambah Petani Baru</h3></div><div class="p-6 space-y-4"><div><label class="block text-xs font-bold text-gray-700 mb-1">Nama Petani</label><input type="text" class="w-full border p-2.5 rounded-lg text-sm"></div></div><div class="p-6 border-t"><button onclick="closeModal()" class="w-full bg-black text-white py-3 rounded-lg font-bold">Simpan</button></div></div>`;
        }

        container.innerHTML = `<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">${content}</div>`;
        document.body.classList.add('modal-active');
    }
</script>
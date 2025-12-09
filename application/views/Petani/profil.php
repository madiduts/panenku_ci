<?php 
// Logika sederhana untuk menentukan mode Edit atau Baca berdasarkan URL
$is_edit = isset($_GET['mode']) && $_GET['mode'] == 'edit';
?>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
    <p class="text-gray-500 mt-1">Kelola informasi profil dan akun Anda</p>
</div>

<div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row overflow-hidden animate-fade-in">
    
    <!-- LEFT COLUMN (Avatar & Actions) -->
    <div class="w-full md:w-1/3 p-8 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col items-center justify-center text-center">
        
        <!-- MODIFIKASI: Default Profile Picture a la Instagram -->
        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-6 overflow-hidden border-4 border-white shadow-sm relative">
            <?php if(isset($user['avatar_url']) && $user['avatar_url'] != ''): ?>
                <!-- Jika nanti sudah ada fitur upload foto, ini akan dipakai -->
                <img src="<?= $user['avatar_url'] ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <!-- Default Tampilan (Siluet Orang) -->
                <!-- mt-4 digunakan agar icon agak turun sedikit seperti gaya bust photo -->
                <i class="fas fa-user text-gray-400 text-7xl mt-4"></i>
            <?php endif; ?>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900"><?= isset($profil->namaLengkap) ? $profil->namaLengkap : 'Nama Petani' ?></h3>
        <p class="text-gray-500 text-sm mt-1 mb-6"><?= isset($profil->role) ? $profil->role : 'Role Petani' ?></p>
        
        <?php if($is_edit): ?>
            <!-- Tombol Batal Edit -->
            <a href="<?= base_url('petani/profil') ?>" class="flex items-center justify-center space-x-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer w-full md:w-auto">
                <i class="far fa-edit"></i>
                <span>Batal Edit</span>
            </a>
        <?php else: ?>
            <!-- Tombol Masuk Mode Edit -->
            <a href="?mode=edit" class="flex items-center justify-center space-x-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer w-full md:w-auto">
                <i class="far fa-edit"></i>
                <span>Edit Profil</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- RIGHT COLUMN (Data / Form) -->
    <div class="w-full md:w-2/3 p-8">
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 text-lg">Informasi Profil</h3>
            <p class="text-gray-500 text-sm mt-1">Detail informasi akun dan kontak Anda</p>
        </div>

        <?php if($is_edit): ?>
            <!-- FORM EDIT MODE -->
            <form action="<?= base_url('petani/profil/update') ?>" method="POST">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?= $profil->namaLengkap ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lahan/Pertanian</label>
                            <input type="text" name="nama_pertanian" value="<?= $profil->namaPertanian ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="<?= $profil->email ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="telepon" value="<?= $profil->telepon ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <input type="text" name="alamat" value="<?= $profil->alamat ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea name="bio" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm h-24 focus:ring-2 focus:ring-black focus:border-black outline-none resize-none transition"><?= $profil->bio ?></textarea>
                    </div>

                    <div class="pt-4 flex items-center space-x-3">
                        <button type="submit" onclick="alert('Simulasi: Perubahan disimpan!')" class="px-6 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg transition">Simpan Perubahan</button>
                        <a href="<?= base_url('petani/profil') ?>" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
                    </div>
                </div>
            </form>

        <?php else: ?>
            <!-- READ ONLY MODE -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                <div>
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-user w-5"></i> Nama Lengkap</label>
                    <p class="font-medium text-gray-800"><?= $profil->namaLengkap ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-map-marker-alt w-5"></i> Nama Pertanian</label>
                    <p class="font-medium text-gray-800"><?= $profil->namaPertanian ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-envelope w-5"></i> Email</label>
                    <p class="font-medium text-gray-800"><?= $profil->email ?></p>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-phone-alt w-5"></i> No. Telepon</label>
                    <p class="font-medium text-gray-800"><?= $profil->telepon ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="fas fa-map-pin w-5"></i> Alamat</label>
                    <p class="font-medium text-gray-800"><?= $profil->alamat ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-400 text-xs mb-1 flex items-center"><i class="far fa-id-card w-5"></i> Bio</label>
                    <p class="font-medium text-gray-800 leading-relaxed"><?= $profil->bio ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- STATS SECTION (Visual only, opacity 50% in Edit Mode) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 <?= $is_edit ? 'opacity-50 pointer-events-none select-none' : '' ?>">
    
    <!-- Card 1: Total Lahan -->
    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center">
        <h4 class="text-3xl font-bold text-green-500 mb-1">
            <?= isset($stats->total_lahan) ? $stats->total_lahan : 0 ?>
        </h4>
        <p class="text-gray-500 text-sm">Total Lahan</p>
    </div>

    <!-- Card 2: Total Luas -->
    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center">
        <h4 class="text-3xl font-bold text-blue-500 mb-1">
            <?php 
                // Format angka: 2 desimal jika ada koma, pemisah ribuan titik
                $luas = isset($stats->total_luas) ? $stats->total_luas : 0;
                echo number_format($luas, 1, ',', '.'); 
            ?>
        </h4>
        <p class="text-gray-500 text-sm">Total Luas (ha)</p>
    </div>

    <!-- Card 3: Siklus Selesai -->
    <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center">
        <h4 class="text-3xl font-bold text-orange-500 mb-1">
            <?= isset($stats->siklus_selesai) ? $stats->siklus_selesai : 0 ?>
        </h4>
        <p class="text-gray-500 text-sm">Siklus Selesai</p>
    </div>
</div>
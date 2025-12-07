<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Informasi & Bantuan</h2>
    <p class="text-gray-500 mt-1">Info cuaca, harga pasar, dan kontak bantuan</p>
</div>

<!-- SUB NAVIGATION (PHP Links) -->
<?php $sub = isset($_GET['sub']) ? $_GET['sub'] : 'cuaca'; ?>
<div class="w-full bg-gray-100 p-1 rounded-lg flex mb-8">
    <a href="?sub=cuaca" class="flex-1 py-2 text-center rounded-md text-sm font-medium transition-all <?= $sub == 'cuaca' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900' ?>">Cuaca & Rekomendasi</a>
    <a href="?sub=harga" class="flex-1 py-2 text-center rounded-md text-sm font-medium transition-all <?= $sub == 'harga' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900' ?>">Harga Pasar</a>
    <a href="?sub=kontak" class="flex-1 py-2 text-center rounded-md text-sm font-medium transition-all <?= $sub == 'kontak' ? 'shadow-sm bg-white text-gray-900' : 'text-gray-500 hover:text-gray-900' ?>">Kontak Bantuan</a>
</div>

<div class="animate-fade-in">
    <?php if($sub == 'cuaca'): ?>
        <div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8">
            <div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-cloud text-blue-500"></i> Prakiraan Cuaca 5 Hari</h3></div>
            <!-- Weather Content Static -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="border-2 border-blue-500 bg-blue-50 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-gray-800 mb-1">Hari Ini</p>
                    <i class="fas fa-sun text-yellow-400 text-2xl mb-2"></i>
                    <h4 class="text-xl font-bold text-gray-800">28°C</h4>
                </div>
                <!-- Loop Dummy Weather -->
                <?php for($i=0; $i<4; $i++): ?>
                <div class="border border-gray-100 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-gray-800 mb-1">Besok</p>
                    <i class="fas fa-cloud-sun text-gray-300 text-2xl mb-2"></i>
                    <h4 class="text-xl font-bold text-gray-800">27°C</h4>
                </div>
                <?php endfor; ?>
            </div>
        </div>

    <?php elseif($sub == 'harga'): ?>
        <div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8">
            <div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-dollar-sign text-green-500"></i> Harga Komoditas Pertanian</h3></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Contoh Loop Harga -->
                <?php 
                $harga = [
                    ['nama' => 'Padi Kering', 'harga' => 'Rp 6.500', 'tren' => 'naik'],
                    ['nama' => 'Jagung', 'harga' => 'Rp 4.800', 'tren' => 'naik'],
                ];
                foreach($harga as $h): ?>
                <div class="border border-gray-100 rounded-xl p-4 flex justify-between items-start hover:shadow-md transition">
                    <div><h4 class="font-bold text-gray-800 text-sm"><?= $h['nama'] ?></h4><span class="text-xl font-bold text-gray-900"><?= $h['harga'] ?></span></div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-600"><i class="fas fa-arrow-up mr-1"></i>Naik</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>
        <!-- KONTAK -->
        <div class="bg-white border border-gray-100 rounded-xl p-8 shadow-sm mb-8">
            <div class="mb-6"><h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-phone-alt text-green-500"></i> Kontak Bantuan Pertanian</h3></div>
            <div class="space-y-4 mb-8">
                <div class="border border-gray-100 rounded-xl p-6 flex flex-col md:flex-row gap-4 items-start md:items-center bg-white">
                    <div class="bg-green-50 w-12 h-12 rounded-lg flex items-center justify-center text-green-600 shrink-0"><i class="fas fa-phone"></i></div>
                    <div class="flex-1">
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded mb-2 inline-block">Dinas Pertanian</span>
                        <h4 class="font-bold text-gray-800">Dinas Pertanian Kab. Subang</h4>
                    </div>
                    <button class="bg-gray-900 text-white text-xs font-medium px-4 py-2.5 rounded-lg">0260-411234</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
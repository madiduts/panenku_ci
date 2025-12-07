<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
    <p class="text-gray-500 mt-1">Ringkasan kondisi lahan dan cuaca hari ini</p>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-leaf text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Total Lahan</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan) ? $total_lahan : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Total luas <?= isset($total_luas) ? $total_luas : '0' ?> ha</p>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-chart-line text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Lahan Aktif</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan_aktif) ? $total_lahan_aktif : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Sedang produktif</p>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-calendar-alt text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Siklus Tanam</p>
            <h3 class="text-lg font-bold text-gray-800">6 siklus</h3>
            <p class="text-[10px] text-gray-400">2 siap panen</p>
        </div>
    </div>
    <!-- Card 4 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500"><i class="fas fa-sun text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Suhu Hari Ini</p>
            <h3 class="text-lg font-bold text-gray-800">28°C</h3>
            <p class="text-[10px] text-gray-400">Cerah berawan</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- WEATHER WIDGET -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm h-fit">
        <div class="mb-6">
            <h3 class="text-gray-500 font-medium text-sm flex items-center gap-2"><i class="fas fa-cloud text-blue-400"></i> Cuaca Hari Ini</h3>
            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1 text-gray-300"></i> Lokasi Lahan Anda</p>
        </div>
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-5xl font-bold text-gray-800 tracking-tight">28°C</h2>
                <p class="text-gray-500 mt-1 text-sm">Cerah Berawan</p>
            </div>
            <div class="text-yellow-400 text-6xl"><i class="fas fa-sun"></i></div>
        </div>
        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4 mb-6">
            <div class="text-left">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Kelembaban</p>
                <p class="font-bold text-gray-700 text-lg">65% <span class="text-blue-500 text-xs"><i class="fas fa-tint"></i></span></p>
            </div>
            <div class="text-left border-l border-gray-100 pl-4">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Angin</p>
                <p class="font-bold text-gray-700 text-lg">12 <span class="text-sm font-normal text-gray-500">km/h</span></p>
            </div>
        </div>
        <h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Prakiraan 5 Hari</h4>
        <!-- Static loop simulation for weather -->
        <div class="flex justify-between text-center px-1">
            <?php 
            $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];
            $icons = ['fa-sun', 'fa-cloud-sun', 'fa-cloud-rain', 'fa-cloud-showers-heavy', 'fa-sun'];
            $colors = ['text-yellow-400', 'text-yellow-400', 'text-blue-400', 'text-blue-600', 'text-yellow-400'];
            $temps = [28, 29, 27, 26, 28];
            foreach($days as $idx => $day): ?>
                <div class="flex flex-col items-center space-y-2">
                    <span class="text-[10px] text-gray-400 font-medium"><?= $day ?></span>
                    <i class="fas <?= $icons[$idx] ?> <?= $colors[$idx] ?> text-sm"></i>
                    <span class="text-xs font-bold text-gray-700"><?= $temps[$idx] ?>°</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- LAHAN AKTIF LIST -->
    <div class="lg:col-span-2">
        <div class="mb-4">
            <h3 class="font-bold text-lg text-gray-800">Lahan Aktif</h3>
            <p class="text-sm text-gray-400">Status lahan yang sedang ditanami</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-1 shadow-sm">
            <div class="divide-y divide-gray-50">
                <?php if(isset($lahan_list) && !empty($lahan_list)): ?>
                    <?php foreach($lahan_list as $l): ?>
                        <div class="p-4 hover:bg-gray-50 transition rounded-xl">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-green-50 w-10 h-10 rounded-lg flex items-center justify-center text-green-600 shrink-0">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">Lahan <?= $l->id ?> • <?= $l->tanaman ?></h4>
                                        <p class="text-xs text-gray-400 mt-0.5"><?= $l->luas ?> • <?= $l->fase ?></p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded"><?= $l->progress ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: <?= $l->progress ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-gray-500 text-sm">Tidak ada data lahan aktif.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
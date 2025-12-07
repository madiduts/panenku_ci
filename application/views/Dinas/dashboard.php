<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Monitoring & Visualisasi</h2>
    <p class="text-gray-500 mt-1">Pantau statistik panen, sebaran lahan, dan sistem peringatan dini hama</p>
</div>

<!-- Top Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold mb-2">Total Lahan</p>
        <h3 class="text-3xl font-bold text-gray-800"><?= number_format($stats['totalLahan']) ?> Ha</h3>
        <p class="text-xs text-gray-400 mt-1">Across 5 villages</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold mb-2">Total Petani</p>
        <h3 class="text-3xl font-bold text-gray-800"><?= number_format($stats['totalPetani']) ?></h3>
        <p class="text-xs text-gray-400 mt-1">Registered farmers</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold mb-2">Produktivitas Rata-rata</p>
        <h3 class="text-3xl font-bold text-gray-800">5.2 ton/ha</h3>
        <p class="text-xs text-green-500 mt-1 font-medium"><i class="fas fa-arrow-up mr-1"></i> +8% dari bulan lalu</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold mb-2">Peringatan Aktif</p>
        <h3 class="text-3xl font-bold text-gray-800"><?= $stats['warningHama'] ?></h3>
        <p class="text-xs text-red-500 mt-1 font-medium">1 tinggi, 1 sedang, 1 rendah</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Line Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-1">Statistik Panen (Ton)</h3>
        <p class="text-xs text-gray-500 mb-6">Tren produksi 6 bulan terakhir</p>
        <div class="h-64 relative">
            <canvas id="harvestTrendsChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-1">Distribusi Komoditas</h3>
        <p class="text-xs text-gray-500 mb-6">Persentase luas tanam</p>
        <div class="h-48 relative flex justify-center items-center">
            <canvas id="commodityPieChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-gray-500">
            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Padi 45%</div>
            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>Jagung 30%</div>
            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>Kedelai 15%</div>
            <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>Lainnya 10%</div>
        </div>
    </div>
</div>

<!-- GIS Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-map-marker-alt mr-2"></i>Peta Sebaran Lahan (GIS)</h3>
    <p class="text-xs text-gray-500 mb-6">Distribusi lahan pertanian per desa</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Static loops for layout preservation (can be made dynamic) -->
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Makmur</h4>
                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
            </div>
            <div class="space-y-1 text-xs text-gray-600">
                <p>Luas: <span class="font-semibold">120 Ha</span></p>
                <p>Petani: <span class="font-semibold">45 orang</span></p>
                <p class="text-[10px] text-gray-400">Koordinat: -6.2088, 106.8456</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Sejahtera</h4>
                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
            </div>
            <div class="space-y-1 text-xs text-gray-600">
                <p>Luas: <span class="font-semibold">95 Ha</span></p>
                <p>Petani: <span class="font-semibold">38 orang</span></p>
                <p class="text-[10px] text-gray-400">Koordinat: -6.2188, 106.8556</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold text-gray-800 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Desa Subur</h4>
                <i class="fas fa-eye text-gray-400 text-xs cursor-pointer hover:text-green-600"></i>
            </div>
            <div class="space-y-1 text-xs text-gray-600">
                <p>Luas: <span class="font-semibold">150 Ha</span></p>
                <p>Petani: <span class="font-semibold">52 orang</span></p>
                <p class="text-[10px] text-gray-400">Koordinat: -6.1988, 106.8356</p>
            </div>
        </div>
    </div>

    <!-- Bar Chart: Village Comparison -->
    <div class="h-64 relative">
        <canvas id="villageStatsChart"></canvas>
    </div>
</div>

<!-- EWS Section (Dynamic Loop) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
     <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>Early Warning System - Hama & Penyakit</h3>
     <p class="text-xs text-gray-500 mb-6">Peringatan dini serangan hama pada tanaman</p>
     
     <div class="space-y-4">
        <?php foreach($ews as $alert): 
            $is_high = $alert->level === 'Bahaya' || $alert->level === 'Tinggi';
            $border_color = $is_high ? 'border-red-200' : 'border-orange-200';
            $icon_color = $is_high ? 'text-red-500' : 'text-orange-500';
            $badge_bg = $is_high ? 'bg-red-600' : 'bg-orange-200';
            $badge_text = $is_high ? 'text-white' : 'text-orange-800';
            $border_top = $is_high ? 'border-red-50' : 'border-orange-50';
        ?>
        <div class="border <?= $border_color ?> rounded-lg p-4 bg-white">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle <?= $icon_color ?>"></i>
                <span class="font-bold <?= $is_high ? 'text-red-600' : 'text-gray-800' ?> text-sm"><?= $alert->hama ?></span>
                <span class="<?= $badge_bg ?> <?= $badge_text ?> text-[10px] px-2 py-0.5 rounded font-bold uppercase"><?= $alert->level ?></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <p class="text-gray-500">Komoditas: <span class="font-semibold text-gray-800">Tanaman</span></p>
                    <p class="text-gray-500 mt-1">Lokasi: <span class="font-semibold text-gray-800"><?= $alert->lokasi ?></span></p>
                </div>
                <div>
                    <p class="text-gray-500">Luas Terdampak: <span class="font-semibold text-gray-800"><?= $alert->luas ?></span></p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3 pt-3 border-t <?= $border_top ?>">Tindakan segera diperlukan. Koordinasikan dengan petani.</p>
        </div>
        <?php endforeach; ?>
     </div>
</div>
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Monitoring & Visualisasi</h2>
        <p class="text-gray-500 mt-1">Pantau statistik panen, sebaran lahan, dan peringatan dini.</p>
    </div>
    <div class="text-right">
        <p class="text-xs text-gray-400">Terakhir diperbarui</p>
        <p class="text-sm font-bold text-gray-700"><?= date('d M Y H:i') ?></p>
    </div>
</div>

<!-- Top Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-map-marked-alt text-6xl text-blue-500"></i></div>
        <p class="text-xs text-gray-500 font-semibold mb-2">Total Lahan</p>
        <h3 class="text-3xl font-bold text-gray-800"><?= number_format($stats['totalLahan']) ?> Ha</h3>
        <p class="text-xs text-gray-400 mt-1">Across 5 villages</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-users text-6xl text-green-500"></i></div>
        <p class="text-xs text-gray-500 font-semibold mb-2">Total Petani</p>
        <h3 class="text-3xl font-bold text-gray-800"><?= number_format($stats['totalPetani']) ?></h3>
        <p class="text-xs text-gray-400 mt-1">Registered farmers</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-chart-line text-6xl text-purple-500"></i></div>
        <p class="text-xs text-gray-500 font-semibold mb-2">Produktivitas Rata-rata</p>
        <h3 class="text-3xl font-bold text-gray-800">5.2 ton/ha</h3>
        <p class="text-xs text-green-500 mt-1 font-medium"><i class="fas fa-arrow-up mr-1"></i> +8% trend</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-exclamation-circle text-6xl text-red-500"></i></div>
        <p class="text-xs text-gray-500 font-semibold mb-2">Peringatan Hama</p>
        <h3 class="text-3xl font-bold text-red-600"><?= $stats['warningHama'] ?></h3>
        <p class="text-xs text-red-500 mt-1 font-medium">Perlu penanganan</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Left Column: Charts -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Line Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-1">Statistik Panen (Ton)</h3>
            <p class="text-xs text-gray-500 mb-6">Tren produksi 6 bulan terakhir</p>
            <div class="h-64 relative">
                <canvas id="harvestTrendsChart"></canvas>
            </div>
        </div>

        <!-- EWS Section (Dynamic) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
             <div class="flex justify-between items-center mb-4">
                 <div>
                     <h3 class="font-bold text-gray-800"><i class="fas fa-biohazard text-red-500 mr-2"></i>Peringatan Dini Hama</h3>
                     <p class="text-xs text-gray-500">Laporan hama yang membutuhkan validasi atau tindakan</p>
                 </div>
                 <a href="<?= base_url('dinas/validasi?tab=hama') ?>" class="text-xs text-blue-600 font-bold hover:underline">Lihat Semua</a>
             </div>
             
             <div class="space-y-3">
                <?php if(empty($ews)): ?>
                    <div class="text-center py-6 text-gray-400 border border-dashed rounded-lg">
                        <i class="fas fa-shield-alt text-2xl mb-2 text-green-200"></i>
                        <p class="text-xs">Aman terkendali</p>
                    </div>
                <?php else: ?>
                    <?php foreach(array_slice($ews, 0, 3) as $alert): // Tampilkan max 3 
                        $is_danger = $alert->level === 'Bahaya';
                    ?>
                    <div class="flex items-start gap-3 p-3 rounded-lg border <?= $is_danger ? 'border-red-100 bg-red-50' : 'border-orange-100 bg-orange-50' ?>">
                        <div class="mt-1">
                            <i class="fas fa-bug <?= $is_danger ? 'text-red-500' : 'text-orange-500' ?>"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <h4 class="text-sm font-bold text-gray-800"><?= $alert->hama ?></h4>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase <?= $is_danger ? 'bg-red-200 text-red-800' : 'bg-orange-200 text-orange-800' ?>"><?= $alert->level ?></span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                Deteksi di <span class="font-semibold"><?= $alert->lokasi ?></span> pada tanaman <?= $alert->komoditas ?>.
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1"><i class="far fa-clock mr-1"></i> <?= $alert->waktu ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
             </div>
        </div>
    </div>

    <!-- Right Column: Notifications & Pie Chart -->
    <div class="space-y-6">
        
        <!-- WIDGET NOTIFIKASI (BARU) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-sm">
                    <i class="far fa-bell text-gray-500 mr-2"></i>Aktivitas Terbaru
                </h3>
                <?php if(isset($notif_unread) && $notif_unread > 0): ?>
                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold"><?= $notif_unread ?></span>
                <?php endif; ?>
            </div>
            <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                <?php if(empty($notifikasi_list)): ?>
                    <div class="p-8 text-center text-gray-400 text-xs">
                        Belum ada notifikasi baru.
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-50">
                        <?php foreach($notifikasi_list as $n): 
                            $bg_icon = 'bg-blue-50 text-blue-500';
                            $icon = 'fa-info';
                            if($n->tipe == 'warning') { $bg_icon = 'bg-yellow-50 text-yellow-600'; $icon = 'fa-exclamation'; }
                            if($n->tipe == 'danger')  { $bg_icon = 'bg-red-50 text-red-600'; $icon = 'fa-times'; }
                            if($n->tipe == 'success') { $bg_icon = 'bg-green-50 text-green-600'; $icon = 'fa-check'; }
                        ?>
                        <a href="<?= $n->link ?>" class="block p-4 hover:bg-gray-50 transition group">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full <?= $bg_icon ?> flex items-center justify-center shrink-0 text-xs">
                                    <i class="fas <?= $icon ?>"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800 group-hover:text-blue-600 transition"><?= $n->judul ?></p>
                                    <p class="text-[11px] text-gray-500 mt-0.5 leading-snug"><?= $n->pesan ?></p>
                                    <p class="text-[10px] text-gray-300 mt-2 text-right">
                                        <?= date('d M H:i', strtotime($n->created_at)) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="p-3 border-t border-gray-100 text-center">
                <a href="#" class="text-xs text-gray-500 hover:text-blue-600 font-medium">Lihat Semua Riwayat</a>
            </div>
        </div>

        <!-- Pie Chart (Moved Here) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-1 text-sm">Distribusi Komoditas</h3>
            <div class="h-40 relative flex justify-center items-center mt-4">
                <canvas id="commodityPieChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 text-[10px] text-gray-500">
                <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Padi 45%</div>
                <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>Jagung 30%</div>
                <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>Kedelai 15%</div>
                <div class="flex items-center"><span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>Lainnya 10%</div>
            </div>
        </div>

    </div>
</div>

<!-- GIS Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>Peta Sebaran Lahan</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 mt-4">
        <?php if(empty($sebaran_desa)): ?>
            <div class="col-span-3 text-center text-gray-400 py-4">Data sebaran belum tersedia.</div>
        <?php else: ?>
            <?php foreach(array_slice($sebaran_desa, 0, 3) as $desa): // Limit tampil ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition cursor-pointer">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> <?= $desa->lokasi_desa ?>
                    </h4>
                </div>
                <p class="text-xs text-gray-600 pl-4">Luas Total: <span class="font-bold"><?= $desa->total_luas ?> Ha</span></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Bar Chart -->
    <div class="h-48 relative">
        <canvas id="villageStatsChart"></canvas>
    </div>
</div>

<!-- EWS Section (Dynamic Loop) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
     <h3 class="font-bold text-gray-800 mb-1"><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>Early Warning System - Hama & Penyakit</h3>
     <p class="text-xs text-gray-500 mb-6">Peringatan dini serangan hama pada tanaman</p>
     
     <div class="space-y-4">
        <?php if(empty($ews)): ?>
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-check-circle text-4xl text-green-100 mb-2"></i>
                <p>Tidak ada peringatan hama aktif saat ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($ews as $alert): 
                $is_high = $alert->level === 'Bahaya' || $alert->level === 'Tinggi';
                $border_color = $is_high ? 'border-red-200' : 'border-orange-200';
                $icon_color = $is_high ? 'text-red-500' : 'text-orange-500';
                $badge_bg = $is_high ? 'bg-red-600' : 'bg-orange-200';
                $badge_text = $is_high ? 'text-white' : 'text-orange-800';
                $border_top = $is_high ? 'border-red-50' : 'border-orange-50';
            ?>
            <!-- OPEN CARD UTAMA -->
            <div class="border <?= $border_color ?> rounded-lg p-4 bg-white">
                
                <!-- Header Card -->
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle <?= $icon_color ?>"></i>
                    <span class="font-bold <?= $is_high ? 'text-red-600' : 'text-gray-800' ?> text-sm"><?= $alert->hama ?></span>
                    <span class="<?= $badge_bg ?> <?= $badge_text ?> text-[10px] px-2 py-0.5 rounded font-bold uppercase"><?= $alert->level ?></span>
                </div>

                <!-- Grid Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-gray-500">Komoditas: <span class="font-semibold text-gray-800"><?= $alert->komoditas ?></span></p>
                        <p class="text-gray-500 mt-1">Lokasi: <span class="font-semibold text-gray-800"><?= $alert->lokasi ?></span></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Luas Terdampak: <span class="font-semibold text-gray-800"><?= $alert->luas ?></span></p>
                        <p class="text-gray-400 mt-1 italic"><?= isset($alert->waktu) ? $alert->waktu : 'Baru saja' ?></p>
                    </div>
                </div> <!-- PENUTUP GRID (Tadi Anda hanya sampai sini) -->

                <!-- Footer Card -->
                <p class="text-xs text-gray-500 mt-3 pt-3 border-t <?= $border_top ?>">Tindakan segera diperlukan. Koordinasikan dengan petani.</p>
            
            </div> <!-- PENUTUP CARD UTAMA (Ini yang hilang sebelumnya!) -->
            
            <?php endforeach; ?>
        <?php endif; ?>
     </div>
</div>

<!-- SCRIPT UNTUK CHART -->
<!-- Pastikan Chart.js sudah diload di layout utama atau load via CDN di sini -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Ambil Data dari PHP (Serialization)
    // Kita encode array PHP menjadi JSON String agar bisa dibaca JS
    const rawData = <?= json_encode($sebaran_desa) ?>;

    // 2. Mapping Data (JS Array Manipulation)
    // Pisahkan nama desa (Labels) dan luas lahan (Data)
    // Jika rawData kosong, berikan default array kosong
    const labels = rawData ? rawData.map(item => item.lokasi_desa) : [];
    const dataLuas = rawData ? rawData.map(item => item.total_luas) : [];

    // 3. Render Chart
    const ctx = document.getElementById('villageStatsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar', // Tipe Chart
        data: {
            labels: labels, // Sumbu X (Nama Desa)
            datasets: [{
                label: 'Luas Lahan (Hektar)',
                data: dataLuas, // Sumbu Y (Nilai Luas)
                backgroundColor: 'rgba(34, 197, 94, 0.6)', // Tailwind Green-500
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6' // Tailwind Gray-100
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Sembunyikan legenda karena judul sudah jelas
                }
            }
        }
    });
});
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
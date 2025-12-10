<!-- HEADER AREA (Judul Kiri - Notifikasi Kanan) -->
<div class="flex justify-between items-start mb-8 relative">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
        <p class="text-gray-500 mt-1">Halo, <?= $user['name'] ?>! Cek kondisi lahanmu hari ini.</p>
    </div>

    <!-- AREA NOTIFIKASI (POJOK KANAN ATAS) -->
    <div class="relative">
        <!-- Tombol Lonceng -->
        <button onclick="toggleNotif()" class="relative p-2 rounded-full hover:bg-gray-100 transition focus:outline-none group">
            <i class="far fa-bell text-2xl text-gray-500 group-hover:text-green-600 transition"></i>
            
            <!-- Badge Merah -->
            <?php if(isset($notif_unread) && $notif_unread > 0): ?>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse">
                    <?= $notif_unread ?>
                </span>
            <?php endif; ?>
        </button>

        <!-- DROPDOWN NOTIFIKASI -->
        <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden transform origin-top-right transition-all">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm">Notifikasi</h3>
                <?php if(isset($notif_unread) && $notif_unread > 0): ?>
                    <span class="text-xs text-green-600 font-medium cursor-pointer hover:underline">Tandai dibaca</span>
                <?php endif; ?>
            </div>

            <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                <?php if(empty($notifikasi_list)): ?>
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-seedling text-2xl mb-2 text-green-200"></i>
                        <p class="text-xs">Belum ada kabar terbaru.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-50">
                        <?php foreach($notifikasi_list as $n): 
                            // Styling Icon Khusus Petani
                            $icon_bg = 'bg-blue-50 text-blue-600';
                            $icon = 'fa-info';
                            if($n->tipe == 'danger')  { $icon_bg = 'bg-red-50 text-red-600'; $icon = 'fa-exclamation-triangle'; } // Serangan Hama
                            if($n->tipe == 'warning') { $icon_bg = 'bg-yellow-50 text-yellow-600'; $icon = 'fa-clock'; } // Jadwal Pupuk
                            if($n->tipe == 'success') { $icon_bg = 'bg-green-50 text-green-600'; $icon = 'fa-check'; } // Validasi Diterima
                            
                            $unread_bg = ($n->is_read == 0) ? 'bg-green-50/30' : 'bg-white';
                        ?>
                        <a href="<?= $n->link ?>" class="block p-4 hover:bg-gray-50 transition <?= $unread_bg ?>">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full <?= $icon_bg ?> flex items-center justify-center shrink-0 text-xs">
                                    <i class="fas <?= $icon ?>"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800 leading-snug mb-0.5"><?= $n->judul ?></p>
                                    <p class="text-[11px] text-gray-500 leading-relaxed"><?= $n->pesan ?></p>
                                    <p class="text-[9px] text-gray-300 mt-1.5"><?= date('d M H:i', strtotime($n->created_at)) ?></p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="p-3 border-t border-gray-100 text-center bg-gray-50">
                <a href="#" class="text-xs text-gray-500 hover:text-green-600 font-medium">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS (GRID) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-leaf text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Total Lahan</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan) ? $total_lahan : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Total luas <?= isset($total_luas) ? $total_luas : '0' ?> ha</p>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-chart-line text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Lahan Aktif</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan_aktif) ? $total_lahan_aktif : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Sedang produktif</p>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-calendar-alt text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Siklus Tanam</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan_aktif) ? $total_lahan_aktif : 0 ?> siklus</h3>
            <p class="text-[10px] text-gray-400">Sedang berjalan</p>
        </div>
    </div>
    <!-- Card 4 (Weather Mini) -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500">
            <i id="card-weather-fallback" class="fas fa-sun text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Suhu Hari Ini</p>
            <h3 id="card-temp" class="text-lg font-bold text-gray-800">--°C</h3>
            <p id="card-desc" class="text-[10px] text-gray-400">Memuat...</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- LEFT COL: WEATHER -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50 blur-2xl"></div>
            <div class="mb-4 relative z-10">
                <h3 class="text-gray-500 font-medium text-sm flex items-center gap-2"><i class="fas fa-cloud text-blue-400"></i> Cuaca</h3>
                <p id="weather-loc" class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi...</p>
            </div>
            <div class="flex justify-between items-center mb-6 relative z-10">
                <div>
                    <h2 id="main-temp" class="text-4xl font-bold text-gray-800">--</h2>
                    <p id="main-desc" class="text-gray-500 mt-1 text-sm">Loading...</p>
                </div>
                <div class="text-yellow-400 text-5xl">
                    <i id="main-icon-fallback" class="fas fa-spinner fa-spin text-gray-300"></i>
                </div>
            </div>
            <div id="weather-tips" class="text-xs text-gray-600 italic bg-gray-50 p-3 rounded-lg border border-gray-100">
                Menunggu data...
            </div>
        </div>
    </div>

    <!-- RIGHT COL: LAHAN AKTIF -->
    <div class="lg:col-span-2">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Lahan Aktif</h3>
                <p class="text-sm text-gray-400">Monitoring perkembangan tanaman</p>
            </div>
            <a href="<?= base_url('petani/lahan') ?>" class="text-xs font-bold text-green-600 hover:text-green-800">Lihat Semua</a>
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
                                        <h4 class="font-bold text-gray-800 text-sm"><?= $l->id ?> • <?= $l->tanaman ?></h4>
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
                    <div class="p-10 text-center text-gray-500 text-sm">
                        <i class="fas fa-tree text-gray-300 text-3xl mb-2 block"></i>
                        Tidak ada lahan yang sedang ditanami.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// --- KONFIGURASI API STORMGLASS ---
    // Masukkan API Key Stormglass kamu di sini
    const SG_API_KEY = "c833a06e-d4f6-11f0-a8f4-0242ac130003-c833a10e-d4f6-11f0-a8f4-0242ac130003"; 

    // --- 1. SCRIPT TOGGLE NOTIFIKASI (BARU) ---
    function toggleNotif() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Menutup dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notif-dropdown');
        const button = event.target.closest('button[onclick="toggleNotif()"]');
        const insideDropdown = event.target.closest('#notif-dropdown');

        if (!button && !insideDropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });

    // 1. Fungsi Utama Mengambil Data (Versi Stormglass.io)
    function getWeatherData(lat, lon) {
        // Stormglass butuh parameter spesifik apa saja yang mau diambil
        // airTemperature = Suhu udara
        // humidity = Kelembaban
        // windSpeed = Kecepatan angin
        // precipitation = Curah hujan
        const params = 'airTemperature,humidity,windSpeed,precipitation';
        
        // Perhatikan: Stormglass pakai 'lng', bukan 'lon'
        const URL = `https://api.stormglass.io/v2/weather/point?lat=${lat}&lng=${lon}&params=${params}`;

        fetch(URL, {
            // [CRITICAL] Stormglass mewajibkan Auth lewat Header, bukan URL
            headers: {
                'Authorization': SG_API_KEY
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal akses Stormglass (Cek API Key/Quota)");
            return response.json();
        })
        .then(data => {
            // Stormglass mengembalikan data per jam ('hours'). Kita ambil data jam pertama (sekarang).
            const current = data.hours[0];

            // Stormglass punya banyak sumber data (sg, noaa, dwd). Kita pakai 'sg' (rata-rata terbaik).
            const temp = Math.round(current.airTemperature.sg); 
            const humid = Math.round(current.humidity.sg);
            const wind = Math.round(current.windSpeed.sg * 3.6); // m/s to km/h
            
            // Logika Hujan (Precipitation)
            const rain = current.precipitation.sg;
            
            // --- UPDATE UI DASHBOARD ---
            
            document.getElementById('main-temp').innerText = `${temp}°C`;
            document.getElementById('weather-humid').innerText = humid;
            document.getElementById('weather-wind').innerText = wind;
            
            // Update Deskripsi & Icon Manual (Karena Stormglass tidak kasih teks "Berawan")
            let desc = "Cerah";
            let iconClass = "fa-sun"; // Default FontAwesome Icon (Kita pakai icon font saja biar mudah)
            let iconColor = "text-yellow-500";

            // Logika Penentuan Cuaca Sederhana
            if (rain > 0.5) {
                desc = "Hujan";
                iconClass = "fa-cloud-showers-heavy";
                iconColor = "text-blue-500";
            } else if (humid > 80) {
                desc = "Berawan Tebal";
                iconClass = "fa-cloud";
                iconColor = "text-gray-400";
            } else if (humid > 60) {
                desc = "Cerah Berawan";
                iconClass = "fa-cloud-sun";
                iconColor = "text-orange-400";
            }

            document.getElementById('main-desc').innerText = desc;
            
            // Update Icon Utama (Pakai FontAwesome, hapus image tag OWM)
            const mainIconContainer = document.getElementById('main-icon-fallback').parentElement;
            mainIconContainer.innerHTML = `<i class="fas ${iconClass} ${iconColor} text-6xl"></i>`;

            // Update Card Kecil
            document.getElementById('card-temp').innerText = `${temp}°C`;
            document.getElementById('card-desc').innerText = desc;
            const cardIconContainer = document.getElementById('card-weather-fallback').parentElement;
            cardIconContainer.innerHTML = `<i class="fas ${iconClass} ${iconColor} text-2xl"></i>`;

            // Update Lokasi (Stormglass tidak mengembalikan nama kota, jadi kita pakai koordinat atau default)
            // Kecuali kita pakai Reverse Geocoding API lain (terlalu rumit untuk sekarang)
            document.getElementById('weather-loc').innerHTML = `<i class="fas fa-map-marker-alt mr-1 text-red-400"></i> ${lat.toFixed(2)}, ${lon.toFixed(2)}`;

            // Tips Pertanian
            let tips = "✅ Cuaca kondusif untuk aktivitas lahan.";
            if (rain > 0) tips = "🌧️ Terdeteksi hujan. Tunda pemupukan.";
            if (temp > 32) tips = "☀️ Suhu panas ekstrem. Cek irigasi.";
            document.getElementById('weather-tips').innerText = tips;
        })
        .catch(error => {
            console.error("Stormglass Error:", error);
            document.getElementById('main-desc').innerText = "Gagal memuat (Limit API Habis?)";
        });
        
        console.log("Fetching Weather for", lat, lon);
    }
</script>
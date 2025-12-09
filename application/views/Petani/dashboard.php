<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
    <p class="text-gray-500 mt-1">Ringkasan kondisi lahan dan cuaca hari ini</p>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: Total Lahan -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600"><i class="fas fa-leaf text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Total Lahan</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan) ? $total_lahan : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Total luas <?= isset($total_luas) ? $total_luas : '0' ?> ha</p>
        </div>
    </div>
    <!-- Card 2: Lahan Aktif -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="fas fa-chart-line text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Lahan Aktif</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan_aktif) ? $total_lahan_aktif : '0' ?> lahan</h3>
            <p class="text-[10px] text-gray-400">Sedang produktif</p>
        </div>
    </div>
    <!-- Card 3: Siklus Tanam -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600"><i class="fas fa-calendar-alt text-lg"></i></div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Siklus Tanam</p>
            <h3 class="text-lg font-bold text-gray-800"><?= isset($total_lahan_aktif) ? $total_lahan_aktif : 0 ?> siklus</h3>
            <p class="text-[10px] text-gray-400">Sedang berjalan</p>
        </div>
    </div>
    
    <!-- Card 4: Suhu (API Connected) -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex items-center space-x-4">
        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500">
            <!-- Icon Cuaca Kecil -->
            <img id="card-weather-icon" src="" class="w-8 h-8 hidden" alt="icon">
            <i id="card-weather-fallback" class="fas fa-sun text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium">Suhu Hari Ini</p>
            <!-- ID target JS: card-temp -->
            <h3 id="card-temp" class="text-lg font-bold text-gray-800">--°C</h3>
            <p id="card-desc" class="text-[10px] text-gray-400">Memuat...</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- WEATHER WIDGET (API Connected) -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm h-fit relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50 blur-2xl"></div>
        
        <div class="mb-6 relative z-10">
            <h3 class="text-gray-500 font-medium text-sm flex items-center gap-2"><i class="fas fa-cloud text-blue-400"></i> Cuaca Real-time</h3>
            <p id="weather-loc" class="text-xs text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1 text-gray-300"></i> Mencari Lokasi Anda...</p>
        </div>
        
        <div class="flex justify-between items-center mb-8 relative z-10">
            <div>
                <!-- ID target JS: main-temp & main-desc -->
                <h2 id="main-temp" class="text-5xl font-bold text-gray-800 tracking-tight">--</h2>
                <p id="main-desc" class="text-gray-500 mt-1 text-sm capitalize">Sedang memuat data...</p>
            </div>
            <!-- Weather Icon Besar -->
            <div class="text-yellow-400 text-6xl">
                <img id="main-icon" src="" class="w-20 h-20 hidden filter drop-shadow-md" alt="Weather">
                <i id="main-icon-fallback" class="fas fa-spinner fa-spin text-gray-300"></i>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4 mb-6 relative z-10">
            <div class="text-left">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Kelembaban</p>
                <p class="font-bold text-gray-700 text-lg">
                    <span id="weather-humid">--</span>% 
                    <span class="text-blue-500 text-xs"><i class="fas fa-tint"></i></span>
                </p>
            </div>
            <div class="text-left border-l border-gray-100 pl-4">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Angin</p>
                <p class="font-bold text-gray-700 text-lg">
                    <span id="weather-wind">--</span> <span class="text-sm font-normal text-gray-500">km/h</span>
                </p>
            </div>
        </div>
        
        <h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Tips Pertanian</h4>
        <div id="weather-tips" class="text-xs text-gray-600 italic bg-gray-50 p-3 rounded-lg border border-gray-100">
            Menunggu data cuaca untuk memberikan rekomendasi...
        </div>
    </div>

    <!-- LAHAN AKTIF LIST -->
    <div class="lg:col-span-2">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Lahan Aktif</h3>
                <p class="text-sm text-gray-400">Status lahan yang sedang ditanami</p>
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
                    <div class="p-8 text-center text-gray-500 text-sm">
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
    }
</script>

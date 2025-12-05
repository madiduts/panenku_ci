<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
        <!-- Tombol Tambahan (Opsional) -->
        <a href="<?= base_url('petani/lahan/tambah') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Lahan Baru
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Card 1: Total Lahan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Lahan</div>
                            <!-- Menggunakan Variabel PHP dari Controller -->
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $card_total_lahan ?> Lahan</div>
                            <small class="text-gray-500">Total Luas <?= $card_luas_total ?> ha</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-leaf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Lahan Aktif -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Lahan Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $card_lahan_aktif ?> Lahan</div>
                            <small class="text-gray-500"><?= $card_lahan_istirahat ?> Lahan istirahat</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Siklus Tanam -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Siklus Tanam</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $card_siklus_total ?> Siklus</div>
                            <small class="text-gray-500"><?= $card_siap_panen ?> Siap Panen</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Cuaca -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Suhu Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $cuaca_suhu ?>°C</div>
                            <small class="text-gray-500"><?= $cuaca_desc ?></small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sun fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row 2 -->
    <div class="row">

        <!-- Widget Cuaca (Kiri) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Cuaca Hari Ini</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-cloud-sun fa-4x text-gray-300 mb-3"></i>
                    <h1 class="display-4 font-weight-bold"><?= $cuaca_suhu ?>°C</h1>
                    <p class="mb-4">Lokasi Lahan Anda</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col">
                            <span class="small text-gray-500">Kelembaban</span><br>
                            <span class="font-weight-bold">65%</span>
                        </div>
                        <div class="col">
                            <span class="small text-gray-500">Angin</span><br>
                            <span class="font-weight-bold">12 km/h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Progress Lahan (Kanan) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Lahan Aktif</h6>
                </div>
                <div class="card-body">
                    
                    <?php if(empty($lahan_progress)) : ?>
                        <div class="text-center py-3">
                            <p class="text-gray-500">Belum ada lahan aktif.</p>
                        </div>
                    <?php else : ?>
                        
                        <!-- LOOPING DATA DARI CONTROLLER -->
                        <?php foreach($lahan_progress as $lahan) : ?>
                            <h4 class="small font-weight-bold">
                                <?= $lahan['nama'] ?> (<?= $lahan['komoditas'] ?>) 
                                <span class="float-right"><?= $lahan['persen'] ?>%</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar <?= $lahan['color'] ?>" role="progressbar" style="width: <?= $lahan['persen'] ?>%"></div>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

</div>
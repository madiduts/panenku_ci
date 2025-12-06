<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Panenku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .register-card { width: 100%; max-width: 450px; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; }
        .brand-logo { color: #198754; font-weight: bold; font-size: 24px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="brand-logo">🌾 Daftar Panenku</div>
        
        <h6 class="text-center text-muted mb-4">Bergabung bersama petani cerdas lainnya</h6>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process_register') ?>" method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Tanpa spasi" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                <div class="form-text">Minimal 6 karakter.</div>
            </div>

            <!-- Hidden Input: Role otomatis Petani -->
            <input type="hidden" name="role" value="petani">

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">Buat Akun Petani</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">Sudah punya akun?</small>
            <a href="<?= base_url('auth') ?>" class="text-success fw-bold text-decoration-none">Login Disini</a>
        </div>
    </div>

</body>
</html>
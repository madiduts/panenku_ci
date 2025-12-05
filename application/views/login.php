<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panenku</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; }
        .brand-logo { color: #28a745; font-weight: bold; font-size: 24px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">🌾 Panenku</div>
        
        <h5 class="text-center mb-4">Masuk Sistem</h5>

        <!-- Alert Sukses (dari Register) -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <!-- Alert Error -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process_login') ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Login</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">Belum punya akun?</small><br>
            <a href="<?= base_url('auth/register') ?>" class="text-success fw-bold text-decoration-none">Daftar Sebagai Petani</a>
        </div>
    </div>

</body>
</html>
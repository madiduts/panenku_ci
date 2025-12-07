<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Panenku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { brand: { green: '#16a34a', dark: '#14532d' } }
                }
            }
        }
    </script>
</head>
<body class="bg-white h-screen overflow-hidden">

    <div class="flex w-full h-full">
        <!-- KIRI: GAMBAR -->
        <div class="hidden lg:flex w-1/2 relative bg-brand-dark">
            <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=1769&auto=format&fit=crop" 
                 class="absolute inset-0 h-full w-full object-cover opacity-80" alt="Ladang">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 via-brand-dark/30 to-transparent"></div>
            <div class="absolute top-8 left-8 flex items-center gap-3 z-10">
                <div class="bg-white/20 backdrop-blur p-2 rounded-lg"><i class="fas fa-leaf text-white text-xl"></i></div>
                <span class="text-white font-bold text-xl">Panenku</span>
            </div>
            <div class="absolute bottom-0 left-0 p-12 z-10 w-full">
                <h1 class="text-4xl font-bold text-white mb-4 leading-tight">Bergabung Bersama<br>Komunitas Petani</h1>
                <p class="text-green-50 text-sm leading-relaxed max-w-md">Daftarkan diri Anda untuk mulai mengelola lahan pertanian dengan lebih efisien.</p>
            </div>
        </div>

        <!-- KANAN: FORM REGISTER -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-24 bg-white h-full overflow-y-auto">
            <div class="w-full max-w-sm mx-auto my-10"> <!-- Tambah my-10 biar aman saat scroll -->
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                <p class="text-gray-500 mb-8 text-sm">Isi data diri Anda untuk mendaftar.</p>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="mb-6 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100 flex items-center gap-2">
                        <i class="fas fa-circle-exclamation"></i> <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/process_register') ?>" method="POST" class="space-y-4">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="full_name" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition placeholder-gray-400"
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition placeholder-gray-400"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Nomor HP (TAMBAHAN PENTING) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="phone_number" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition placeholder-gray-400"
                            placeholder="081234567890">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="6"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition placeholder-gray-400"
                            placeholder="Minimal 6 karakter">
                    </div>

                    <button type="submit" class="w-full py-3 bg-brand-green text-white rounded-lg font-bold text-sm hover:bg-green-700 transition shadow-md shadow-green-100">Daftar Sekarang</button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">Sudah punya akun? <a href="<?= base_url('auth') ?>" class="font-bold text-brand-green hover:underline">Login Disini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
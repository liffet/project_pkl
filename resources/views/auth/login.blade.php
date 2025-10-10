<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row min-vh-100 align-items-center">

            <!-- KIRI (Card Login) -->
            <div class="col-md-5 d-flex justify-content-center align-items-center">
                <div class="card shadow-lg border-0 rounded-4 w-75" style="height: 90vh;">
                    <div class="card-body d-flex flex-column justify-content-center p-5">
                        <h3 class="text-center mb-4 fw-bold text-success">Login</h3>

                        <form method="POST" action="{{ route('login.web') }}" class="flex-grow-0">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control p-3" placeholder="Masukkan email" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control p-3" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">Login</button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Belum punya akun? 
                            <a href="/register" class="text-success fw-semibold text-decoration-none">Register</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- KANAN (Teks Sambutan) -->
            <div class="col-md-7 d-flex flex-column justify-content-center ps-5">
                <h1 class="fw-bold text-success mb-3">Selamat Datang!</h1>
                <p class="text-muted fs-5 mb-4">
                    Silakan login untuk mengakses sistem informasi sarana dan prasarana sekolah.
                </p>
                <ul class="text-muted fs-6">
                    <li>📦 Kelola data barang & kategori</li>
                    <li>🔁 Peminjaman & pengembalian mudah</li>
                    <li>📊 Laporan cepat & efisien</li>
                </ul>
            </div>
            
        </div>
    </div>
    
</body>
</html>

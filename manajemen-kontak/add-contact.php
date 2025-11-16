<?php
session_start();

$errors = [];
$data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi Nama
    if (empty($_POST["nama"])) {
        $errors[] = "Nama harus diisi";
    } else {
        $data['nama'] = trim($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
            $errors[] = "Nama hanya boleh mengandung huruf dan spasi";
        }
    }

    // Validasi Email
    if (empty($_POST["email"])) {
        $errors[] = "Email harus diisi";
    } else {
        $data['email'] = trim($_POST["email"]);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
    }

    // Validasi Telepon
    if (empty($_POST["telepon"])) {
        $errors[] = "Telepon harus diisi";
    } else {
        $data['telepon'] = trim($_POST["telepon"]);
        if (!preg_match("/^[0-9+\-\s()]+$/", $data['telepon'])) {
            $errors[] = "Format telepon tidak valid";
        } else {
            // Hapus karakter non-digit untuk cek panjang
            $clean_phone = preg_replace("/[^0-9]/", "", $data['telepon']);
            if (strlen($clean_phone) < 10) {
                $errors[] = "Nomor telepon minimal 10 digit";
            } elseif (strlen($clean_phone) > 13) {
                $errors[] = "Nomor telepon maksimal 13 digit";
            }
        }
    }

    // Validasi Alamat
    if (empty($_POST["alamat"])) {
        $errors[] = "Alamat harus diisi";
    } else {
        $data['alamat'] = trim($_POST["alamat"]);
    }

    // Jika tidak ada error, simpan kontak
    if (empty($errors)) {
        if (!isset($_SESSION['contacts'])) {
            $_SESSION['contacts'] = [];
        }
        
        $id = uniqid();
        $_SESSION['contacts'][$id] = $data;
        $_SESSION['message'] = "Kontak berhasil ditambahkan!";
        $_SESSION['message_type'] = "success";
        
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Header Banner -->
    <div class="header-banner bg-mocha text-vanilla py-3">
        <div class="container position-relative">
            <a href="index.php" class="text-vanilla text-decoration-none position-absolute start-0" style="top: 50%; transform: translateY(-50%);">
                <i class="bi bi-arrow-left-circle fs-4"></i>
            </a>
            <h2 class="mb-0 text-center fs-1">
                Sistem Manajemen Kontak
            </h2>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-mocha text-vanilla py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus-fill fs-4 me-3"></i>
                            <div>
                                <h4 class="mb-1">Tambah Kontak Baru</h4>
                                <small class="text-vanilla-light">Isi formulir di bawah untuk menambahkan kontak</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-vanilla-light p-4">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <h5 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Error Validasi
                                </h5>
                                <hr>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label text-mocha">
                                    <i class="bi bi-person"></i> Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama" 
                                       name="nama" 
                                       placeholder="Masukkan nama lengkap"
                                       value="<?php echo isset($data['nama']) ? htmlspecialchars($data['nama']) : ''; ?>">
                                <small class="form-text text-muted">Hanya huruf dan spasi</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label text-mocha">
                                    <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="name@example.com"
                                       value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="telepon" class="form-label text-mocha">
                                    <i class="bi bi-telephone"></i> Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="telepon" 
                                       name="telepon" 
                                       placeholder="08123456789"
                                       minlength="10"
                                       maxlength="13"
                                       value="<?php echo isset($data['telepon']) ? htmlspecialchars($data['telepon']) : ''; ?>">
                                <small class="form-text text-muted">Minimal 10 digit, maksimal 13 digit</small>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label text-mocha">
                                    <i class="bi bi-geo-alt"></i> Alamat <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3" 
                                          placeholder="Masukkan alamat lengkap"><?php echo isset($data['alamat']) ? htmlspecialchars($data['alamat']) : ''; ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-mocha">
                                    <i class="bi bi-save"></i> Tambah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-mocha text-vanilla text-center py-3 mt-auto">
        <p class="mb-0">&copy; 2025 Sistem Manajemen Kontak | Praktikum Pemrograman Web</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
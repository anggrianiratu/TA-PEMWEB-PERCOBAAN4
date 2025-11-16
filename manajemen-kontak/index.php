<?php
session_start();

// Inisialisasi array kontak di session jika belum ada
if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}

// Handle hapus kontak
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (isset($_SESSION['contacts'][$id])) {
        unset($_SESSION['contacts'][$id]);
        $_SESSION['message'] = "Kontak berhasil dihapus!";
        $_SESSION['message_type'] = "success";
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    
    <!-- Header Banner -->
    <div class="header-banner bg-mocha text-vanilla py-3">
        <div class="container">
            <h2 class="mb-0 text-center fs-1">Sistem Manajemen Kontak</h2>
        </div>
    </div>

    <div class="container my-4">

        <!-- Alert -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Section Header -->
        <div class="section-header bg-mocha text-vanilla p-3 mb-3 rounded">
            <div class="row align-items-center">
                <div class="col-md-8">

                    <!-- Diperbaiki: Icon + Teks sejajar -->
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text fs-3"></i>
                        <span>Daftar Kontak</span>
                    </h3>

                </div>
                <div class="col-md-4 text-end">

                    <!-- Badge baru dengan border -->
                    <span class="badge badge-mocha-count fs-6">
                        <?php echo count($_SESSION['contacts']); ?> Kontak
                    </span>

                </div>
            </div>
        </div>

        <!-- Jika kosong -->
        <?php if (empty($_SESSION['contacts'])): ?>

            <div class="empty-state text-center py-5">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="text-mocha mt-4">Belum ada kontak tersimpan</h4>
                <p class="text-muted">Klik tombol "Tambah Kontak" untuk mulai menambahkan kontak.</p>
                <a href="add-contact.php" class="btn btn-mocha mt-3">
                    <i class="bi bi-plus-circle"></i> Tambah Kontak Pertama
                </a>
            </div>

        <?php else: ?>

            <!-- Table -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 modern-table">
                            <thead class="table-mocha">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="20%"><i class="bi bi-person"></i> Nama</th>
                                    <th width="20%"><i class="bi bi-envelope"></i> Email</th>
                                    <th width="15%"><i class="bi bi-telephone"></i> Telepon</th>
                                    <th width="25%"><i class="bi bi-geo-alt"></i> Alamat</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($_SESSION['contacts'] as $id => $contact): 
                                ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-mocha"><?php echo $no++; ?></span>
                                    </td>

                                    <td class="align-middle"><strong><?php echo htmlspecialchars($contact['nama']); ?></strong></td>

                                    <td class="align-middle"><?php echo htmlspecialchars($contact['email']); ?></td>

                                    <td class="align-middle"><?php echo htmlspecialchars($contact['telepon']); ?></td>

                                    <td class="align-middle"><?php echo htmlspecialchars($contact['alamat']); ?></td>

                                    <td class="text-center align-middle">
                                        <a href="edit-contact.php?id=<?php echo $id; ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?delete=<?php echo $id; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus kontak <?php echo htmlspecialchars($contact['nama']); ?>?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-vanilla-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Menampilkan <strong><?php echo count($_SESSION['contacts']); ?></strong> kontak
                        </span>

                        <a href="add-contact.php" class="btn btn-sm btn-mocha">
                            <i class="bi bi-plus-circle"></i> Tambah Lagi
                        </a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <footer class="bg-mocha text-vanilla text-center py-3 mt-auto">
        <p class="mb-0">&copy; 2025 Sistem Manajemen Kontak | Praktikum Pemrograman Web</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

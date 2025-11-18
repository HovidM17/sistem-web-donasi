<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['admin'])) header('Location: login.php');

if ($_POST) {
    $id_kategori = $_POST['id_kategori'] ?? 0;
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];
    
    if (isset($_POST['add'])) {
        mysqli_query($conn, "INSERT INTO kategori VALUES (NULL, '$nama_kategori', '$deskripsi')");
    } else {
        mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama_kategori', deskripsi='$deskripsi' WHERE id_kategori=$id_kategori");
    }
    header('Location: kategori.php?success=1');
    exit;
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori=".$_GET['delete']);
    header('Location: kategori.php?success=1');
    exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Kita Peduli</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-hand-holding-heart me-2"></i>Kita Peduli - Admin
            </a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Halo, <?php echo $_SESSION['admin']['nama_lengkap']; ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar-green p-3">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="kampanye.php" class="nav-link">
                                <i class="fas fa-hand-holding-heart me-2"></i>Kampanye
                            </a>
                        </li>
                        <li>
                            <a href="kategori.php" class="nav-link active">
                                <i class="fas fa-tags me-2"></i>Kategori
                            </a>
                        </li>
                        <li>
                            <a href="donasi.php" class="nav-link">
                                <i class="fas fa-donate me-2"></i>Donasi
                            </a>
                        </li>
                        <li>
                            <a href="pencairan.php" class="nav-link">
                                <i class="fas fa-money-bill-wave me-2"></i>Pencairan Dana
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 px-4 py-4 dashboard-bg">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3">Kelola Kategori</h2>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#formModal">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Data berhasil disimpan!</div>
                <?php endif; ?>

                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['nama_kategori']; ?></h5>
                                <p class="card-text text-muted"><?php echo $row['deskripsi']; ?></p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-warning" onclick="editKategori(<?php echo $row['id_kategori']; ?>, '<?php echo $row['nama_kategori']; ?>', '<?php echo $row['deskripsi']; ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="kategori.php?delete=<?php echo $row['id_kategori']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Form (1 modal untuk tambah & edit) -->
    <div class="modal fade" id="formModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_kategori" id="id_kategori">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="add" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editKategori(id, nama, deskripsi) {
        document.getElementById('modalTitle').textContent = 'Edit Kategori';
        document.getElementById('id_kategori').value = id;
        document.getElementById('nama_kategori').value = nama;
        document.getElementById('deskripsi').value = deskripsi;
        document.querySelector('button[name="add"]').name = 'edit';
        new bootstrap.Modal(document.getElementById('formModal')).show();
    }
    </script>
</body>
</html>
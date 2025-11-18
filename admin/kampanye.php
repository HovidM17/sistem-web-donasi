<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['admin'])) header('Location: login.php');

if ($_POST) {
    $id_kampanye = $_POST['id_kampanye'] ?? 0;
    $id_kategori = $_POST['id_kategori'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $target_dana = $_POST['target_dana'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_akhir = $_POST['tanggal_akhir'];
    $status = $_POST['status_kampanye'];
    
    if (isset($_POST['add'])) {
        mysqli_query($conn, "INSERT INTO kampanye VALUES (NULL, '$id_kategori', '$judul', '$deskripsi', '$target_dana', 0, '$tanggal_mulai', '$tanggal_akhir', NULL, '$status')");
    } else {
        mysqli_query($conn, "UPDATE kampanye SET id_kategori='$id_kategori', judul='$judul', deskripsi='$deskripsi', target_dana='$target_dana', tanggal_mulai='$tanggal_mulai', tanggal_akhir='$tanggal_akhir', status_kampanye='$status' WHERE id_kampanye=$id_kampanye");
    }
    header('Location: kampanye.php?success=1');
    exit;
}

if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM kampanye WHERE id_kampanye=".$_GET['delete']);
    header('Location: kampanye.php?success=1');
    exit;
}

$kampanye = mysqli_query($conn, "SELECT k.*, kat.nama_kategori FROM kampanye k JOIN kategori kat ON k.id_kategori = kat.id_kategori ORDER BY k.judul ASC");
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kampanye - Kita Peduli</title>
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
                            <a href="kampanye.php" class="nav-link active">
                                <i class="fas fa-hand-holding-heart me-2"></i>Kampanye
                            </a>
                        </li>
                        <li>
                            <a href="kategori.php" class="nav-link">
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
                    <h2 class="h3">Kelola Kampanye</h2>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#formModal">
                        <i class="fas fa-plus me-2"></i>Tambah Kampanye
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Data berhasil disimpan!</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Target Dana</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($kampanye)): 
                                        $progress = ($row['dana_terkumpul'] / $row['target_dana']) * 100;
                                        $status_color = [
                                            'Aktif' => 'success',
                                            'Selesai' => 'primary', 
                                            'Dihentikan' => 'danger'
                                        ][$row['status_kampanye']];
                                    ?>
                                    <tr>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td><?php echo $row['nama_kategori']; ?></td>
                                        <td>Rp <?php echo number_format($row['target_dana'], 0, ',', '.'); ?></td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                            <small>Rp <?php echo number_format($row['dana_terkumpul'], 0, ',', '.'); ?></small>
                                        </td>
                                        <td><span class="badge bg-<?php echo $status_color; ?>"><?php echo $row['status_kampanye']; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editKampanye(<?php echo $row['id_kampanye']; ?>, '<?php echo $row['judul']; ?>', '<?php echo $row['id_kategori']; ?>', '<?php echo $row['deskripsi']; ?>', '<?php echo $row['target_dana']; ?>', '<?php echo $row['status_kampanye']; ?>', '<?php echo $row['tanggal_mulai']; ?>', '<?php echo $row['tanggal_akhir']; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="kampanye.php?delete=<?php echo $row['id_kampanye']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Form (1 modal untuk tambah & edit) -->
    <div class="modal fade" id="formModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kampanye Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_kampanye" id="id_kampanye">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" id="judul" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="id_kategori" id="id_kategori" required>
                                    <?php mysqli_data_seek($kategori, 0); ?>
                                    <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                                    <option value="<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Target Dana</label>
                                <input type="number" class="form-control" name="target_dana" id="target_dana" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status_kampanye" id="status_kampanye" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dihentikan">Dihentikan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" required>
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
    function editKampanye(id, judul, kategori, deskripsi, target, status, mulai, akhir) {
        document.getElementById('modalTitle').textContent = 'Edit Kampanye';
        document.getElementById('id_kampanye').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('id_kategori').value = kategori;
        document.getElementById('deskripsi').value = deskripsi;
        document.getElementById('target_dana').value = target;
        document.getElementById('status_kampanye').value = status;
        document.getElementById('tanggal_mulai').value = mulai;
        document.getElementById('tanggal_akhir').value = akhir;
        document.querySelector('button[name="add"]').name = 'edit';
        new bootstrap.Modal(document.getElementById('formModal')).show();
    }
    </script>
</body>
</html>

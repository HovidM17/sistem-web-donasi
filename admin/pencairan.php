<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['admin'])) header('Location: login.php');

if ($_POST) {
    if (isset($_POST['add'])) {
        $id_kampanye = $_POST['id_kampanye'];
        $jumlah_cair = $_POST['jumlah_cair'];
        $tujuan = $_POST['tujuan'];
        
        // Cek dana kampanye
        $kampanye = mysqli_fetch_assoc(mysqli_query($conn, "SELECT dana_terkumpul FROM kampanye WHERE id_kampanye=$id_kampanye"));
        
        if ($kampanye['dana_terkumpul'] >= $jumlah_cair) {
            mysqli_query($conn, "INSERT INTO pencairan_dana (id_kampanye, jumlah_cair, tujuan) VALUES ($id_kampanye, $jumlah_cair, '$tujuan')");
            mysqli_query($conn, "UPDATE kampanye SET dana_terkumpul=dana_terkumpul-$jumlah_cair WHERE id_kampanye=$id_kampanye");
            header('Location: pencairan.php?success=1');
            exit;
        } else {
            $error = "Dana terkumpul tidak mencukupi!";
        }
    }
}

// List pencairan kampanye 
$pencairan = mysqli_query($conn, "SELECT p.*, k.judul FROM pencairan_dana p JOIN kampanye k ON p.id_kampanye=k.id_kampanye ORDER BY p.tanggal_cair DESC");

// Listampanye aktif
$kampanye = mysqli_query($conn, "SELECT * FROM kampanye WHERE status_kampanye='Aktif'");

// Statistik
$total_pencairan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_cair) as total FROM pencairan_dana"))['total'];
$jumlah_pencairan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pencairan_dana"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencairan Dana - Kita Peduli</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-success">
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
                            <a href="pencairan.php" class="nav-link active">
                                <i class="fas fa-money-bill-wave me-2"></i>Pencairan Dana
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 px-4 py-4 dashboard-bg">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3">Pencairan Dana</h2>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i>Tambah Pencairan
                    </button>
                </div>

                <!-- Statistics Sederhana -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>Total Dana Dicairkan</h5>
                                <h4>Rp <?php echo number_format($total_pencairan ?: 0, 0, ',', '.'); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>Jumlah Pencairan</h5>
                                <h4><?php echo $jumlah_pencairan; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Pencairan berhasil dicatat!</div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kampanye</th>
                                        <th>Jumlah Cair</th>
                                        <th>Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($pencairan)): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_cair'])); ?></td>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td>Rp <?php echo number_format($row['jumlah_cair'], 0, ',', '.'); ?></td>
                                        <td><?php echo $row['tujuan']; ?></td>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pencairan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kampanye</label>
                            <select class="form-select" name="id_kampanye" required>
                                <option value="">Pilih Kampanye</option>
                                <?php while ($kamp = mysqli_fetch_assoc($kampanye)): ?>
                                <option value="<?php echo $kamp['id_kampanye']; ?>">
                                    <?php echo $kamp['judul']; ?> (Rp <?php echo number_format($kamp['dana_terkumpul'], 0, ',', '.'); ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Cair (Rp)</label>
                            <input type="number" class="form-control" name="jumlah_cair" required min="1000">
                        </div>
                        <div class="mb-3">
                            <label>Tujuan Pencairan</label>
                            <textarea class="form-control" name="tujuan" rows="2" required></textarea>
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
</body>
</html>

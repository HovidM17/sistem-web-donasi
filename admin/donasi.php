<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['admin'])) header('Location: login.php');

// Update status donasi
if (isset($_POST['update_status'])) {
    $id_donasi = $_POST['id_donasi'];
    $status = $_POST['status_pembayaran'];
    
    mysqli_query($conn, "UPDATE donasi SET status_pembayaran='$status' WHERE id_donasi=$id_donasi");
    
    if ($status == 'Berhasil') {
        $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM donasi WHERE id_donasi=$id_donasi"));
        mysqli_query($conn, "UPDATE kampanye SET dana_terkumpul=dana_terkumpul+{$d['jumlah_donasi']} WHERE id_kampanye={$d['id_kampanye']}");
    }
    
    header('Location: donasi.php?success=1');
    exit;
}

// Ambil data donasi
$donasi = mysqli_query($conn, "SELECT d.*, k.judul FROM donasi d JOIN kampanye k ON d.id_kampanye=k.id_kampanye ORDER BY d.tanggal_donasi DESC");

// Statistik
$total_donasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_donasi) as total FROM donasi WHERE status_pembayaran='Berhasil'"))['total'];
$donasi_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM donasi WHERE status_pembayaran='Menunggu Konfirmasi'"))['total'];
$donasi_berhasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM donasi WHERE status_pembayaran='Berhasil'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Donasi - Kita Peduli</title>
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
                            <a href="donasi.php" class="nav-link active">
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
                <h2 class="h3 mb-4">Kelola Donasi</h2>

                <!-- Statistics Sederhana -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>Total Donasi</h5>
                                <h4>Rp <?php echo number_format($total_donasi ?: 0, 0, ',', '.'); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h5>Donasi Berhasil</h5>
                                <h4><?php echo $donasi_berhasil; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>Menunggu Konfirmasi</h5>
                                <h4><?php echo $donasi_menunggu; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Status berhasil diupdate!</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Donatur</th>
                                        <th>Kampanye</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($donasi)): 
                                        $status_color = [
                                            'Berhasil' => 'success',
                                            'Gagal' => 'danger', 
                                            'Menunggu Konfirmasi' => 'warning'
                                        ][$row['status_pembayaran']];
                                    ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_donasi'])); ?></td>
                                        <td><?php echo $row['nama_donatur']; ?></td>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td>Rp <?php echo number_format($row['jumlah_donasi'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-<?php echo $status_color; ?>"><?php echo $row['status_pembayaran']; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id_donasi']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $row['id_donasi']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Sederhana -->
                                    <div class="modal fade" id="detailModal<?php echo $row['id_donasi']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Donasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama:</strong> <?php echo $row['nama_donatur']; ?></p>
                                                    <p><strong>Email:</strong> <?php echo $row['email_donatur']; ?></p>
                                                    <p><strong>Kampanye:</strong> <?php echo $row['judul']; ?></p>
                                                    <p><strong>Jumlah:</strong> Rp <?php echo number_format($row['jumlah_donasi'], 0, ',', '.'); ?></p>
                                                    <p><strong>Status:</strong> <span class="badge bg-<?php echo $status_color; ?>"><?php echo $row['status_pembayaran']; ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Update Status Sederhana -->
                                    <div class="modal fade" id="statusModal<?php echo $row['id_donasi']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_donasi" value="<?php echo $row['id_donasi']; ?>">
                                                        <select class="form-select" name="status_pembayaran">
                                                            <option value="Menunggu Konfirmasi" <?php if($row['status_pembayaran']=='Menunggu Konfirmasi') echo 'selected'; ?>>Menunggu</option>
                                                            <option value="Berhasil" <?php if($row['status_pembayaran']=='Berhasil') echo 'selected'; ?>>Berhasil</option>
                                                            <option value="Gagal" <?php if($row['status_pembayaran']=='Gagal') echo 'selected'; ?>>Gagal</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
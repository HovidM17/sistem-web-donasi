<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Query statistik
$q = [
    "total_kampanye"  => "SELECT COUNT(*) total FROM kampanye",
    "total_donasi"    => "SELECT SUM(jumlah_donasi) total FROM donasi WHERE status_pembayaran='Berhasil'",
    "total_donatur"   => "SELECT COUNT(DISTINCT email_donatur) total FROM donasi",
    "kampanye_aktif"  => "SELECT COUNT(*) total FROM kampanye WHERE status_kampanye='Aktif'"
];

$stats = [];
foreach ($q as $key => $sql) {
    $stats[$key] = mysqli_fetch_assoc(mysqli_query($conn, $sql))['total'] ?? 0;
}

// Donasi terbaru
$recent = mysqli_query($conn, "
    SELECT d.*, k.judul 
    FROM donasi d 
    JOIN kampanye k USING (id_kampanye)
    ORDER BY tanggal_donasi DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-success px-3">
    <a class="navbar-brand fw-bold" href="dashboard.php">
        <i class="fas fa-hand-holding-heart me-2"></i>Kita Peduli - Admin
    </a>
    <div>
        <span class="navbar-text me-3">Halo, <?= $_SESSION['admin']['nama_lengkap']; ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-3 col-lg-2 sidebar-green p-3">
    <?php
    $menu = [
        "dashboard.php" => ["Dashboard", "fa-tachometer-alt"],
        "kampanye.php"  => ["Kampanye", "fa-hand-holding-heart"],
        "kategori.php"  => ["Kategori", "fa-tags"],
        "donasi.php"    => ["Donasi", "fa-donate"],
        "pencairan.php" => ["Pencairan Dana", "fa-money-bill-wave"],
    ];
    ?>
    <ul class="nav nav-pills flex-column">
        <?php foreach ($menu as $link => [$label, $icon]): ?>
        <li class="nav-item">
            <a href="<?= $link ?>" class="nav-link <?= $link == basename($_SERVER['PHP_SELF']) ? 'active' : '' ?>">
                <i class="fas <?= $icon ?> me-2"></i><?= $label ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- MAIN CONTENT -->
<main class="col-md-9 col-lg-10 px-4 py-4 dashboard-bg">

    <h2 class="h3 mb-4">Dashboard Overview</h2>

    <!-- CARD STATISTIK -->
    <div class="row">
        <?php 
        $cards = [
            ["bg-danger",  "fa-hand-holding-heart", "Total Kampanye", $stats['total_kampanye']],
            ["bg-success", "fa-donate",             "Total Donasi",   "Rp " . number_format($stats['total_donasi'],0,",",".")],
            ["bg-info",    "fa-users",              "Total Donatur",  $stats['total_donatur']],
            ["bg-warning", "fa-play-circle",        "Kampanye Aktif", $stats['kampanye_aktif']]
        ];

        foreach ($cards as [$bg,$icon,$label,$value]): ?>
            <div class="col-md-3 mb-3">
                <div class="card text-white <?= $bg ?>">
                    <div class="card-body text-center">
                        <i class="fas <?= $icon ?> fa-2x mb-2"></i>
                        <h5><?= $label ?></h5>
                        <h3><?= $value ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- DONASI TERBARU -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Donasi Terbaru</h5>
            <a href="donasi.php" class="btn btn-primary btn-sm">Lihat Semua</a>
        </div>

        <div class="table-responsive p-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Donatur</th>
                        <th>Kampanye</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = mysqli_fetch_assoc($recent)): ?>
                        <?php
                        $badge = [
                            "Berhasil" => "bg-success",
                            "Gagal"    => "bg-danger",
                            "Menunggu Konfirmasi" => "bg-warning"
                        ][$d['status_pembayaran']];
                        ?>
                        <tr>
                            <td><?= $d['nama_donatur'] ?></td>
                            <td><?= $d['judul'] ?></td>
                            <td>Rp <?= number_format($d['jumlah_donasi'],0,",",".") ?></td>
                            <td><?= date("d/m/Y", strtotime($d['tanggal_donasi'])) ?></td>
                            <td><span class="badge <?= $badge ?>"><?= $d['status_pembayaran'] ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>

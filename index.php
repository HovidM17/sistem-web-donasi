<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<section class="hero-section text-center py-5">
    <div class="container">
        <h1 class="display-5 fw-bold text-primary mb-3 animate__animated animate__fadeInDown">Mari Berbagi, Mari Peduli</h1>
        <p class="lead text-muted mb-4 animate__animated animate__fadeInUp">
            Setiap donasi yang kamu berikan membawa senyum, harapan, dan kehidupan baru bagi mereka yang membutuhkan.
        </p>
        
    </div>
</section>

<!-- Kampanye  -->
<section class="py-5 section-stats">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="p-4 shadow-sm rounded bg-white hover-zoom">
                    <h3 class="text-success fw-bold">Rp 100 Juta+</h3>
                    <p class="text-muted mb-0">Total Donasi Terkumpul</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 shadow-sm rounded bg-white hover-zoom">
                    <h3 class="text-success fw-bold">50+</h3>
                    <p class="text-muted mb-0">Program Aktif</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 shadow-sm rounded bg-white hover-zoom">
                    <h3 class="text-success fw-bold">100+</h3>
                    <p class="text-muted mb-0">Donatur Bergabung</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Campaigns -->
<section class="py-5 section-program">
    <div class="container">
        <h2 class="text-center mb-5 text-primary fw-bold"> Program Terbaru</h2>
        <div class="row">
            <?php
            include 'config/koneksi.php';
            $query = "SELECT k.*, kat.nama_kategori 
                     FROM kampanye k 
                     JOIN kategori kat ON k.id_kategori = kat.id_kategori 
                     WHERE k.status_kampanye = 'Aktif' 
                     ORDER BY k.tanggal_mulai DESC 
                     LIMIT 3";
            $result = mysqli_query($conn, $query);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $progress = ($row['dana_terkumpul'] / $row['target_dana']) * 100;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 card-hover rounded-4 overflow-hidden">
                    <!-- Tempat gambar program -->
                    <div class="program-img">
                        <img src="uploads/<?php echo $row['gambar']; ?>" 
                             class="img-fluid w-100" 
                             alt="<?php echo $row['judul']; ?>">
                    </div>

                    <div class="card-body">
                        <span class="badge bg-success mb-2"><?php echo $row['nama_kategori']; ?></span>
                        <h5 class="card-title text-primary fw-semibold"><?php echo $row['judul']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr($row['deskripsi'], 0, 100); ?>...</p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Terkumpul</small>
                                <small class="fw-bold text-success">Rp <?php echo number_format($row['dana_terkumpul'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                            <small class="text-muted">Target: Rp <?php echo number_format($row['target_dana'], 0, ',', '.'); ?></small>
                        </div>
                        
                        <a href="donasi.php?kampanye=<?php echo $row['id_kampanye']; ?>" class="btn btn-donasi w-100 rounded-pill">
                            🤝 Donasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="kampanye.php" class="btn btn-outline-success rounded-pill px-4">Lihat Semua Program</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

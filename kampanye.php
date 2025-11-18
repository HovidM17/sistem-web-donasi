<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h1 class="text-center fw-bold mb-4 text-success">Semua Program Berbagi</h1>
    <p class="text-center text-muted mb-5">
        Temukan berbagai Program yang bisa kamu dukung untuk membantu sesama.  
        Setiap donasi kecilmu membawa perubahan besar.
    </p>

    <div class="row">
        <?php
        include 'config/koneksi.php';
        $query = "SELECT k.*, kat.nama_kategori 
                 FROM kampanye k 
                 JOIN kategori kat ON k.id_kategori = kat.id_kategori 
                 WHERE k.status_kampanye = 'Aktif' 
                 ORDER BY k.tanggal_mulai DESC";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $progress = ($row['dana_terkumpul'] / $row['target_dana']) * 100;
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-card" style="transition: transform 0.2s ease;">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-success mb-2 px-3 py-2"><?php echo $row['nama_kategori']; ?></span>
                    
                    <div class="program-img">
                        <img src="uploads/<?php echo $row['gambar']; ?>" 
                             class="img-fluid w-100" 
                             alt="<?php echo $row['judul']; ?>">
                    </div>   
                    <h5 class="card-title custom-title fw-semibold">
                        <?php echo $row['judul']; ?>
                    </h5>
                    <p class="card-text text-muted flex-grow-1">
                        <?php echo substr($row['deskripsi'], 0, 150); ?>...
                    </p>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Terkumpul</small>
                            <small class="fw-bold text-success">
                                Rp <?php echo number_format($row['dana_terkumpul'], 0, ',', '.'); ?>
                            </small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                        <small class="text-muted">Target: Rp <?php echo number_format($row['target_dana'], 0, ',', '.'); ?></small>
                    </div>                 
                    <a href="donasi.php?kampanye=<?php echo $row['id_kampanye']; ?>" 
                       class="btn btn-success mt-auto rounded-pill w-100 shadow-sm">
                        🤝 Donasi Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<style>
/* Warna latar belakang halaman */
body {
  background-color: #e9f9e8;
}
.hero-section {
  background: linear-gradient(to bottom, var(--bg-light-top), var(--bg-light-bottom));
  background-image: url('../assets/img/hero-bg.svg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  animation: fadeInHero 1.2s ease;
}
.program-img {
  height: 220px;
  overflow: hidden;
}
.program-img img {
  object-fit: cover;
  height: 100%;
  transition: transform 0.4s ease;
}
.program-img:hover img {
  transform: scale(1.05);
}
.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}
.custom-title {
  color: #2c7a3f; 
  transition: color 0.3s ease;
}
.custom-title:hover {
  color: #1abc9c; 
}
</style>

<?php include 'includes/footer.php'; ?>
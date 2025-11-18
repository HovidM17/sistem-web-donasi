<?php include 'includes/header.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white text-center fw-semibold fs-4" 
             style="background: linear-gradient(to right, #27ae60, #2ecc71);">
          Form Donasi
        </div>

        <div class="card-body p-5 bg-white">
          <?php
          include 'config/koneksi.php';
          
          if (isset($_GET['kampanye'])) {
              $id_kampanye = $_GET['kampanye'];
              $query = "SELECT * FROM kampanye WHERE id_kampanye = ?";
              $stmt = mysqli_prepare($conn, $query);
              mysqli_stmt_bind_param($stmt, "i", $id_kampanye);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              $kampanye = mysqli_fetch_assoc($result);
              
              if (!$kampanye) {
                  echo '<div class="alert alert-danger rounded-3">Kampanye tidak ditemukan!</div>';
              }
          }
          
          if ($_POST) {
              $id_kampanye = $_POST['id_kampanye'];
              $nama_donatur = $_POST['nama_donatur'];
              $email_donatur = $_POST['email_donatur'];
              $jumlah_donasi = $_POST['jumlah_donasi'];
              $jumlah_donasi = str_replace('.', '', $jumlah_donasi); 
              $metode_pembayaran = $_POST['metode_pembayaran'];
              $pesan_donatur = $_POST['pesan_donatur'];
              
              $query = "INSERT INTO donasi (id_kampanye, nama_donatur, email_donatur, jumlah_donasi, metode_pembayaran, pesan_donatur) 
                        VALUES (?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_prepare($conn, $query);
              mysqli_stmt_bind_param($stmt, "issdss", $id_kampanye, $nama_donatur, $email_donatur, $jumlah_donasi, $metode_pembayaran, $pesan_donatur);
              
              if (mysqli_stmt_execute($stmt)) {
                  echo '<div class="alert alert-success rounded-3 shadow-sm">Donasi berhasil dikirim! Terima kasih atas kontribusinya.</div>';
              } else {
                  echo '<div class="alert alert-danger rounded-3 shadow-sm">Terjadi kesalahan. Silakan coba lagi.</div>';
              }
          }
          ?>
          
          <?php if (isset($kampanye) && $kampanye): ?>
          <div class="mb-4 p-4 bg-light border rounded-3 shadow-sm">
            <h5 class="fw-semibold text-success mb-2"><?php echo $kampanye['judul']; ?></h5>
            <p class="mb-0 text-secondary" style="line-height: 1.6;"><?php echo $kampanye['deskripsi']; ?></p>
          </div>
          
          <form method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="id_kampanye" value="<?php echo $kampanye['id_kampanye']; ?>">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nama_donatur" class="form-label fw-semibold text-success">Nama Lengkap *</label>
                <input type="text" class="form-control form-control-lg border-success rounded-3 shadow-sm" id="nama_donatur" name="nama_donatur" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email_donatur" class="form-label fw-semibold text-success">Email *</label>
                <input type="email" class="form-control form-control-lg border-success rounded-3 shadow-sm" id="email_donatur" name="email_donatur" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="jumlah_donasi" class="form-label fw-semibold text-success">Jumlah Donasi (Rp) *</label>
              <input type="text" class="form-control form-control-lg border-success rounded-3 shadow-sm" id="jumlah_donasi" name="jumlah_donasi" required>
            </div>

            <div class="mb-3">
              <label for="metode_pembayaran" class="form-label fw-semibold text-success">Metode Pembayaran *</label>
              <select class="form-select form-select-lg border-success rounded-3 shadow-sm" id="metode_pembayaran" name="metode_pembayaran" required>
                <option value="">Pilih Metode</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="Kartu Kredit">Kartu Kredit</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="pesan_donatur" class="form-label fw-semibold text-success">Pesan untuk Penerima</label>
              <textarea class="form-control border-success rounded-3 shadow-sm" id="pesan_donatur" name="pesan_donatur" rows="3" placeholder="Tuliskan pesan Anda..."></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 py-2 fw-semibold shadow">Kirim Donasi</button>
          </form>

          <?php else: ?>
          <div class="alert alert-warning rounded-3">Silakan pilih kampanye terlebih dahulu dari halaman kampanye.</div>
          <a href="kampanye.php" class="btn btn-success shadow-sm">Lihat Kampanye</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('jumlah_donasi').addEventListener('input', function(e) {
  let value = e.target.value;
  value = value.replace(/\D/g, '');
  e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
});
</script>

<style> 
body {
  background-color: #ecf9f0;
  font-family: 'Poppins', sans-serif;
}

.form-label {
  font-size: 15px;
}

.form-control:focus, .form-select:focus {
  box-shadow: 0 0 0 0.25rem rgba(46, 204, 113, 0.25);
  border-color: #27ae60;
}

.card {
  transition: transform 0.2s ease-in-out;
}
.card:hover {
  transform: translateY(-3px);
}

.btn-success:hover {
  background-color: #219150;
}
</style>

<?php include 'includes/footer.php'; ?>
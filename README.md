# 🎁 Sistem Informasi Web Donasi Sederhana

Aplikasi web ini dikembangkan untuk memfasilitasi manajemen data donasi secara digital, mulai dari pencatatan data donatur, penerimaan dana, hingga laporan penyaluran. Proyek ini dikembangkan sebagai tugas akhir mata kuliah **Sistem Informasi Semester 3** dengan fokus pada penggunaan **PHP Native (murni)** dan praktik database yang terstruktur.

---

## 🎯 Fitur Utama

Fitur yang telah diimplementasikan dalam versi ini meliputi:

* **Manajemen Kategori:** Admin dapat menambah, mengubah, dan menghapus kategori donasi (Pendidikan, Bencana, dll.). (CRUD Kategori)
* **Manajemen Kampanye:** Admin dapat membuat, mengubah, dan menghapus kampanye donasi, termasuk *upload* foto utama kampanye. (CRUD Kampanye)
* **Pencatatan Transaksi:** Pengguna publik dapat mengisi *form* donasi yang datanya langsung masuk ke tabel `donasi` dengan status 'Menunggu Konfirmasi'.
* **Verifikasi Donasi:** Admin dapat mengubah status donasi menjadi 'Berhasil', yang secara otomatis akan **meng-update total dana terkumpul** di Kampanye terkait.
* **Halaman Publik:** Menampilkan daftar kampanye aktif dan halaman detail untuk proses donasi.
* **Manajemen User:** Sistem Login dan Session sederhana untuk mengamankan akses Admin Panel.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend** | **PHP Native (Murni)** | Bahasa pemrograman utama. |
| **Database** | **MySQL** / MariaDB | Digunakan untuk menyimpan data transaksi, kampanye, dan admin. |
| **Frontend** | HTML, CSS, **JavaScript** | Digunakan untuk antarmuka pengguna dan validasi sisi klien dasar. |
| **Framework CSS** | **Tidak Ada** | Menggunakan CSS Murni dengan struktur sederhana. |

---

## ⚙️ Cara Instalasi (Lokal)

Proyek ini dirancang untuk dijalankan di lingkungan *web server* lokal (XAMPP/MAMP/Laragon).

1.  Pastikan Anda sudah menginstal **XAMPP** atau sejenisnya.
2.  Clone repositori ini atau unduh filenya.
3.  Pindahkan folder repositori (misalnya `db_donasi`) ke direktori `htdocs` XAMPP Anda.
4.  Buka **phpMyAdmin** dan buat database baru dengan nama **`db_donasi`**.
5.  Impor *query* SQL skema database. Anda dapat menggunakan *query* lengkap yang terdapat pada lampiran file **`db_donasi.sql`** untuk membuat semua tabel dan relasi.
7.  Akses aplikasi melalui *browser*: `http://localhost/db_donasi/` (ganti `db_donasi` jika nama folder berbeda).

### Akses Admin Panel:

* **URL:** `http://localhost/db_donasi/admin/login.php`

---

## ➕ Panduan Penggunaan Awal

1.  **Login:** Akses halaman *login* admin.
2.  **Kelola Kategori:** Masuk ke menu **Kelola Kategori** dan buat beberapa kategori baru (Pendidikan, Bencana Alam, dll.).
3.  **Buat Kampanye:** Masuk ke **Kelola Kampanye** dan buat kampanye baru dengan mengunggah foto, menentukan target dana, dan memilih kategori yang baru Anda buat.
4.  **Uji Donasi:** Buka halaman utama (`index.php`) dan coba berdonasi melalui kampanye yang baru Anda buat.
5.  **Verifikasi:** Cek menu **Laporan Donasi** untuk memverifikasi dan mengubah status donasi yang masuk.

---

## 👥 Kontributor

* [Nama Anda/Nama Anggota Kelompok] - Mahasiswa Sistem Informasi Semester 3

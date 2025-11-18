<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kita Peduli - Platform Donasi Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        /* Warna background navbar */
        .navbar {
            background-color: #198754 !important;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Warna teks dan ikon */
        .navbar .nav-link,
        .navbar-brand,
        .navbar i {
            color: #ffffff !important;
        }

        /* Warna hover */
        .navbar .nav-link:hover {
            color: #edd4e0ff !important;
        }

        /* Hilangkan ruang kosong di atas header */
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-hand-holding-heart me-2"></i>Kita Peduli
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

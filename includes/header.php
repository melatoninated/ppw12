<?php
$currentPage = basename($_SERVER['PHP_SELF']);
function active_nav($file, $currentPage)
{
    return $file === $currentPage ? 'active' : '';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'BookNest Inventory'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="side-panel">
        <div class="brand-box">
            <div class="brand-mark">BN</div>
            <div>
                <h1>BookNest</h1>
                <p>Mini Library Inventory</p>
            </div>
        </div>
        <nav class="nav flex-column gap-2 mt-4">
            <a class="nav-link <?= active_nav('index.php', $currentPage); ?>" href="index.php">Dashboard Buku</a>
            <a class="nav-link <?= active_nav('buku_create.php', $currentPage); ?>" href="buku_create.php">Tambah Buku</a>
            <a class="nav-link <?= active_nav('konversi_nilai.php', $currentPage); ?>" href="konversi_nilai.php">Konversi Nilai</a>
            <a class="nav-link <?= active_nav('pendataan_mahasiswa.php', $currentPage); ?>" href="pendataan_mahasiswa.php">Pendataan Mahasiswa</a>
        </nav>
        <div class="side-note mt-auto">
            <span>PHP • MySQL • Bootstrap</span>
        </div>
    </aside>
    <main class="content-area">

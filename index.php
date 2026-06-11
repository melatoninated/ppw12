<?php
$pageTitle = 'Dashboard Buku - BookNest';
require_once 'config/database.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE kode_buku LIKE :q OR judul LIKE :q OR penulis LIKE :q OR kategori LIKE :q ORDER BY id DESC");
    $stmt->execute(['q' => "%{$q}%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM buku ORDER BY id DESC");
}
$books = $stmt->fetchAll();

$stats = $pdo->query("SELECT COUNT(*) AS total_buku, COALESCE(SUM(stok), 0) AS total_stok,
    SUM(status = 'Tersedia') AS tersedia, SUM(status = 'Dipinjam') AS dipinjam FROM buku")->fetch();

require_once 'includes/header.php';
?>
<section class="hero-card card mb-4">
    <div class="card-body p-4 p-lg-5 d-flex flex-column flex-lg-row justify-content-between gap-4">
        <div>
            <span class="badge rounded-pill mb-3">CRUD Database</span>
            <h2 class="display-6 fw-bold mb-2">Inventaris Buku Perpustakaan Mini</h2>
            <p class="mb-0 opacity-75">Kelola kode buku, judul, penulis, kategori, stok, status, dan lokasi rak dalam satu dashboard.</p>
        </div>
        <div class="align-self-lg-end">
            <a href="buku_create.php" class="btn btn-light fw-bold rounded-pill px-4">+ Tambah Buku</a>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card"><span>Total Judul</span><strong><?= e($stats['total_buku']); ?></strong></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card"><span>Total Stok</span><strong><?= e($stats['total_stok']); ?></strong></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card"><span>Tersedia</span><strong><?= e($stats['tersedia']); ?></strong></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card"><span>Dipinjam</span><strong><?= e($stats['dipinjam']); ?></strong></div></div>
</div>

<div class="soft-card p-3 p-lg-4">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success rounded-4">Data berhasil diproses.</div>
    <?php endif; ?>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
        <div>
            <h3 class="h4 fw-bold mb-1">Koleksi Buku</h3>
            <p class="text-secondary mb-0">Data ditampilkan dari tabel <code>buku</code>.</p>
        </div>
        <form class="d-flex gap-2" method="get">
            <input type="search" class="form-control" name="q" placeholder="Cari buku..." value="<?= e($q); ?>">
            <button class="btn btn-forest rounded-pill px-4" type="submit">Cari</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Rak</th>
                <th class="text-end">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($books)): ?>
                <tr><td colspan="8" class="text-center text-secondary py-4">Belum ada data yang cocok.</td></tr>
            <?php endif; ?>
            <?php foreach ($books as $book): ?>
                <?php
                    $badge = 'badge-ready';
                    if ($book['status'] === 'Dipinjam') $badge = 'badge-loan';
                    if ($book['status'] === 'Perbaikan') $badge = 'badge-repair';
                ?>
                <tr>
                    <td class="fw-bold"><?= e($book['kode_buku']); ?></td>
                    <td>
                        <div class="fw-semibold"><?= e($book['judul']); ?></div>
                        <small class="text-secondary">oleh <?= e($book['penulis']); ?></small>
                    </td>
                    <td><?= e($book['kategori']); ?></td>
                    <td><?= e($book['tahun_terbit']); ?></td>
                    <td><?= e($book['stok']); ?></td>
                    <td><span class="badge-soft <?= $badge; ?>"><?= e($book['status']); ?></span></td>
                    <td><?= e($book['lokasi_rak']); ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-gold rounded-pill" href="buku_edit.php?id=<?= e($book['id']); ?>">Edit</a>
                        <form class="d-inline" action="buku_delete.php" method="post" onsubmit="return confirm('Hapus data buku ini?')">
                            <input type="hidden" name="id" value="<?= e($book['id']); ?>">
                            <button class="btn btn-sm btn-outline-danger rounded-pill" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

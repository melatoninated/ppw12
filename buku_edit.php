<?php
$pageTitle = 'Edit Buku - BookNest';
require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM buku WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
    header('Location: index.php');
    exit;
}

$errors = [];
$kategoriList = ['Fiksi', 'Teknologi', 'Sejarah', 'Sains', 'Referensi', 'Bisnis', 'Seni'];
$statusList = ['Tersedia', 'Dipinjam', 'Perbaikan'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['kode_buku','judul','penulis','kategori','tahun_terbit','stok','status','lokasi_rak'] as $key) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    if ($data['kode_buku'] === '') $errors[] = 'Kode buku wajib diisi.';
    if ($data['judul'] === '') $errors[] = 'Judul buku wajib diisi.';
    if ($data['penulis'] === '') $errors[] = 'Nama penulis wajib diisi.';
    if (!in_array($data['kategori'], $kategoriList, true)) $errors[] = 'Kategori tidak valid.';
    if (!preg_match('/^(19|20)\d{2}$/', $data['tahun_terbit'])) $errors[] = 'Tahun terbit harus berupa tahun yang valid.';
    if (!filter_var($data['stok'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])) $errors[] = 'Stok harus berupa angka minimal 0.';
    if (!in_array($data['status'], $statusList, true)) $errors[] = 'Status tidak valid.';
    if ($data['lokasi_rak'] === '') $errors[] = 'Lokasi rak wajib diisi.';

    if (empty($errors)) {
        $cek = $pdo->prepare('SELECT COUNT(*) FROM buku WHERE kode_buku = ? AND id != ?');
        $cek->execute([$data['kode_buku'], $id]);
        if ($cek->fetchColumn() > 0) {
            $errors[] = 'Kode buku sudah digunakan oleh data lain.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE buku SET kode_buku=?, judul=?, penulis=?, kategori=?, tahun_terbit=?, stok=?, status=?, lokasi_rak=? WHERE id=?');
        $stmt->execute([
            $data['kode_buku'], $data['judul'], $data['penulis'], $data['kategori'],
            $data['tahun_terbit'], $data['stok'], $data['status'], $data['lokasi_rak'], $id
        ]);
        header('Location: index.php?success=1');
        exit;
    }
}

require_once 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-secondary fw-semibold mb-1">Update</p>
        <h2 class="fw-bold mb-0">Edit Data Buku</h2>
    </div>
    <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
</div>

<div class="soft-card p-4 p-lg-5">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-4">
            <strong>Periksa kembali input:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?><li><?= e($error); ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Kode Buku <span class="required">*</span></label>
            <input type="text" name="kode_buku" class="form-control" value="<?= e($data['kode_buku']); ?>">
        </div>
        <div class="col-md-8">
            <label class="form-label">Judul Buku <span class="required">*</span></label>
            <input type="text" name="judul" class="form-control" value="<?= e($data['judul']); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Penulis <span class="required">*</span></label>
            <input type="text" name="penulis" class="form-control" value="<?= e($data['penulis']); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Kategori <span class="required">*</span></label>
            <select name="kategori" class="form-select">
                <?php foreach ($kategoriList as $kategori): ?>
                    <option value="<?= e($kategori); ?>" <?= $data['kategori'] === $kategori ? 'selected' : ''; ?>><?= e($kategori); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tahun Terbit <span class="required">*</span></label>
            <input type="number" name="tahun_terbit" class="form-control" value="<?= e($data['tahun_terbit']); ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Stok <span class="required">*</span></label>
            <input type="number" name="stok" min="0" class="form-control" value="<?= e($data['stok']); ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status <span class="required">*</span></label>
            <select name="status" class="form-select">
                <?php foreach ($statusList as $status): ?>
                    <option value="<?= e($status); ?>" <?= $data['status'] === $status ? 'selected' : ''; ?>><?= e($status); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Lokasi Rak <span class="required">*</span></label>
            <input type="text" name="lokasi_rak" class="form-control" value="<?= e($data['lokasi_rak']); ?>">
        </div>
        <div class="col-12 pt-3">
            <button class="btn btn-forest rounded-pill px-5" type="submit">Perbarui Buku</button>
        </div>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?>

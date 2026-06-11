<?php
$pageTitle = 'Pendataan Mahasiswa - BookNest';
require_once 'config/database.php';

$prodiList = [
    'TRPL' => 'Teknologi Rekayasa Perangkat Lunak',
    'TRIK' => 'Teknologi Rekayasa Instrumentasi dan Kontrol',
    'TRM' => 'Teknologi Rekayasa Mesin',
    'TKE' => 'Teknologi Kelistrikan',
    'TI' => 'Teknologi Informasi'
];

$form = [
    'nama' => '',
    'nim' => '',
    'prodi' => '',
    'ipk' => '',
    'semester' => '',
];
$errors = [];
$hasil = null;

function predikatKelulusan($ipk)
{
    if ($ipk >= 3.51) return 'Dengan Pujian';
    if ($ipk >= 3.01) return 'Sangat Memuaskan';
    if ($ipk >= 2.76) return 'Memuaskan';
    if ($ipk >= 2.00) return 'Lulus';
    return 'Belum Memenuhi Syarat';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? '');
    }

    if ($form['nama'] === '' || mb_strlen($form['nama']) < 3) {
        $errors[] = 'Nama wajib diisi minimal 3 karakter.';
    }
    if (!preg_match('/^\d{2}\/\d{6}\/[A-Z]{2}\/\d{5}$/', $form['nim'])) {
        $errors[] = 'Format NIM tidak valid. Contoh: 25/123456/SV/12345.';
    }
    if (!array_key_exists($form['prodi'], $prodiList)) {
        $errors[] = 'Program studi wajib dipilih.';
    }
    if ($form['ipk'] === '' || !is_numeric($form['ipk']) || $form['ipk'] < 0 || $form['ipk'] > 4) {
        $errors[] = 'IPK harus berupa angka 0.00 sampai 4.00.';
    }
    if (!filter_var($form['semester'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 14]])) {
        $errors[] = 'Semester harus berupa angka 1 sampai 14.';
    }

    if (empty($errors)) {
        $hasil = [
            'nama' => $form['nama'],
            'nim' => $form['nim'],
            'prodi' => $prodiList[$form['prodi']],
            'ipk' => number_format((float) $form['ipk'], 2),
            'semester' => (int) $form['semester'],
            'predikat' => predikatKelulusan((float) $form['ipk'])
        ];
    }
}

require_once 'includes/header.php';
?>
<div class="row g-4">
    <div class="col-xl-7">
        <div class="mb-4">
            <p class="text-secondary fw-semibold mb-1">PHP Lanjutan 2</p>
            <h2 class="fw-bold mb-0">Pendataan Mahasiswa</h2>
            <p class="text-secondary mt-2 mb-0">Input divalidasi di sisi server dan output ditampilkan kembali menggunakan escaping.</p>
        </div>

        <div class="soft-card p-4 p-lg-5">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-4">
                    <strong>Data belum valid:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($errors as $error): ?><li><?= e($error); ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?= e($form['nama']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIM <span class="required">*</span></label>
                    <input type="text" name="nim" class="form-control" placeholder="25/123456/SV/12345" value="<?= e($form['nim']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Program Studi <span class="required">*</span></label>
                    <select name="prodi" class="form-select">
                        <option value="">Pilih prodi</option>
                        <?php foreach ($prodiList as $kode => $namaProdi): ?>
                            <option value="<?= e($kode); ?>" <?= $form['prodi'] === $kode ? 'selected' : ''; ?>><?= e($namaProdi); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">IPK <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" max="4" name="ipk" class="form-control" placeholder="0.00 - 4.00" value="<?= e($form['ipk']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <input type="number" min="1" max="14" name="semester" class="form-control" value="<?= e($form['semester']); ?>">
                </div>
                <div class="col-12 pt-3 d-grid">
                    <button class="btn btn-forest rounded-pill" type="submit">Proses Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="identity-card p-4 p-lg-5 mt-xl-5">
            <h3 class="h4 fw-bold mb-3">Hasil Pendataan</h3>
            <?php if ($hasil): ?>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama</dt><dd class="col-sm-8"><?= e($hasil['nama']); ?></dd>
                    <dt class="col-sm-4">NIM</dt><dd class="col-sm-8"><?= e($hasil['nim']); ?></dd>
                    <dt class="col-sm-4">Prodi</dt><dd class="col-sm-8"><?= e($hasil['prodi']); ?></dd>
                    <dt class="col-sm-4">IPK</dt><dd class="col-sm-8"><?= e($hasil['ipk']); ?></dd>
                    <dt class="col-sm-4">Semester</dt><dd class="col-sm-8"><?= e($hasil['semester']); ?></dd>
                    <dt class="col-sm-4">Predikat</dt><dd class="col-sm-8"><span class="badge-soft badge-ready"><?= e($hasil['predikat']); ?></span></dd>
                </dl>
            <?php else: ?>
                <p class="text-secondary mb-0">Ringkasan akan muncul setelah form diisi dengan data yang valid.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

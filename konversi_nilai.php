<?php
$pageTitle = 'Konversi Nilai - BookNest';
require_once 'config/database.php';

$nilai = $_POST['nilai'] ?? '';
$error = '';
$hasil = null;

function hitungGrade($nilaiAngka)
{
    if ($nilaiAngka >= 85) {
        return ['grade' => 'A', 'deskripsi' => 'Sangat Baik', 'class' => 'grade-a'];
    }
    if ($nilaiAngka >= 70) {
        return ['grade' => 'B', 'deskripsi' => 'Baik', 'class' => 'grade-b'];
    }
    if ($nilaiAngka >= 55) {
        return ['grade' => 'C', 'deskripsi' => 'Cukup', 'class' => 'grade-c'];
    }
    if ($nilaiAngka >= 40) {
        return ['grade' => 'D', 'deskripsi' => 'Kurang', 'class' => 'grade-d'];
    }
    return ['grade' => 'E', 'deskripsi' => 'Sangat Kurang', 'class' => 'grade-e'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($nilai === '' || !is_numeric($nilai)) {
        $error = 'Nilai harus diisi dengan angka.';
    } elseif ($nilai < 0 || $nilai > 100) {
        $error = 'Nilai harus berada pada rentang 0 sampai 100.';
    } else {
        $hasil = hitungGrade((float) $nilai);
    }
}

require_once 'includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-secondary fw-semibold mb-1">PHP Lanjutan 1</p>
                <h2 class="fw-bold mb-0">Form Konversi Nilai</h2>
            </div>
        </div>

        <div class="soft-card p-4 p-lg-5 mb-4">
            <form method="post" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Masukkan Nilai Angka (0 - 100)</label>
                    <input type="number" step="0.01" min="0" max="100" name="nilai" class="form-control" value="<?= e($nilai); ?>" placeholder="Contoh: 88">
                </div>
                <div class="col-md-4 d-grid">
                    <button class="btn btn-forest rounded-pill" type="submit">Konversi</button>
                </div>
            </form>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger rounded-4"><?= e($error); ?></div>
        <?php endif; ?>

        <?php if ($hasil): ?>
            <div class="grade-panel <?= e($hasil['class']); ?> mb-4">
                <div class="grade-letter"><?= e($hasil['grade']); ?></div>
                <h3 class="fw-bold mb-1"><?= e($hasil['deskripsi']); ?></h3>
                <p class="text-secondary mb-0">Nilai yang dimasukkan: <?= e($nilai); ?></p>
            </div>
        <?php endif; ?>

        <div class="soft-card p-4">
            <h3 class="h5 fw-bold mb-3">Referensi Grade</h3>
            <div class="row g-3">
                <div class="col-md"><div class="p-3 rounded-4 bg-success-subtle"><strong>A</strong><br><small>85 - 100</small></div></div>
                <div class="col-md"><div class="p-3 rounded-4 bg-primary-subtle"><strong>B</strong><br><small>70 - 84</small></div></div>
                <div class="col-md"><div class="p-3 rounded-4 bg-warning-subtle"><strong>C</strong><br><small>55 - 69</small></div></div>
                <div class="col-md"><div class="p-3 rounded-4 bg-danger-subtle"><strong>D</strong><br><small>40 - 54</small></div></div>
                <div class="col-md"><div class="p-3 rounded-4 bg-secondary-subtle"><strong>E</strong><br><small>0 - 39</small></div></div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

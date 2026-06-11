CREATE DATABASE IF NOT EXISTS booknest_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booknest_db;

DROP TABLE IF EXISTS buku;

CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(20) NOT NULL UNIQUE,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    kategori VARCHAR(60) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    status ENUM('Tersedia', 'Dipinjam', 'Perbaikan') NOT NULL DEFAULT 'Tersedia',
    lokasi_rak VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO buku (kode_buku, judul, penulis, kategori, tahun_terbit, stok, status, lokasi_rak) VALUES
('BN-FIC-001', 'Langit di Ujung Rak', 'Raka Pramana', 'Fiksi', 2022, 4, 'Tersedia', 'A1'),
('BN-TEC-002', 'Dasar Pemrograman Web', 'Maya Santoso', 'Teknologi', 2021, 7, 'Tersedia', 'B2'),
('BN-HIS-003', 'Catatan Kota Lama', 'Dian Lestari', 'Sejarah', 2019, 2, 'Dipinjam', 'C1'),
('BN-SCI-004', 'Sains untuk Pemula', 'Arief Nugroho', 'Sains', 2020, 5, 'Tersedia', 'D3'),
('BN-REF-005', 'Kamus Istilah Digital', 'Nabila Putri', 'Referensi', 2023, 1, 'Perbaikan', 'E2');

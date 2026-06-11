# BookNest - Tugas Praktikum PHP Web 1

Project ini dibuat sebagai alternatif original untuk tugas PHP CRUD, Bootstrap, export database, konversi nilai, dan pendataan mahasiswa.

## Isi Project

- CRUD data buku perpustakaan mini dengan PHP, MySQL, PDO, dan Bootstrap.
- File export database: `booknest_db.sql`.
- Form konversi nilai A/B/C/D/E.
- Form pendataan mahasiswa dengan validasi server-side dan output aman dari XSS.

## Cara Menjalankan di XAMPP

1. Pindahkan folder `booknest_php_web1` ke `C:\xampp\htdocs\`.
2. Nyalakan Apache dan MySQL di XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `booknest_db.sql`.
5. Buka `http://localhost/booknest_php_web1/`.

## Database

Database: `booknest_db`

Tabel utama CRUD: `buku`

Kolom tabel: id, kode_buku, judul, penulis, kategori, tahun_terbit, stok, status, lokasi_rak, created_at.

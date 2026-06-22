CREATE DATABASE IF NOT EXISTS bank_ide_db;
USE bank_ide_db;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE ide (
    id_ide INT AUTO_INCREMENT PRIMARY KEY,
    judul_ide VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    id_kategori INT,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
);

CREATE TABLE komentar (
    id_komentar INT AUTO_INCREMENT PRIMARY KEY,
    isi_komentar TEXT NOT NULL,
    tanggal_komentar DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user INT,
    id_ide INT,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_ide) REFERENCES ide(id_ide) ON DELETE CASCADE
);

INSERT INTO kategori (nama_kategori) VALUES 
('Teknologi'), 
('Bisnis'), 
('Pendidikan'), 
('Lingkungan'), 
('Kesehatan'), 
('Lainnya');

-- The password for the admin is 'password'
INSERT INTO users (nama, email, password, role) VALUES 
('Administrator', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Mahasiswa 1', 'mhs1@kampus.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO ide (judul_ide, deskripsi, id_user, id_kategori) VALUES 
('Aplikasi Pantau Sampah', 'Aplikasi berbasis mobile untuk memantau titik pembuangan sampah ilegal dan melaporkannya secara realtime ke dinas kebersihan.', 2, 4),
('Marketplace Barang Bekas Kampus', 'Platform untuk mahasiswa jual beli buku, alat tulis, dan perlengkapan kos yang sudah tidak terpakai agar lebih hemat.', 2, 2);

INSERT INTO komentar (isi_komentar, id_user, id_ide) VALUES 
('Ide yang sangat cemerlang, semoga bisa direalisasikan!', 1, 1),
('Bagus sekali untuk mengurangi limbah buku.', 1, 2);

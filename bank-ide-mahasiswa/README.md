# Bank Ide Mahasiswa

Sistem Bank Ide Mahasiswa Berbasis Web dengan desain modern, responsif, dan profesional. 

## Teknologi yang Digunakan
- Frontend: HTML5, CSS3 Murni (Vanilla CSS), JavaScript
- Backend: PHP Native (PDO)
- Database: MySQL
- Desain UI: Modern, Glassmorphism, Responsif, palet Biru/Putih/Abu-abu (Startup feel).

## Cara Instalasi di Localhost (XAMPP)
1. **Pindahkan Folder**: Pastikan folder project (`bank-ide-mahasiswa`) berada di dalam folder `htdocs` dari instalasi XAMPP Anda.
   Contoh direktori: `C:\xampp\htdocs\bank-ide-mahasiswa\`
2. **Nyalakan XAMPP**: Buka XAMPP Control Panel, lalu Start modul **Apache** dan **MySQL**.
3. **Impor Database**:
   - Buka browser dan akses `http://localhost/phpmyadmin/`
   - Buat database baru dengan nama `bank_ide_db`
   - Pilih tab **Import**, lalu pilih file `database.sql` yang ada di root project ini.
   - Klik **Go** atau **Kirim** untuk menjalankan query SQL.
4. **Jalankan Aplikasi**: Buka browser dan akses `http://localhost/bank-ide-mahasiswa/`

## Akun Demo (Bawaan Database)
**Akun Admin:**
- Email: `admin@admin.com`
- Password: `password`

**Akun Pengguna (Mahasiswa):**
- Email: `mhs1@kampus.ac.id`
- Password: `password`

## Fitur
- **Pengguna Biasa**: Register, Login, Tambah Ide, Edit Ide, Hapus Ide, Lihat Detail Ide, Tambah Komentar, Edit Profil, Cari & Filter Ide.
- **Admin**: Dashboard Statistik (Total Ide, Total Pengguna, dll), Manajemen Pengguna, Manajemen Kategori, Manajemen Ide, Manajemen Komentar.

## Struktur Database
Sistem ini menggunakan 4 tabel utama:
- `users`: Menyimpan data admin dan mahasiswa.
- `kategori`: Menyimpan kategori ide (Teknologi, Pendidikan, dll).
- `ide`: Menyimpan informasi ide, berelasi dengan tabel `users` dan `kategori`.
- `komentar`: Menyimpan komentar dari pengguna terhadap suatu ide, berelasi dengan `users` dan `ide`.

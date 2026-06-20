<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch User Info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (empty($nama)) {
        $error = "Nama tidak boleh kosong.";
    } else {
        if (!empty($password_baru)) {
            if ($password_baru !== $konfirmasi_password) {
                $error = "Konfirmasi password baru tidak cocok.";
            } else {
                $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET nama = ?, password = ? WHERE id_user = ?");
                $stmt->execute([$nama, $hashed, $user_id]);
                $_SESSION['nama'] = $nama;
                $success = "Profil dan password berhasil diupdate.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nama = ? WHERE id_user = ?");
            $stmt->execute([$nama, $user_id]);
            $_SESSION['nama'] = $nama;
            $success = "Profil berhasil diupdate.";
        }
    }
    
    // Refresh user info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container" style="max-width: 600px;">
        <h2 class="mb-2">Profil Saya</h2>

        <div class="card">
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email (Tidak dapat diubah)</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: #f1f5f9;">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>
                
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--border-color);">
                <h4 class="mb-1">Ubah Password (Opsional)</h4>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>

                <button type="submit" class="btn btn-primary mt-1">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

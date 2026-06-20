<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$categories = $stmt->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_ide = trim($_POST['judul_ide']);
    $deskripsi = trim($_POST['deskripsi']);
    $id_kategori = $_POST['id_kategori'];
    $id_user = $_SESSION['user_id'];

    if (empty($judul_ide) || empty($deskripsi) || empty($id_kategori)) {
        $error = 'Semua field harus diisi.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO ide (judul_ide, deskripsi, id_user, id_kategori) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$judul_ide, $deskripsi, $id_user, $id_kategori])) {
            redirect('dashboard.php?msg=added');
        } else {
            $error = 'Terjadi kesalahan saat menyimpan ide.';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container" style="max-width: 800px;">
        <h2 class="mb-2">Tambah Ide Baru</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Judul Ide</label>
                    <input type="text" name="judul_ide" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id_kategori']; ?>"><?php echo htmlspecialchars($cat['nama_kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Lengkap</label>
                    <textarea name="deskripsi" class="form-control" rows="8" required></textarea>
                </div>

                <div class="flex gap-1 mt-2">
                    <button type="submit" class="btn btn-primary">Simpan Ide</button>
                    <a href="dashboard.php" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

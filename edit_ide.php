<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$id_ide = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_COOKIE['user_id'];
$is_admin = isAdmin();

// Check if ide exists and belongs to user or is admin
$stmt = $pdo->prepare("SELECT * FROM bim_ide WHERE id_ide = ?");
$stmt->execute([$id_ide]);
$ide = $stmt->fetch();

if (!$ide) {
    redirect('dashboard.php');
}

if (!$is_admin && $ide['id_user'] != $user_id) {
    redirect('dashboard.php'); // Unauthorized
}

$stmt_kat = $pdo->query("SELECT * FROM bim_kategori ORDER BY nama_kategori ASC");
$categories = $stmt_kat->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_ide = trim($_POST['judul_ide']);
    $deskripsi = trim($_POST['deskripsi']);
    $id_kategori = $_POST['id_kategori'];

    if (empty($judul_ide) || empty($deskripsi) || empty($id_kategori)) {
        $error = 'Semua field harus diisi.';
    } else {
        $stmt = $pdo->prepare("UPDATE bim_ide SET judul_ide = ?, deskripsi = ?, id_kategori = ? WHERE id_ide = ?");
        if ($stmt->execute([$judul_ide, $deskripsi, $id_kategori, $id_ide])) {
            redirect($is_admin ? "admin/ideas.php?msg=updated" : "dashboard.php?msg=updated");
        } else {
            $error = 'Terjadi kesalahan saat mengupdate bim_ide.';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container" style="max-width: 800px;">
        <h2 class="mb-2">Edit Ide</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Judul Ide</label>
                    <input type="text" name="judul_ide" class="form-control" value="<?php echo htmlspecialchars($ide['judul_ide']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id_kategori']; ?>" <?php echo $ide['id_kategori'] == $cat['id_kategori'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Lengkap</label>
                    <textarea name="deskripsi" class="form-control" rows="8" required><?php echo htmlspecialchars($ide['deskripsi']); ?></textarea>
                </div>

                <div class="flex gap-1 mt-2">
                    <button type="submit" class="btn btn-primary">Update bim_Ide</button>
                    <a href="<?php echo $is_admin ? 'admin/ideas.php' : 'dashboard.php'; ?>" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

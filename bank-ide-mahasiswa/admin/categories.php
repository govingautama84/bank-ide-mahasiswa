<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

// Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    if (!empty($nama_kategori)) {
        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->execute([$nama_kategori]);
        redirect('categories.php?msg=added');
    }
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM kategori WHERE id_kategori = ?");
    $stmt->execute([$id]);
    redirect('categories.php?msg=deleted');
}

$stmt = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$categories = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem; color: var(--primary-color);">Admin Panel</h3>
        <a href="index.php">Dashboard</a>
        <a href="users.php">Kelola Pengguna</a>
        <a href="categories.php" class="active">Kelola Kategori</a>
        <a href="ideas.php">Kelola Ide</a>
        <a href="comments.php">Kelola Komentar</a>
    </div>
    
    <div class="admin-content">
        <div class="flex justify-between align-center mb-2">
            <h2>Kelola Kategori</h2>
        </div>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">Kategori berhasil ditambahkan.</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Kategori berhasil dihapus.</div>
        <?php endif; ?>

        <div class="card mb-2" style="max-width: 500px;">
            <h4>Tambah Kategori Baru</h4>
            <form method="POST" action="" class="flex gap-1 mt-1">
                <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori..." required>
                <button type="submit" name="add_kategori" class="btn btn-primary">Tambah</button>
            </form>
        </div>

        <div class="table-container" style="max-width: 800px;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id_kategori']; ?></td>
                        <td><?php echo htmlspecialchars($cat['nama_kategori']); ?></td>
                        <td>
                            <a href="categories.php?delete=<?php echo $cat['id_kategori']; ?>" class="btn-delete" style="color: var(--danger); text-decoration: none;">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

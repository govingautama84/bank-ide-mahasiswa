<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

// Delete Comment
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM komentar WHERE id_komentar = ?");
    $stmt->execute([$id]);
    redirect('comments.php?msg=deleted');
}

$stmt = $pdo->query("
    SELECT c.*, u.nama, i.judul_ide 
    FROM komentar c 
    LEFT JOIN users u ON c.id_user = u.id_user 
    LEFT JOIN ide i ON c.id_ide = i.id_ide 
    ORDER BY c.tanggal_komentar DESC
");
$comments = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem; color: var(--primary-color);">Admin Panel</h3>
        <a href="index.php">Dashboard</a>
        <a href="users.php">Kelola Pengguna</a>
        <a href="categories.php">Kelola Kategori</a>
        <a href="ideas.php">Kelola Ide</a>
        <a href="comments.php" class="active">Kelola Komentar</a>
    </div>
    
    <div class="admin-content">
        <h2 class="mb-2">Kelola Komentar</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Komentar berhasil dihapus.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Komentar</th>
                        <th>Komentator</th>
                        <th>Pada Ide</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($comments as $c): ?>
                    <tr>
                        <td><?php echo $c['id_komentar']; ?></td>
                        <td style="max-width: 300px;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($c['isi_komentar']); ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($c['nama']); ?></td>
                        <td>
                            <a href="../ide_detail.php?id=<?php echo $c['id_ide']; ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                                <?php echo htmlspecialchars(substr($c['judul_ide'], 0, 30)) . '...'; ?>
                            </a>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($c['tanggal_komentar'])); ?></td>
                        <td>
                            <a href="comments.php?delete=<?php echo $c['id_komentar']; ?>" class="btn-delete" style="color: var(--danger); text-decoration: none; font-size: 0.9rem;">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

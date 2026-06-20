<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

// Delete Idea
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM ide WHERE id_ide = ?");
    $stmt->execute([$id]);
    redirect('ideas.php?msg=deleted');
}

$stmt = $pdo->query("
    SELECT i.*, u.nama, k.nama_kategori 
    FROM ide i 
    LEFT JOIN users u ON i.id_user = u.id_user 
    LEFT JOIN kategori k ON i.id_kategori = k.id_kategori 
    ORDER BY i.tanggal_upload DESC
");
$ideas = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem; color: var(--primary-color);">Admin Panel</h3>
        <a href="index.php">Dashboard</a>
        <a href="users.php">Kelola Pengguna</a>
        <a href="categories.php">Kelola Kategori</a>
        <a href="ideas.php" class="active">Kelola Ide</a>
        <a href="comments.php">Kelola Komentar</a>
    </div>
    
    <div class="admin-content">
        <h2 class="mb-2">Kelola Ide Mahasiswa</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Ide berhasil dihapus.</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Ide berhasil diupdate.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Ide</th>
                        <th>Kategori</th>
                        <th>Pembuat</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ideas as $ide): ?>
                    <tr>
                        <td><?php echo $ide['id_ide']; ?></td>
                        <td>
                            <a href="../ide_detail.php?id=<?php echo $ide['id_ide']; ?>" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                                <?php echo htmlspecialchars($ide['judul_ide']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($ide['nama_kategori']); ?></td>
                        <td><?php echo htmlspecialchars($ide['nama']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($ide['tanggal_upload'])); ?></td>
                        <td>
                            <div class="flex gap-1">
                                <a href="../edit_ide.php?id=<?php echo $ide['id_ide']; ?>" style="color: #f59e0b; text-decoration: none; font-size: 0.9rem;">Edit</a>
                                <a href="ideas.php?delete=<?php echo $ide['id_ide']; ?>" class="btn-delete" style="color: var(--danger); text-decoration: none; font-size: 0.9rem;">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

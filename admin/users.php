<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id != $_COOKIE['user_id']) { // Prevent self-delete
        $stmt = $pdo->prepare("DELETE FROM bim_users WHERE id_user = ?");
        $stmt->execute([$id]);
        redirect('users.php?msg=deleted');
    }
}

$stmt = $pdo->query("SELECT * FROM bim_users ORDER BY role ASC, nama ASC");
$users = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem; color: var(--primary-color);">Admin Panel</h3>
        <a href="index.php">Dashboard</a>
        <a href="users.php" class="active">Kelola Pengguna</a>
        <a href="categories.php">Kelola Kategori</a>
        <a href="ideas.php">Kelola Ide</a>
        <a href="comments.php">Kelola Komentar</a>
    </div>
    
    <div class="admin-content">
        <h2 class="mb-2">Kelola Pengguna</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Pengguna berhasil dihapus.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id_user']; ?></td>
                        <td><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="card-category" style="background: <?php echo $user['role'] == 'admin' ? '#fef08a' : '#e0e7ff'; ?>; color: <?php echo $user['role'] == 'admin' ? '#854d0e' : '#3730a3'; ?>"><?php echo strtoupper($user['role']); ?></span></td>
                        <td>
                            <?php if($user['id_user'] != $_COOKIE['user_id']): ?>
                                <a href="users.php?delete=<?php echo $user['id_user']; ?>" class="btn-delete" style="color: var(--danger); text-decoration: none;">Hapus</a>
                            <?php else: ?>
                                <span class="text-muted">Anda (Admin)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

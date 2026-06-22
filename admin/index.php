<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

// Get Statistics
$stats = [];
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM bim_users")->fetchColumn();
$stats['ideas'] = $pdo->query("SELECT COUNT(*) FROM bim_ide")->fetchColumn();
$stats['categories'] = $pdo->query("SELECT COUNT(*) FROM bim_kategori")->fetchColumn();
$stats['comments'] = $pdo->query("SELECT COUNT(*) FROM bim_komentar")->fetchColumn();

// Recent ideas
$recent_ideas = $pdo->query("
    SELECT i.judul_ide, u.nama, i.tanggal_upload 
    FROM bim_ide i 
    LEFT JOIN bim_users u ON i.id_user = u.id_user 
    ORDER BY i.tanggal_upload DESC LIMIT 5
")->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3 style="margin-bottom: 1.5rem; padding-left: 1rem; color: var(--primary-color);">Admin Panel</h3>
        <a href="index.php" class="active">Dashboard</a>
        <a href="users.php">Kelola Pengguna</a>
        <a href="categories.php">Kelola Kategori</a>
        <a href="ideas.php">Kelola Ide</a>
        <a href="comments.php">Kelola Komentar</a>
    </div>
    
    <div class="admin-content">
        <h2 class="mb-2">Dashboard Statistik</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['ideas']; ?></div>
                <div class="stat-label">Total Ide</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Total Pengguna</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['categories']; ?></div>
                <div class="stat-label">Total Kategori</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['comments']; ?></div>
                <div class="stat-label">Total Komentar</div>
            </div>
        </div>

        <div class="card mt-2">
            <h3 class="mb-1">Ide Terbaru</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Judul Ide</th>
                            <th>Pembuat</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_ideas as $ide): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ide['judul_ide']); ?></td>
                            <td><?php echo htmlspecialchars($ide['nama']); ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($ide['tanggal_upload'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($recent_ideas)): ?>
                        <tr><td colspan="3" class="text-center">Belum ada ide.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

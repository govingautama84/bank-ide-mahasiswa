<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_COOKIE['user_id'];

// Get user stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total_ide FROM bim_ide WHERE id_user = ?");
$stmt->execute([$user_id]);
$total_ide = $stmt->fetch()['total_ide'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total_komentar FROM bim_komentar WHERE id_user = ?");
$stmt->execute([$user_id]);
$total_komentar = $stmt->fetch()['total_komentar'];

// Get user ideas
$stmt = $pdo->prepare("
    SELECT i.*, k.nama_kategori 
    FROM bim_ide i 
    LEFT JOIN bim_kategori k ON i.id_kategori = k.id_kategori 
    WHERE i.id_user = ? 
    ORDER BY i.tanggal_upload DESC
");
$stmt->execute([$user_id]);
$my_ideas = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container">
        <div class="flex justify-between align-center mb-2">
            <h2>Selamat Datang, <?php echo htmlspecialchars($_COOKIE['nama'] ?? 'Pengguna'); ?>!</h2>
            <a href="create_ide.php" class="btn btn-primary">+ Tambah Ide Baru</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_ide; ?></div>
                <div class="stat-label">Ide Anda</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_komentar; ?></div>
                <div class="stat-label">Komentar Diberikan</div>
            </div>
        </div>

        <h3 class="mb-1">Daftar Ide Saya</h3>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Ide berhasil dihapus.</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">Ide berhasil ditambahkan.</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Ide berhasil diperbarui.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Judul Ide</th>
                        <th>Kategori</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($my_ideas) > 0): ?>
                        <?php foreach ($my_ideas as $ide): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ide['judul_ide']); ?></td>
                                <td><span class="card-category"><?php echo htmlspecialchars($ide['nama_kategori'] ?? '-'); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($ide['tanggal_upload'])); ?></td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="ide_detail.php?id=<?php echo $ide['id_ide']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Lihat</a>
                                        <a href="edit_ide.php?id=<?php echo $ide['id_ide']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-color: #f59e0b; color: #f59e0b;">Edit</a>
                                        <a href="delete_ide.php?id=<?php echo $ide['id_ide']; ?>" class="btn btn-danger btn-delete" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Anda belum menambahkan ide apapun.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

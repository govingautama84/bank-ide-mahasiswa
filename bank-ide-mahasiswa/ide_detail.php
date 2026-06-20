<?php
require_once 'config/database.php';

$id_ide = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Idea Details
$stmt = $pdo->prepare("
    SELECT i.*, u.nama as pembuat, k.nama_kategori 
    FROM ide i 
    LEFT JOIN users u ON i.id_user = u.id_user 
    LEFT JOIN kategori k ON i.id_kategori = k.id_kategori 
    WHERE i.id_ide = ?
");
$stmt->execute([$id_ide]);
$ide = $stmt->fetch();

if (!$ide) {
    redirect('index.php');
}

// Handle Add Comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn() && isset($_POST['isi_komentar'])) {
    $isi_komentar = trim($_POST['isi_komentar']);
    $id_user = $_SESSION['user_id'];
    
    if (!empty($isi_komentar)) {
        $stmt = $pdo->prepare("INSERT INTO komentar (isi_komentar, id_user, id_ide) VALUES (?, ?, ?)");
        $stmt->execute([$isi_komentar, $id_user, $id_ide]);
        // Redirect to avoid form resubmission
        redirect("ide_detail.php?id=$id_ide&msg=comment_added");
    }
}

// Handle Delete Comment (Admin only)
if (isset($_GET['del_comment']) && isAdmin()) {
    $id_komentar = (int)$_GET['del_comment'];
    $stmt = $pdo->prepare("DELETE FROM komentar WHERE id_komentar = ?");
    $stmt->execute([$id_komentar]);
    redirect("ide_detail.php?id=$id_ide&msg=comment_deleted");
}

// Fetch Comments
$stmt = $pdo->prepare("
    SELECT c.*, u.nama as komentator 
    FROM komentar c 
    LEFT JOIN users u ON c.id_user = u.id_user 
    WHERE c.id_ide = ? 
    ORDER BY c.tanggal_komentar ASC
");
$stmt->execute([$id_ide]);
$comments = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container" style="max-width: 900px;">
        <div class="card" style="padding: 2.5rem;">
            <div class="detail-header">
                <span class="card-category"><?php echo htmlspecialchars($ide['nama_kategori'] ?? 'Tanpa Kategori'); ?></span>
                <h1 style="margin-top: 1rem;"><?php echo htmlspecialchars($ide['judul_ide']); ?></h1>
                <div class="detail-meta">
                    <span>👤 Oleh: <?php echo htmlspecialchars($ide['pembuat'] ?? 'Anonim'); ?></span>
                    <span>📅 <?php echo date('d M Y, H:i', strtotime($ide['tanggal_upload'])); ?></span>
                </div>
            </div>
            
            <div class="detail-body" style="white-space: pre-line; line-height: 1.8; color: var(--text-main); font-size: 1.05rem;">
                <?php echo htmlspecialchars($ide['deskripsi']); ?>
            </div>
        </div>

        <div class="comments-section">
            <h3 class="mb-2">Komentar (<?php echo count($comments); ?>)</h3>
            
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'comment_added'): ?>
                <div class="alert alert-success">Komentar berhasil ditambahkan.</div>
            <?php endif; ?>
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'comment_deleted'): ?>
                <div class="alert alert-success">Komentar berhasil dihapus.</div>
            <?php endif; ?>

            <?php if (count($comments) > 0): ?>
                <div class="comments-list mb-2">
                    <?php foreach ($comments as $c): ?>
                        <div class="comment">
                            <div class="flex justify-between">
                                <div class="comment-meta">
                                    <?php echo htmlspecialchars($c['komentator'] ?? 'Anonim'); ?>
                                    <span style="color: var(--text-muted); font-weight: normal; font-size: 0.8rem; margin-left: 0.5rem;">
                                        <?php echo date('d M Y, H:i', strtotime($c['tanggal_komentar'])); ?>
                                    </span>
                                </div>
                                <?php if(isAdmin()): ?>
                                    <a href="?id=<?php echo $id_ide; ?>&del_comment=<?php echo $c['id_komentar']; ?>" class="btn-delete" style="color: var(--danger); font-size: 0.8rem; text-decoration: none;">Hapus</a>
                                <?php endif; ?>
                            </div>
                            <div class="comment-content" style="color: var(--text-main);">
                                <?php echo nl2br(htmlspecialchars($c['isi_komentar'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-2">Belum ada komentar.</p>
            <?php endif; ?>

            <?php if (isLoggedIn()): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Tambahkan Komentar</label>
                        <textarea name="isi_komentar" class="form-control" rows="3" required placeholder="Tulis komentar Anda di sini..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                </form>
            <?php else: ?>
                <div class="alert" style="background: var(--secondary-color); border: 1px solid var(--border-color);">
                    Silakan <a href="login.php" style="color: var(--primary-color);">Login</a> untuk menambahkan komentar.
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

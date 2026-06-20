<?php
require_once 'config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori_id = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

$query = "SELECT i.*, u.nama as pembuat, k.nama_kategori 
          FROM ide i 
          LEFT JOIN users u ON i.id_user = u.id_user 
          LEFT JOIN kategori k ON i.id_kategori = k.id_kategori 
          WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND i.judul_ide LIKE ?";
    $params[] = "%$search%";
}

if ($kategori_id > 0) {
    $query .= " AND i.id_kategori = ?";
    $params[] = $kategori_id;
}

$query .= " ORDER BY i.tanggal_upload DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$ideas = $stmt->fetchAll();

$stmt_kat = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$categories = $stmt_kat->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="hero">
    <h1>Jelajahi Ide Kreatif Mahasiswa</h1>
    <p>Temukan inovasi, penelitian, dan ide bisnis dari mahasiswa lain. Bagikan ide Anda dan mulai kolaborasi hari ini!</p>
    <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 0.8rem 1.5rem;">Mulai Bagikan Ide</a>
    <?php endif; ?>
</div>

<main class="main-content">
    <div class="container">
        
        <form method="GET" action="" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Cari judul ide..." value="<?php echo htmlspecialchars($search); ?>" style="flex-grow: 1;">
            <select name="kategori" class="form-control" style="width: 200px;">
                <option value="0">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id_kategori']; ?>" <?php echo $kategori_id == $cat['id_kategori'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if($search || $kategori_id): ?>
                <a href="index.php" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>

        <div class="ideas-grid">
            <?php if (count($ideas) > 0): ?>
                <?php foreach ($ideas as $ide): ?>
                    <div class="card">
                        <div class="card-category"><?php echo htmlspecialchars($ide['nama_kategori'] ?? 'Tanpa Kategori'); ?></div>
                        <h3 class="card-title"><?php echo htmlspecialchars($ide['judul_ide']); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars(substr($ide['deskripsi'], 0, 150)) . '...'; ?></p>
                        <div class="card-footer">
                            <span>Oleh: <?php echo htmlspecialchars($ide['pembuat'] ?? 'Anonim'); ?></span>
                            <a href="ide_detail.php?id=<?php echo $ide['id_ide']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Lihat Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">
                    <h3>Tidak ada ide yang ditemukan.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<nav class="navbar">
    <div class="container">
        <a href="/index.php" class="navbar-brand">
            💡 Bank Ide Mahasiswa
        </a>
        <ul class="navbar-nav">
            <li><a href="/index.php">Beranda</a></li>
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="/admin/index.php">Admin Panel</a></li>
                <?php else: ?>
                    <li><a href="/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="/profile.php">Profil</a></li>
                <li><a href="/logout.php" class="btn btn-outline" style="padding: 0.4rem 1rem;">Logout</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/register.php" class="btn btn-primary" style="color: white;">Daftar</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

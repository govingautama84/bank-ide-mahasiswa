<nav class="navbar">
    <div class="container">
        <a href="/bank-ide-mahasiswa/index.php" class="navbar-brand">
            💡 Bank Ide Mahasiswa
        </a>
        <ul class="navbar-nav">
            <li><a href="/bank-ide-mahasiswa/index.php">Beranda</a></li>
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="/bank-ide-mahasiswa/admin/index.php">Admin Panel</a></li>
                <?php else: ?>
                    <li><a href="/bank-ide-mahasiswa/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="/bank-ide-mahasiswa/profile.php">Profil</a></li>
                <li><a href="/bank-ide-mahasiswa/logout.php" class="btn btn-outline" style="padding: 0.4rem 1rem;">Logout</a></li>
            <?php else: ?>
                <li><a href="/bank-ide-mahasiswa/login.php">Login</a></li>
                <li><a href="/bank-ide-mahasiswa/register.php" class="btn btn-primary" style="color: white;">Daftar</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

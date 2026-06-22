<?php
require_once 'config/database.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/index.php' : 'dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM bim_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Vercel Serverless Compatible Login (Cookies instead of PHP Sessions)
        setcookie('user_id', $user['id_user'], time() + 86400 * 30, '/');
        setcookie('nama', $user['nama'], time() + 86400 * 30, '/');
        setcookie('role', $user['role'], time() + 86400 * 30, '/');

        redirect($user['role'] === 'admin' ? 'admin/index.php' : 'dashboard.php');
    } else {
        $error = 'Email atau password salah.';
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <h2 class="text-center mb-2">Login ke Akun Anda</h2>
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            <p class="text-center mt-1" style="font-size: 0.9rem;">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

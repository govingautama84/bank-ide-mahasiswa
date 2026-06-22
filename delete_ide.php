<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$id_ide = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_COOKIE['user_id'];
$is_admin = isAdmin();

// Check if ide exists and belongs to user or is admin
$stmt = $pdo->prepare("SELECT id_user FROM bim_ide WHERE id_ide = ?");
$stmt->execute([$id_ide]);
$ide = $stmt->fetch();

if ($ide && ($is_admin || $ide['id_user'] == $user_id)) {
    $stmt = $pdo->prepare("DELETE FROM bim_ide WHERE id_ide = ?");
    $stmt->execute([$id_ide]);
}

redirect($is_admin ? "admin/ideas.php?msg=deleted" : "dashboard.php?msg=deleted");
?>

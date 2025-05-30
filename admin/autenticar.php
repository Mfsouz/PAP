<?php
session_start();
include '../bd/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pag/login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT is_admin FROM utilizadores WHERE id_utilizador = ?");
$stmt->execute([$_SESSION['user_id']]);
$isAdmin = $stmt->fetchColumn();

if ($isAdmin != 1) {
    header("Location: ../index.php");
    exit();
}
?>

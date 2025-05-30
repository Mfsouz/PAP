<?php
session_start();
include '../bd/dbcon.php';

// Se o utilizador não estiver autenticado, redireciona para login
if (!isset($_SESSION['id_utilizador'])) {
    header("Location: ../pag/login.php");
    exit();
}

// Verifica se o utilizador é admin
$stmt = $pdo->prepare("SELECT is_admin FROM utilizador WHERE id_utilizador = ?");
$stmt->execute([$_SESSION['id_utilizador']]);
$isAdmin = $stmt->fetchColumn();

// Se não for admin, redireciona para o site público
if ($isAdmin != 1) {
    header("Location: ../index.php");
    exit();
}
?>
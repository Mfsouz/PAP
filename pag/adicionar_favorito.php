<?php
session_start();
require 'conexao.php'; // substitui pelo nome do teu ficheiro de conexão

if (!isset($_SESSION['id_utilizador']) || !isset($_POST['id_produto'])) {
    http_response_code(401);
    echo "Não autorizado.";
    exit;
}

$id_utilizador = $_SESSION['id_utilizador'];
$id_produto = (int) $_POST['id_produto'];

// Verifica se já está nos favoritos
$stmt = $pdo->prepare("SELECT * FROM utilizador_favorito WHERE id_utilizador = ? AND id_produto = ?");
$stmt->execute([$id_utilizador, $id_produto]);

if ($stmt->rowCount() == 0) {
    $insert = $pdo->prepare("INSERT INTO utilizador_favorito (id_utilizador, id_produto) VALUES (?, ?)");
    $insert->execute([$id_utilizador, $id_produto]);
    echo "adicionado";
} else {
    // Remover se já existir
    $delete = $pdo->prepare("DELETE FROM utilizador_favorito WHERE id_utilizador = ? AND id_produto = ?");
    $delete->execute([$id_utilizador, $id_produto]);
    echo "removido";
}

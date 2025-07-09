<?php
ob_start(); // <- Isto evita o erro de header enviado
require_once './bd/dbcon.php'; // ou o caminho correto para a tua ligação PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = trim($_POST['comentario'] ?? '');
    $id_utilizador = $_SESSION['id_utilizador'] ?? null;
    $id_produto = $_POST['id_produto'] ?? null;

    if ($id_utilizador && $id_produto && !empty($comentario)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO utilizador_comentario (id_utilizador, id_produto, comentario, data) VALUES (:id_utilizador, :id_produto, :comentario, NOW())");

            $stmt->bindParam(':id_utilizador', $id_utilizador, PDO::PARAM_INT);
            $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
            $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: ?page=produto&id=$id_produto#comentarios");
                exit();
            } else {
                $_SESSION['erro_comentario'] = "Erro ao guardar o comentário.";
            }
        } catch (PDOException $e) {
            $_SESSION['erro_comentario'] = "Erro de base de dados: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro_comentario'] = "Comentário inválido.";
    }

    header("Location: ?page=produto&id=$id_produto#comentarios");
    exit();
} else {
    header("Location: ?page=home");
    exit();
}

ob_end_flush(); // <- Envia o conteúdo só no final

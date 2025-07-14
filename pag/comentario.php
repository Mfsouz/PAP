<?php

require_once __DIR__ . '/../bd/dbcon.php';

// Verifica se o método é POST (acesso via formulário)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=home-form&error=Acesso inválido à página de comentários');
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: index.php?page=login-form&error=Por favor inicie sessão para comentar');
    exit;
}

// Valida dados obrigatórios
if (!isset($_POST['comentario'], $_POST['id_produto'])) {
    header('Location: index.php?page=home-form&error=Dados de comentário incompletos');
    exit;
}

$comentario = trim($_POST['comentario']);
$id_utilizador = (int) $_SESSION['id_utilizador'];
$id_produto = (int) $_POST['id_produto'];

// Validações adicionais
if (empty($comentario)) {
    header("Location: index.php?page=detalhes_jogos_form&id=$id_produto&error=O comentário não pode estar vazio");
    exit;
}

if (strlen($comentario) > 500) {
    header("Location: index.php?page=detalhes_jogos_form&id=$id_produto&error=O comentário não pode exceder 500 caracteres");
    exit;
}

try {
    // Insere o novo comentário
    $stmt = $pdo->prepare("INSERT INTO utilizador_comentario (id_utilizador, id_produto, comentario, data) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$id_utilizador, $id_produto, $comentario]);

    // Verifica se precisa inserir também na tabela de avaliações (se não existir)
    $stmt = $pdo->prepare("SELECT 1 FROM utilizador_avaliacao WHERE id_utilizador = ? AND id_produto = ?");
    $stmt->execute([$id_utilizador, $id_produto]);

    if ($stmt->rowCount() === 0) {
        // Insere avaliação padrão (3 estrelas) se não existir
        $stmt = $pdo->prepare("INSERT INTO utilizador_avaliacao (id_utilizador, id_produto, avaliacao, data) VALUES (?, ?, 3, NOW())");
        $stmt->execute([$id_utilizador, $id_produto]);
    }

    // Redireciona com mensagem de sucesso
    header("Location: index.php?page=detalhes_jogos_form&id=$id_produto&success=Comentário adicionado com sucesso");
    exit;

} catch (PDOException $e) {
    // Log do erro (em produção, usar um sistema de logs adequado)
    error_log("Erro ao processar comentário: " . $e->getMessage());

    // Redireciona com mensagem de erro
    header("Location: index.php?page=detalhes_jogos_form&id=$id_produto&error=" . urlencode('Erro ao processar comentário. Por favor, tente novamente.'));
    exit;
}
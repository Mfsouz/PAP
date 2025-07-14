<?php
require_once __DIR__ . '/../bd/dbcon.php';

// Verifica se o acesso é via POST (formulário)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<script>window.location.href = "index.php?page=home-form&error=Acesso inválido à página de avaliação";</script>';
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_utilizador'])) {
    echo '<script>window.location.href = "index.php?page=login-form&error=Por favor inicie sessão para avaliar";</script>';
    exit;
}

// Verifica dados obrigatórios
if (!isset($_POST['avaliacao'], $_POST['id_utilizador'], $_POST['id_produto'])) {
    echo '<script>window.location.href = "index.php?page=home-form&error=Dados de avaliação incompletos";</script>';
    exit;
}

// Valida os dados
$avaliacao = (int) $_POST['avaliacao'];
$id_utilizador = (int) $_POST['id_utilizador'];
$id_produto = (int) $_POST['id_produto'];

if ($avaliacao < 1 || $avaliacao > 5) {
    echo '<script>window.location.href = "index.php?page=detalhes_jogos_form&id=' . $id_produto . '&error=Avaliação deve ser entre 1 e 5 estrelas";</script>';
    exit;
}

if ($id_utilizador !== (int) $_SESSION['id_utilizador']) {
    echo '<script>window.location.href = "index.php?page=home-form&error=ID de utilizador inválido";</script>';
    exit;
}

try {
    // Verifica se já existe avaliação deste usuário para este produto
    $stmt = $pdo->prepare("SELECT * FROM utilizador_avaliacao WHERE id_utilizador = ? AND id_produto = ?");
    $stmt->execute([$id_utilizador, $id_produto]);

    if ($stmt->rowCount() > 0) {
        // Atualiza avaliação existente
        $stmt = $pdo->prepare("UPDATE utilizador_avaliacao SET avaliacao = ?, data = NOW() WHERE id_utilizador = ? AND id_produto = ?");
        $stmt->execute([$avaliacao, $id_utilizador, $id_produto]);
        $mensagem = "Avaliação atualizada com sucesso!";
    } else {
        // Insere nova avaliação
        $stmt = $pdo->prepare("INSERT INTO utilizador_avaliacao (id_utilizador, id_produto, avaliacao, data) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$id_utilizador, $id_produto, $avaliacao]);
        $mensagem = "Avaliação registrada com sucesso!";
    }

    // Redireciona de volta para a página do jogo com mensagem de sucesso
    echo '<script>window.location.href = "index.php?page=detalhes_jogos_form&id=' . $id_produto . '&success=' . urlencode($mensagem) . '";</script>';
    exit;

} catch (PDOException $e) {
    // Log do erro (em produção, usar um sistema de logs adequado)
    error_log("Erro ao processar avaliação: " . $e->getMessage());

    // Redireciona com mensagem de erro
    echo '<script>window.location.href = "index.php?page=detalhes_jogos_form&id=' . $id_produto . '&error=' . urlencode('Erro ao processar avaliação. Por favor, tente novamente.') . '";</script>';
    exit;
}
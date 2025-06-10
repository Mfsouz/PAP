<?php
include './dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_utilizador = trim($_POST['nome_utilizador'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome_utilizador) || empty($senha)) {
        $_SESSION['error'] = "Preencha todos os campos.";
        header("Location: ?page=login-form");
        exit();
    }

    // Hash da senha com md5 (confirme que é assim no banco)
    $senha_hash = md5($senha);

    try {
        $stmt = $pdo->prepare("SELECT * FROM utilizador WHERE nome_utilizador = :usuario AND senha = :senha LIMIT 1");
        $stmt->execute([
            ':usuario' => $nome_utilizador,
            ':senha' => $senha_hash
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['id_utilizador'] = $user['id_utilizador'];
            $_SESSION['nome_utilizador'] = $user['nome_utilizador'];
            header("Location: ?page=home-form");
            exit();
        } else {
            $_SESSION['error'] = "Utilizador ou senha inválidos.";
            header("Location: ?page=login-form");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro na base de dados: " . $e->getMessage();
        header("Location: ?page=login-form");
        exit();
    }
} else {
    header("Location: ?page=login-form");
    exit();
}

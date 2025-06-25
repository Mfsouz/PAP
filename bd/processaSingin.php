<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $nome_utilizador = trim($_POST['nome_utilizador']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senha_confirm = $_POST['senha_confirm'];

    if (empty($nome_utilizador) || empty($email) || empty($senha) || empty($senha_confirm)) {
        $_SESSION['error'] = "Por favor, preencha todos os campos.";
        header("Location: ../pag/criarConta.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "E-mail inválido.";
        header("Location: ../pag/criarConta.php");
        exit();
    }

    if ($senha !== $senha_confirm) {
        $_SESSION['error'] = "As senhas não coincidem.";
        header("Location: ../pag/criarConta.php");
        exit();
    }

    // Verificar se o utilizador ou email já existe
    $sql = "SELECT * FROM utilizador WHERE nome_utilizador = :nome_utilizador OR email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome_utilizador' => $nome_utilizador, 'email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['error'] = "Nome de utilizador ou e-mail já existe.";
        header("Location: ../pag/criarConta.php");
        exit();
    }

    $senha_hash = md5($senha);

    // Inserir na base de dados
    $sql = "INSERT INTO utilizador (nome, nome_utilizador, email, senha) VALUES (:nome, :nome_utilizador, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'nome' => $nome,
        'nome_utilizador' => $nome_utilizador,
        'email' => $email,
        'senha' => $senha_hash
    ]);

    if ($result) {
        $_SESSION['success'] = "Conta criada com sucesso! Faça login.";
        header("Location: ../pag/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Erro ao criar conta. Tente novamente.";
        header("Location: ../pag/criarConta.php");
        exit();
    }
} else {
    header("Location: ../pag/criarConta.php");
    exit();
}
?>

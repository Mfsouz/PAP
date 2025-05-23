<?php
session_start();
include '../bd/dbcon.php'; // aqui deve conter sua variável $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_utilizador = trim($_POST['nome_utilizador'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome_utilizador) || empty($senha)) {
        $_SESSION['error'] = "Preencha todos os campos.";
        header("Location: ../pag/login.php");
        exit();
    }

    // Hash da senha com md5 (se é assim que está salvo no banco)
    $senha_hash = md5($senha);

    try {
        // Consulta para verificar usuário e senha
        $stmt = $pdo->prepare("SELECT * FROM utilizador WHERE nome_utilizador = :usuario AND senha = :senha LIMIT 1");
        $stmt->execute([
            ':usuario' => $nome_utilizador,
            ':senha' => $senha_hash
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Login OK, armazenar dados na sessão
            $_SESSION['user_id'] = $user['id']; // por exemplo, id do usuário
            $_SESSION['nome_utilizador'] = $user['nome_utilizador'];
            header("Location: ../index.php"); // redireciona para home ou dashboard
            exit();
        } else {
            $_SESSION['error'] = "Utilizador ou senha inválidos.";
            header("Location: ../pag/login.php");
            exit();
        }
    } catch (PDOException $e) {
        // Erro no banco
        $_SESSION['error'] = "Erro na base de dados: " . $e->getMessage();
        header("Location: ../pag/login.php");
        exit();
    }
} else {
    header("Location: ../pag/login.php");
    exit();
}
?>

<?php
// Iniciar sessão
session_start();

// Verificar se o formulário foi submetido corretamente
if (isset($_POST['nome_utilizador']) && isset($_POST['senha'])) {
    require "dbcon.php"; // Conexão com o banco de dados

    try {
        // Capturar e limpar os dados do formulário
        $nome_utilizador = trim($_POST['nome_utilizador']);
        $senha = sha1($_POST['senha']); // Aplicar SHA1 para criptografar a senha

        // Query SQL para verificar o utilizador
        $sql = "SELECT * FROM utilizador WHERE nome_utilizador = :nome_utilizador AND senha = :senha";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_utilizador', $nome_utilizador, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar se encontrou um utilizador válido
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['nome_utilizador'] = $row['nome_utilizador']; // Armazena o nome na sessão
            
            header("Location: ../bd/proecssaLogin.php");
            exit();
        } else {
            $_SESSION['erro_login'] = "Utilizador ou senha inválidos!";
        }
    } catch (PDOException $e) {
        $_SESSION['erro_login'] = "Erro no login: " . $e->getMessage();
    }
} else {
    $_SESSION['erro_login'] = "Preencha todos os campos!";
}

// Redirecionar para a página de login
header("Location: ../pag/login.php");
exit();
?>
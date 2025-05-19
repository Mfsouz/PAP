<?php
session_start();

if (!empty($_POST['nome_utilizador']) && !empty($_POST['senha'])) {
    require "dbcon.php"; // Aqui você deve garantir que $pdo esteja criado

    $username = $_POST['nome_utilizador'];
    $password = md5($_POST['senha']); // Hash MD5, mas recomendo usar password_hash no futuro

    try {
        $stmt = $pdo->prepare("SELECT * FROM utilizador WHERE nome_utilizador = :username AND senha = :password");
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['nome_utilizador'] = $user;
        } else {
            $_SESSION['erro_login'] = "Utilizador ou Password inválidos!";
        }
    } catch (PDOException $e) {
        // Pode registar o erro se quiser
        $_SESSION['erro_login'] = "Erro na base de dados.";
    }
} else {
    $_SESSION['erro_login'] = "Os dados do utilizador e password devem ser preenchidos!";
}

header("Location:../");
exit;
?>

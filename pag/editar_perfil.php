<?php
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: ?page=login-form');
    exit;
}

$id = $_SESSION['id_utilizador'];

// Obter dados do utilizador
$stmt = $pdo->prepare("SELECT nome, email, senha FROM utilizador WHERE id_utilizador = ?");
$stmt->execute([$id]);
$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilizador) {
    echo '<div class="alert alert-danger">Utilizador não encontrado.</div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);

    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    $pode_atualizar = true;

    // Se o utilizador preencheu senha_atual, valida-a antes de qualquer alteração
    if (!empty($senha_atual) || !empty($nova_senha) || !empty($confirmar_senha)) {
        if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
            echo '<div class="alert alert-warning">Preencha todos os campos de senha para alterar.</div>';
            $pode_atualizar = false;
        } elseif (md5($senha_atual) !== $utilizador['senha']) {
            echo '<div class="alert alert-danger">A senha atual está incorreta.</div>';
            $pode_atualizar = false;
        } elseif (strlen($nova_senha) < 6) {
            echo '<div class="alert alert-warning">A nova senha deve ter pelo menos 6 caracteres.</div>';
            $pode_atualizar = false;
        } elseif ($nova_senha !== $confirmar_senha) {
            echo '<div class="alert alert-danger">As senhas novas não coincidem.</div>';
            $pode_atualizar = false;
        } else {
            // Atualizar senha
            $nova_senha_md5 = md5($nova_senha);
            $stmt_update_pass = $pdo->prepare("UPDATE utilizador SET senha = ? WHERE id_utilizador = ?");
            if ($stmt_update_pass->execute([$nova_senha_md5, $id])) {
                echo '<div class="alert alert-success">Senha alterada com sucesso.</div>';
            } else {
                echo '<div class="alert alert-danger">Erro ao atualizar a senha.</div>';
                $pode_atualizar = false;
            }
        }
    }

    // Atualização de nome e email (só se a senha estiver correta ou não for necessária)
    if ($pode_atualizar && !empty($novo_nome) && !empty($novo_email)) {
        $check_email = $pdo->prepare("SELECT id_utilizador FROM utilizador WHERE email = ? AND id_utilizador != ?");
        $check_email->execute([$novo_email, $id]);

        if ($check_email->rowCount() > 0) {
            echo '<div class="alert alert-danger">Este e-mail já está em uso por outro utilizador.</div>';
        } else {
            $update = $pdo->prepare("UPDATE utilizador SET nome = ?, email = ? WHERE id_utilizador = ?");
            if ($update->execute([$novo_nome, $novo_email, $id])) {
                echo '<div class="alert alert-success">Perfil atualizado com sucesso.</div>';
                $utilizador['nome'] = $novo_nome;
                $utilizador['email'] = $novo_email;
            } else {
                echo '<div class="alert alert-danger">Erro ao atualizar perfil.</div>';
            }
        }
    } elseif ($pode_atualizar) {
        echo '<div class="alert alert-warning">Preencha todos os campos obrigatórios.</div>';
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Meu Perfil</h2>
    <form method="POST" class="w-50 mx-auto bg-light p-4 rounded shadow">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome"
                value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= htmlspecialchars($utilizador['email']) ?>" required>
        </div>

        <hr>
        <h5 class="mt-4">Alterar Senha</h5>
        <div class="mb-3">
            <label for="senha_atual" class="form-label">Senha Atual</label>
            <input type="password" class="form-control" id="senha_atual" name="senha_atual">
        </div>
        <div class="mb-3">
            <label for="nova_senha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="nova_senha" name="nova_senha">
        </div>
        <div class="mb-3">
            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
        </div>

        <button type="submit" class="btn btn-primary w-100">Guardar Alterações</button>
    </form>
</div>
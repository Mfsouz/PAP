<?php
if (!isset($_SESSION['id_utilizador'])) {
    header('Location: ?page=login-form');
    exit;
}

$id = $_SESSION['id_utilizador'];

// Obter dados do utilizador
$stmt = $pdo->prepare("SELECT nome, email FROM utilizador WHERE id_utilizador = ?");
$stmt->execute([$id]);
$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilizador) {
    echo '<div class="alert alert-danger">Utilizador não encontrado.</div>';
    exit;
}

// Atualização de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);

    if (!empty($novo_nome) && !empty($novo_email)) {
        $update = $pdo->prepare("UPDATE utilizador SET nome = ?, email = ? WHERE id_utilizador = ?");
        if ($update->execute([$novo_nome, $novo_email, $id])) {
            echo '<div class="alert alert-success">Perfil atualizado com sucesso.</div>';
            $utilizador['nome'] = $novo_nome;
            $utilizador['email'] = $novo_email;
        } else {
            echo '<div class="alert alert-danger">Erro ao atualizar perfil.</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Preencha todos os campos.</div>';
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Meu Perfil</h2>
    <form method="POST" class="w-50 mx-auto bg-light p-4 rounded shadow">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilizador['email']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Guardar Alterações</button>
    </form>
</div>

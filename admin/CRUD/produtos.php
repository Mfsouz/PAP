<?php
$action = $_GET['action'] ?? 'list';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function redirectToList()
{
    header("Location: ?page=admin-produtos-form");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_produto'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if (empty($nome) || empty($descricao)) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios.";
        header("Location: ?page=admin-produtos-form&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ? WHERE id_produto = ?");
            $stmt->execute([$nome, $descricao, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao) VALUES (?, ?)");
            $stmt->execute([$nome, $descricao]);
        }
        redirectToList();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro ao salvar produto: " . $e->getMessage();
        header("Location: admin-produtos-form&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erro ao excluir produto: " . $e->getMessage();
        }
    }
    redirectToList();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($action === 'new' || $action === 'edit'):
            $id = $_GET['id'] ?? null;
            $nome = $descricao = "";
            $editando = false;

            if ($action === 'edit') {
                $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
                $stmt->execute([$id]);
                $produto = $stmt->fetch();
                if ($produto) {
                    $nome = $produto['nome'];
                    $descricao = $produto['descricao'];
                    $editando = true;
                } else {
                    echo "<div class='alert alert-danger'>Produto não encontrado.</div>";
                    exit;
                }
            }
        ?>

            <h2><?= $editando ? "Editar Produto" : "Novo Produto" ?></h2>
            <form method="post" action="?page=admin-produtos-form">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_produto" value="<?= htmlspecialchars($id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea id="descricao" name="descricao" class="form-control"
                        required><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary"><?= $editando ? "Atualizar" : "Criar" ?></button>
                <a href="?page=admin-produtos-form" class="btn btn-secondary">Cancelar</a>
            </form>

        <?php else:
            try {
                $stmt = $pdo->query("SELECT id_produto, nome, descricao FROM produtos ORDER BY id_produto DESC");
                $produtos = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erro ao buscar produtos: " . $e->getMessage() . "</div>";
                $produtos = [];
            }
        ?>

            <h2>Produtos</h2>
            <a href="?page=admin-produtos-form&action=new" class="btn btn-success mb-3">Novo Produto</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td>
                                <a href="?page=admin-produtos-form&action=edit&id=<?= $produto['id_produto'] ?>"
                                    class="btn btn-primary btn-sm">Editar</a>
                                <a href="?page=admin-produtos-form&id=<?= $produto['id_produto'] ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir?');"
                                    class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($produtos)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Nenhum produto cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>
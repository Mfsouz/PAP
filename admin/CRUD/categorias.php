<?php
$action = $_GET['action'] ?? 'list';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function redirectToList()
{
    header("Location: ./categorias.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_categoria'] ?? null;
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        $_SESSION['error'] = "Preencha o campo obrigatório.";
        header("Location: ./categorias.php&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE categorias SET nome_categoria = ? WHERE id_categoria = ?");
            $stmt->execute([$nome, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO categorias (nome_categoria) VALUES (?)");
            $stmt->execute([$nome]);
        }
        redirectToList();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro ao salvar categoria: " . $e->getMessage();
        header("Location: ./categorias.php&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM categorias WHERE id_categoria = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erro ao excluir categoria: " . $e->getMessage();
        }
    }
    redirectToList();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gestão de Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($action === 'new' || $action === 'edit'):
            $id = $_GET['id'] ?? null;
            $nome = "";
            $editando = false;

            if ($action === 'edit') {
                $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
                $stmt->execute([$id]);
                $categoria = $stmt->fetch();
                if ($categoria) {
                    $nome = $categoria['nome_categoria'];
                    $editando = true;
                } else {
                    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
                    exit;
                }
            }
        ?>

            <h2><?= $editando ? "Editar Categoria" : "Nova Categoria" ?></h2>
            <form method="post" action="?page=admin-categorias-form">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_categoria" value="<?= htmlspecialchars($id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary"><?= $editando ? "Atualizar" : "Criar" ?></button>
                <a href="?page=admin-categorias-form" class="btn btn-secondary">Cancelar</a>
            </form>

        <?php else:
            try {
                $stmt = $pdo->query("SELECT id_categoria, nome_categoria FROM categorias ORDER BY id_categoria DESC");
                $categorias = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erro ao buscar categorias: " . $e->getMessage() . "</div>";
                $categorias = [];
            }
        ?>

            <h2>Categorias</h2>
            <a href="?page=admin-categorias-form&action=new" class="btn btn-success mb-3">Nova Categoria</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($categoria['nome_categoria']) ?></td>
                            <td>
                                <a href="?page=admin-categorias-form&action=edit&id=<?= $categoria['id_categoria'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="?page=admin-categorias-form&action=delete&id=<?= $categoria['id_categoria'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="2" class="text-center">Nenhuma Categoria Cadastrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>
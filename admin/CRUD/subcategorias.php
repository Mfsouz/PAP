<?php
$action = $_GET['action'] ?? 'list';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function redirectToList()
{
    header("Location: admin-subcategorias-form");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_subcategoria'] ?? null;
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios.";
        header("Location: admin-subcategorias-form&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE subcategorias SET nome_subcategoria = ? WHERE id_subcategoria = ?");
            $stmt->execute([$nome, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO subcategorias (nome_subcategoria) VALUES (?)");
            $stmt->execute([$nome]);
        }
        redirectToList();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro ao salvar subcategoria: " . $e->getMessage();
        header("Location: admin-subcategorias-form&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM subcategorias WHERE id_subcategoria = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erro ao excluir subcategoria: " . $e->getMessage();
        }
    }
    redirectToList();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gestão de Subcategorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($action === 'new' || $action === 'edit'):
            $id = $_GET['id'] ?? null;
            $editando = false;

            if ($action === 'edit') {
                $stmt = $pdo->prepare("SELECT * FROM subcategorias WHERE id_subcategoria = ?");
                $stmt->execute([$id]);
                $subcategoria = $stmt->fetch();
                if ($subcategoria) {
                    $nome = $subcategoria['nome_subcategoria'];
                    $editando = true;
                } else {
                    echo "<div class='alert alert-danger'>Subcategoria não encontrada.</div>";
                    exit;
                }
            }
        ?>

            <h2><?= $editando ? "Editar Subcategoria" : "Nova Subcategoria" ?></h2>
            <form method="post" action="admin-subcategorias-form">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_subcategoria" value="<?= htmlspecialchars($id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome ?? '') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary"><?= $editando ? "Atualizar" : "Criar" ?></button>
                <a href="admin-subcategorias-form" class="btn btn-secondary">Cancelar</a>
            </form>

        <?php else:
            try {
                $stmt = $pdo->query("SELECT id_subcategoria, nome_subcategoria FROM subcategorias ORDER BY id_subcategoria DESC");
                $subcategorias = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erro ao buscar subcategorias: " . $e->getMessage() . "</div>";
                $subcategorias = [];
            }
        ?>

            <h2>Subcategorias</h2>
            <a href="admin-subcategorias-form&action=new" class="btn btn-success mb-3">Nova Subcategoria</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subcategorias as $subcategoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($subcategoria['nome_subcategoria']) ?></td>
                            <td>
                                <a href="admin-subcategorias-form&action=edit&id=<?= $subcategoria['id_subcategoria'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="admin-subcategorias-form&action=delete&id=<?= $subcategoria['id_subcategoria'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($subcategorias)): ?>
                        <tr>
                            <td colspan="2" class="text-center">Nenhuma Subcategoria Cadastrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>
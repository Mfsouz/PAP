<?php
$action = $_GET['action'] ?? 'list';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function redirectToList()
{
    header("Location: ./criadoras.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_criadora'] ?? null;
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        $_SESSION['error'] = "Preencha o campo obrigatório.";
        header("Location: ./criadoras.php&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE criadoras SET nome_criadora = ? WHERE id_criadora = ?");
            $stmt->execute([$nome, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO criadoras (nome_criadora) VALUES (?)");
            $stmt->execute([$nome]);
        }
        redirectToList();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro ao salvar criadora: " . $e->getMessage();
        header("Location: ./criadoras.php&action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM criadoras WHERE id_criadora = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erro ao excluir criadora: " . $e->getMessage();
        }
    }
    redirectToList();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gestão de criadoras</title>
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
                $stmt = $pdo->prepare("SELECT * FROM criadoras WHERE id_criadora = ?");
                $stmt->execute([$id]);
                $criadora = $stmt->fetch();
                if ($criadora) {
                    $nome = $criadora['nome_criadora'];
                    $editando = true;
                } else {
                    echo "<div class='alert alert-danger'>criadora não encontrada.</div>";
                    exit;
                }
            }
        ?>

            <h2><?= $editando ? "Editar criadora" : "Nova criadora" ?></h2>
            <form method="post" action="?page=admin-criadoras-form">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_criadora" value="<?= htmlspecialchars($id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary"><?= $editando ? "Atualizar" : "Criar" ?></button>
                <a href="?page=admin-criadoras-form" class="btn btn-secondary">Cancelar</a>
            </form>

        <?php else:
            try {
                $stmt = $pdo->query("SELECT id_criadora, nome_criadora FROM criadoras ORDER BY id_criadora DESC");
                $criadoras = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erro ao buscar criadoras: " . $e->getMessage() . "</div>";
                $criadoras = [];
            }
        ?>

            <h2>criadoras</h2>
            <a href="?page=admin-criadoras-form&action=new" class="btn btn-success mb-3">Nova criadora</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($criadoras as $criadora): ?>
                        <tr>
                            <td><?= htmlspecialchars($criadora['nome_criadora']) ?></td>
                            <td>
                                <a href="?page=admin-criadoras-form&action=edit&id=<?= $criadora['id_criadora'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="?page=admin-criadoras-form&action=delete&id=<?= $criadora['id_criadora'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($criadoras)): ?>
                        <tr>
                            <td colspan="2" class="text-center">Nenhuma criadora Cadastrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>
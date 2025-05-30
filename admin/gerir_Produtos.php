<?php
include './autenticar.php';
include '../includes/header.php';

$action = $_GET['action'] ?? 'list';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function redirectToList() {
    header("Location: utilizadores.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SALVAR (create ou update)
    $id = $_POST['id_utilizador'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($nome) || empty($email) || (!$id && empty($senha))) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios.";
        header("Location: utilizadores.php?action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }

    $senha_hash = $senha ? md5($senha) : null;

    try {
        if ($id) {
            // Update
            if ($senha_hash) {
                $stmt = $pdo->prepare("UPDATE utilizador SET nome = ?, email = ?, senha = ?, is_admin = ? WHERE id_utilizador = ?");
                $stmt->execute([$nome, $email, $senha_hash, $is_admin, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE utilizador SET nome = ?, email = ?, is_admin = ? WHERE id_utilizador = ?");
                $stmt->execute([$nome, $email, $is_admin, $id]);
            }
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO utilizador (nome, email, senha, is_admin) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash, $is_admin]);
        }
        redirectToList();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro ao salvar utilizador: " . $e->getMessage();
        header("Location: utilizadores.php?action=" . ($id ? "edit&id=$id" : "new"));
        exit();
    }
}

if ($action === 'delete') {
    // EXCLUIR
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM utilizador WHERE id_utilizador = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erro ao excluir utilizador: " . $e->getMessage();
        }
    }
    redirectToList();
}

?>

<div class="container mt-4">

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($action === 'new' || $action === 'edit'):

    $id = $_GET['id'] ?? null;
    $nome = $email = "";
    $is_admin = 0;
    $editando = false;

    if ($action === 'edit') {
        $stmt = $pdo->prepare("SELECT * FROM utilizador WHERE id_utilizador = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $nome = $user['nome'];
            $email = $user['email'];
            $is_admin = $user['is_admin'];
            $editando = true;
        } else {
            echo "<div class='alert alert-danger'>Utilizador não encontrado.</div>";
            include '../includes/footer.php';
            exit;
        }
    }
    ?>

    <h2><?= $editando ? "Editar Utilizador" : "Novo Utilizador" ?></h2>
    <form method="post" action="utilizadores.php">
        <?php if ($editando): ?>
            <input type="hidden" name="id_utilizador" value="<?= $id ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label"><?= $editando ? "Nova Senha (deixe em branco para manter)" : "Senha:" ?></label>
            <input type="password" id="senha" name="senha" class="form-control" <?= $editando ? "" : "required" ?>>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" id="is_admin" name="is_admin" value="1" class="form-check-input" <?= $is_admin ? "checked" : "" ?>>
            <label for="is_admin" class="form-check-label">Administrador</label>
        </div>

        <button type="submit" class="btn btn-primary"><?= $editando ? "Atualizar" : "Criar" ?></button>
        <a href="utilizadores.php" class="btn btn-secondary">Cancelar</a>
    </form>

<?php else: 
    // LISTAR UTILIZADORES
    try {
        $stmt = $pdo->query("SELECT id_utilizador, nome, email, is_admin FROM utilizador");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao buscar utilizadores: " . $e->getMessage() . "</div>";
        $usuarios = [];
    }
    ?>

    <h2>Utilizadores</h2>
    <a href="utilizadores.php?action=new" class="btn btn-success mb-3">Novo Utilizador</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['nome']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Sim' : 'Não' ?></td>
                    <td>
                        <a href="utilizadores.php?action=edit&id=<?= $user['id_utilizador'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="utilizadores.php?action=delete&id=<?= $user['id_utilizador'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');" class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>

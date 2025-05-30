<?php
include 'autenticar.php';
include 'header.php';

try {
    $stmt = $pdo->query("SELECT id_utilizador, nome, email, is_admin FROM utilizadores");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao buscar utilizadores.</div>";
}
?>

<h2>Utilizadores</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Admin</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $user): ?>
            <tr>
                <td><?= $user['id_utilizador'] ?></td>
                <td><?= htmlspecialchars($user['nome']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['is_admin'] ? 'Sim' : 'Não' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
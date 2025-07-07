<?php
if (!isset($_SESSION['id_utilizador'])) {
    header("Location: ?page=login-form");
    exit;
}

$id = $_SESSION['id_utilizador'];

// Buscar dados do utilizador
$stmt = $pdo->prepare("SELECT * FROM utilizador WHERE id_utilizador = :id");
$stmt->execute(['id' => $id]);
$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilizador) {
    echo "<div class='alert alert-danger'>Utilizador não encontrado.</div>";
    exit;
}

// Buscar favoritos
$stmt_fav = $pdo->prepare("
    SELECT p.*, pi.link_imagem
    FROM utilizador_favorito uf
    JOIN produtos p ON uf.id_produto = p.id_produto
    JOIN produto_imagem pi ON p.imagem_fk = pi.id_imagem
    WHERE uf.id_utilizador = :id
");
$stmt_fav->execute(['id' => $id]);
$favoritos = $stmt_fav->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="text-center mb-4"><i class="bi bi-person-circle"></i> Perfil do Utilizador</h2>

    <div class="row">
        <div class="col-md-4 text-center">
            <?php if (!empty($utilizador['avatar']) && file_exists('./img/Avatares/' . $utilizador['avatar'])): ?>
                <img src="./img/Avatares/<?= htmlspecialchars($utilizador['avatar']) ?>" class="img-fluid rounded-circle mb-3" width="150" alt="Avatar">
            <?php else: ?>
                <img src="./img/no-avatar.png" class="img-fluid rounded-circle mb-3" width="150" alt="Sem avatar">
            <?php endif; ?>

            <h4><?= htmlspecialchars($utilizador['nome']) ?></h4>
            <p><?= htmlspecialchars($utilizador['email']) ?></p>

            <a href="?page=editar-perfil    " class="btn btn-outline-primary btn-sm">Editar Perfil</a>
            <a href="?page=logout" class="btn btn-outline-danger btn-sm ms-2">Logout</a>
        </div>

        <div class="col-md-8">
            <h5><i class="bi bi-heart-fill text-danger"></i> Meus Favoritos</h5>

            <?php if (empty($favoritos)): ?>
                <p class="text-muted">Ainda não adicionaste jogos aos favoritos.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($favoritos as $jogo): ?>
                        <div class="col-sm-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($jogo['link_imagem']) && file_exists('./img/Games/' . $jogo['link_imagem'])): ?>
                                    <img src="./img/Games/<?= htmlspecialchars($jogo['link_imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($jogo['nome']) ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" class="card-img-top" alt="Sem imagem">
                                <?php endif; ?>
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?= htmlspecialchars($jogo['nome']) ?></h6>
                                    <a href="?page=detalhes_jogos_form&id=<?= $jogo['id_produto'] ?>" class="btn btn-sm btn-primary">Ver Jogo</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

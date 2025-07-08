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
    SELECT p.*
    FROM utilizador_favorito uf
    JOIN produtos p ON uf.id_produto = p.id_produto
    WHERE uf.id_utilizador = :id
");
$stmt_fav->execute(['id' => $id]);
$favoritos = $stmt_fav->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .card.h-100 {
        display: flex;
        flex-direction: column;
        height: 350px;
        /* aumenta a altura total do card */
    }

    .card-img-top {
        object-fit: cover;
        height: 180px;
        /* Altura fixa para as imagens */
        width: 100%;
    }

    .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1rem;
    }

    .card-body h6.card-title {
        margin-bottom: 1rem;
        flex-grow: 1;
    }
</style>

<div class="container my-5">
    <h2 class="text-center mb-4"><i class="bi bi-person-circle"></i> Perfil do Utilizador</h2>

    <div class="row">
        <div class="col-md-4 text-center">
            <h4><?= htmlspecialchars($utilizador['nome']) ?></h4>
            <p><?= htmlspecialchars($utilizador['email']) ?></p>

            <a href="?page=editar-perfil" class="btn btn-outline-primary btn-sm">Editar Perfil</a>
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
                                <?php if (!empty($jogo['imagem_path']) && file_exists('./img/Games/' . $jogo['imagem_path'])): ?>
                                    <img src="./img/Games/<?= htmlspecialchars($jogo['imagem_path']) ?>" class="card-img-top"
                                        alt="<?= htmlspecialchars($jogo['nome']) ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" class="card-img-top" alt="Sem imagem">
                                <?php endif; ?>
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?= htmlspecialchars($jogo['nome']) ?></h6>
                                    <a href="?page=detalhes_jogos_form&id=<?= $jogo['id_produto'] ?>"
                                        class="btn btn-sm btn-primary">Ver Jogo</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
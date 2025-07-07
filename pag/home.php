<?php
$utilizador_logado = isset($_SESSION['id_utilizador']);
$id_utilizador = $_SESSION['id_utilizador'] ?? null;

$subcat = isset($_GET['sc']) ? (int) $_GET['sc'] : 0;
$termo_pesquisa = $_GET['pesquisa'] ?? '';
$params = [];

$query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
            FROM produtos
            INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk
            WHERE 1 = 1";

if (!empty($termo_pesquisa)) {
    $query .= " AND produtos.nome LIKE :pesquisa";
    $params['pesquisa'] = '%' . $termo_pesquisa . '%';
}

if ($subcat !== 0) {
    $query .= " AND produtos.subcategoria_fk = :subcat";
    $params['subcat'] = $subcat;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar favoritos do utilizador logado (para marcar botões)
$favoritos_ids = [];
if ($utilizador_logado) {
    $stmt_fav = $pdo->prepare("SELECT id_produto FROM utilizador_favorito WHERE id_utilizador = ?");
    $stmt_fav->execute([$id_utilizador]);
    $favoritos_ids = array_column($stmt_fav->fetchAll(PDO::FETCH_ASSOC), 'id_produto');
}

$query_novos = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
                    FROM produtos
                    INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk
                    WHERE produtos.data_pub >= DATE_SUB(NOW(), INTERVAL 5 DAY)";
$stmt_novos = $pdo->prepare($query_novos);
$stmt_novos->execute();
$produtos_novos = $stmt_novos->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="games-wrapper">
    <!-- Pesquisa -->
    <form method="GET" class="d-flex justify-content-center mb-4">
        <input type="hidden" name="page" value="home-form">
        <?php if ($subcat !== 0): ?>
            <input type="hidden" name="sc" value="<?= $subcat ?>">
        <?php endif; ?>
        <input type="text" name="pesquisa" class="form-control w-50 me-2" placeholder="Pesquisar jogo por nome..."
            value="<?= htmlspecialchars($termo_pesquisa) ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <!-- Catálogo -->
    <h2 class="text-center mb-4"><i class="bi bi-controller"></i> Catálogo</h2>
    <div class="row">
        <?php if (empty($produtos)): ?>
            <div class="alert alert-warning text-center">Nenhum jogo encontrado com esse nome.</div>
        <?php endif; ?>

        <?php foreach ($produtos as $index => $produto): ?>
            <?php
            $classe_oculta = $index >= 6 ? ' d-none mais-produtos' : '';
            $ja_favorito = in_array($produto['id_produto'], $favoritos_ids);
            ?>
            <div class="col-md-4<?= $classe_oculta; ?>">
                <div class="game-card">
                    <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                        <img src="./img/Games/<?= htmlspecialchars($produto['link_imagem']); ?>"
                            alt="<?= htmlspecialchars($produto['nome']); ?>">
                    <?php else: ?>
                        <img src="./img/no-image.png" alt="Imagem não disponível">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($produto['nome']); ?></h3>
                    <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                        class="btn btn-primary w-100 mt-2">Ver Jogo</a>

                    <?php if ($utilizador_logado): ?>
                        <button class="btn <?= $ja_favorito ? 'btn-danger' : 'btn-outline-danger' ?> w-100 mt-2 btn-favorito"
                            data-id="<?= $produto['id_produto'] ?>">
                            <i class="bi <?= $ja_favorito ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                            <?= $ja_favorito ? 'Remover Favorito' : 'Adicionar aos Favoritos' ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($produtos) > 6): ?>
        <div class="text-center mt-4">
            <button id="mostrarMaisBtn" class="btn btn-outline-primary">Mostrar Mais</button>
            <button id="mostrarMenosBtn" class="btn btn-outline-primary d-none">Mostrar Menos</button>
        </div>
    <?php endif; ?>

    <!-- Promoções -->
    <?php
    $produtos_com_desconto = array_filter($produtos, fn($p) => !empty($p['desconto']) && $p['desconto'] > 0);
    if (count($produtos_com_desconto) > 0): ?>
        <h2 class="text-center my-5"><i class="bi bi-fire text-danger"></i> Promoções</h2>
        <div class="row">
            <?php foreach ($produtos_com_desconto as $produto): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                            <img src="./img/Games/<?= htmlspecialchars($produto['link_imagem']); ?>"
                                alt="<?= htmlspecialchars($produto['nome']); ?>">
                        <?php else: ?>
                            <img src="./img/no-image.png" alt="Imagem não disponível">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($produto['nome']); ?></h3>
                        <p class="text-danger">-<?= (int) $produto['desconto']; ?>% OFF</p>
                        <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                            class="btn btn-primary w-100 mt-2">Ver jogo</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Novos Jogos -->
    <?php if (count($produtos_novos) > 0): ?>
        <h2 class="text-center my-5"><i class="bi bi-stars text-success"></i> Novos Jogos</h2>
        <div class="novos-jogos-container">
            <div class="novos-jogos-track">
                <?php $produtos_para_rolar = array_merge($produtos_novos, $produtos_novos); ?>
                <?php foreach ($produtos_para_rolar as $produto): ?>
                    <div class="col-md-4">
                        <div class="game-card">
                            <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                                <img src="./img/Games/<?= htmlspecialchars($produto['link_imagem']); ?>"
                                    alt="<?= htmlspecialchars($produto['nome']); ?>">
                            <?php else: ?>
                                <img src="./img/no-image.png" alt="Imagem não disponível">
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($produto['nome']); ?></h3>
                            <span class="badge bg-success">Novidade!</span>
                            <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                                class="btn btn-primary w-100 mt-2">Ver produto</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btnMais = document.getElementById('mostrarMaisBtn');
            const btnMenos = document.getElementById('mostrarMenosBtn');
            const maisProdutos = document.querySelectorAll('.mais-produtos');

            if (btnMais && btnMenos) {
                btnMais.addEventListener('click', function () {
                    maisProdutos.forEach(el => el.classList.remove('d-none'));
                    btnMais.classList.add('d-none');
                    btnMenos.classList.remove('d-none');
                });

                btnMenos.addEventListener('click', function () {
                    maisProdutos.forEach(el => el.classList.add('d-none'));
                    btnMais.classList.remove('d-none');
                    btnMenos.classList.add('d-none');
                    document.querySelector('.games-wrapper h2').scrollIntoView({ behavior: 'smooth' });
                });
            }

            // AJAX Favoritos
            document.querySelectorAll('.btn-favorito').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const id = this.dataset.id;
                    const self = this;

                    fetch('./pag/adicionar_favorito.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_produto=${id}`
                    })
                        .then(response => response.text())
                        .then(data => {
                            console.log('Resposta do servidor:', data);
                            if (data === "adicionado") {
                                self.classList.remove('btn-outline-danger');
                                self.classList.add('btn-danger');
                                self.innerHTML = '<i class="bi bi-heart-fill"></i> Remover Favorito';
                            } else if (data === "removido") {
                                self.classList.remove('btn-danger');
                                self.classList.add('btn-outline-danger');
                                self.innerHTML = '<i class="bi bi-heart"></i> Adicionar aos Favoritos';
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao atualizar favorito:', error);
                        });
                });
            });
        });
    </script>

</div>
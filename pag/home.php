<?php

$utilizador_logado = isset($_SESSION['id_utilizador']);

$subcat = 0;
if (isset($_GET['sc'])) {
    $subcat = (int) $_GET['sc'];
}

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

$query_novos = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
                FROM produtos
                INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk
                WHERE produtos.data_pub >= DATE_SUB(NOW(), INTERVAL 5 DAY)";
$stmt_novos = $pdo->prepare($query_novos);
$stmt_novos->execute();
$produtos_novos = $stmt_novos->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="games-wrapper">

    <!-- Formulário de Pesquisa -->
    <form method="GET" class="d-flex justify-content-center mb-4">
        <input type="hidden" name="page" value="home-form">
        <?php if ($subcat !== 0): ?>
            <input type="hidden" name="sc" value="<?= $subcat ?>">
        <?php endif; ?>
        <input type="text" name="pesquisa" class="form-control w-50 me-2" placeholder="Pesquisar jogo por nome..." value="<?= htmlspecialchars($termo_pesquisa) ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <!-- Catálogo Completo -->
    <h2 class="text-center mb-4">
        <i class="bi bi-controller"></i> Catálogo
    </h2>
    <div class="row">
        <?php if (empty($produtos)): ?>
            <div class="alert alert-warning text-center">Nenhum jogo encontrado com esse nome.</div>
        <?php endif; ?>

        <?php foreach ($produtos as $index => $produto): ?>
            <?php $classe_oculta = $index >= 6 ? ' d-none mais-produtos' : ''; ?>
            <div class="col-md-4<?= $classe_oculta; ?>">
                <div class="game-card">
                    <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                        <img src="./img/Games/<?= htmlspecialchars($produto['link_imagem']); ?>"
                            alt="<?= htmlspecialchars($produto['nome']); ?>">
                    <?php else: ?>
                        <img src="./img/no-image.png" alt="Imagem não disponível">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($produto['nome']); ?></h3>
                    <?php if ($utilizador_logado): ?>
                        <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                            class="btn btn-primary w-100 mt-2">Comprar</a>
                    <?php else: ?>
                        <a href="?page=login-form" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
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
    <h2 class="text-center my-5"><i class="bi bi-fire text-danger"></i> Promoções</h2>
    <div class="row">
        <?php foreach ($produtos as $produto): ?>
            <?php if (!empty($produto['desconto']) && $produto['desconto'] > 0): ?>
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
                        <?php if ($utilizador_logado): ?>
                            <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                                class="btn btn-primary w-100 mt-2">Comprar</a>
                        <?php else: ?>
                            <a href="?page=login-form" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h2 class="text-center my-5"><i class="bi bi-stars text-success"></i> Novos Jogos</h2>
    <div class="novos-jogos-container">
        <div class="novos-jogos-track">
            <?php
            // Duplicar os produtos para o loop ser contínuo
            $produtos_para_rolar = array_merge($produtos_novos, $produtos_novos);
            ?>
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
                        <?php if ($utilizador_logado): ?>
                            <a href="?page=detalhes_jogos_form&id=<?= $produto['id_produto']; ?>"
                                class="btn btn-primary w-100 mt-2">Comprar</a>
                        <?php else: ?>
                            <a href="?page=login-form" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnMais = document.getElementById('mostrarMaisBtn');
            const btnMenos = document.getElementById('mostrarMenosBtn');
            const maisProdutos = document.querySelectorAll('.mais-produtos');

            if (btnMais && btnMenos) {
                btnMais.addEventListener('click', function() {
                    maisProdutos.forEach(el => el.classList.remove('d-none'));
                    btnMais.classList.add('d-none');
                    btnMenos.classList.remove('d-none');
                });

                btnMenos.addEventListener('click', function() {
                    maisProdutos.forEach(el => el.classList.add('d-none'));
                    btnMais.classList.remove('d-none');
                    btnMenos.classList.add('d-none');
                    document.querySelector('.games-wrapper h2').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            }

            // Favoritos
            document.querySelectorAll('.fav-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const produtoId = this.getAttribute('data-produto-id');
                    const isFavorito = this.getAttribute('aria-pressed') === 'true';
                    const action = isFavorito ? 'remover' : 'adicionar';

                    try {
                        const response = await fetch('./favorito.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                produto_id: produtoId,
                                acao: action
                            })
                        });
                        const data = await response.json();

                        if (data.success) {
                            this.setAttribute('aria-pressed', action === 'adicionar' ? 'true' : 'false');
                            this.textContent = action === 'adicionar' ? '❤️' : '🤍';
                        } else {
                            alert('Erro: ' + (data.message || 'Não foi possível atualizar favorito.'));
                        }
                    } catch (error) {
                        alert('Erro de rede. Tente novamente.');
                    }
                });
            });
        });
    </script>
</div>
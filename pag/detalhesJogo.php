<?php
// Verificação inicial do ID do jogo
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?page=home-form&error=Jogo não encontrado');
    exit;
}

$jogoId = (int) $_GET['id'];

// Buscar informações do jogo
try {
    $stmt = $pdo->prepare("SELECT produtos.*, subcategorias.nome_subcategoria, criadoras.nome_criadora, produtos.imagem_path
                          FROM produtos, subcategorias, criadoras
                          WHERE produtos.subcategoria_fk = subcategorias.id_subcategoria
                          AND produtos.criadora_fk = criadoras.id_criadora
                          AND id_produto = ?");
    $stmt->execute([$jogoId]);
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jogo) {
        header('Location: index.php?page=home-form&error=Jogo não encontrado');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar jogo: " . $e->getMessage());
}

// Buscar avaliações do jogo
$avaliacoes = [];
try {
    $stmt = $pdo->prepare("
        SELECT utilizador_avaliacao.avaliacao, utilizador_comentario.comentario, utilizador_avaliacao.data, utilizador_avaliacao.id_utilizador, utilizador.nome_utilizador
        FROM utilizador_avaliacao, utilizador, utilizador_comentario
        WHERE utilizador_avaliacao.id_utilizador = utilizador_comentario.id_utilizador
        AND utilizador_avaliacao.id_produto = utilizador_comentario.id_produto
        AND utilizador_avaliacao.id_produto = ?
        ORDER BY utilizador_avaliacao.data DESC
        LIMIT 5
    ");
    $stmt->execute([$jogoId]);
    $avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erroAvaliacoes = "Não foi possível carregar as avaliações";
}

// Calcular média das avaliações
$mediaAvaliacoes = 0;
if (!empty($avaliacoes)) {
    $soma = 0;
    foreach ($avaliacoes as $av) {
        $soma += $av['avaliacao'];
    }
    $mediaAvaliacoes = round($soma / count($avaliacoes), 1);
}

// Verificar se o usuário logado já avaliou este jogo
$avaliacao_usuario = null;
if (isset($_SESSION['id_utilizador'])) {
    try {
        $stmt = $pdo->prepare("SELECT avaliacao FROM utilizador_avaliacao WHERE id_utilizador = ? AND id_produto = ?");
        $stmt->execute([$_SESSION['id_utilizador'], $jogoId]);
        $avaliacao_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Silenciar erro, não é crítico
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($jogo['nome']) ?> - Detalhes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .jogo-header {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        .jogo-capa {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .jogo-meta {
            margin: 20px 0;
        }
        .avaliacao-media {
            color: #ffc107;
            font-weight: bold;
        }
        .price-comparison {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        .price-platform {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .platform-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .platform-logo {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }
        .platform-name {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .price-name {
            flex: 2;
        }
        .price-value {
            flex: 1;
            text-align: right;
            font-weight: bold;
            color: #28a745;
            padding-right: 15px;
        }
        .price-link {
            flex: 1;
            text-align: right;
        }
        .rating-stars {
            color: #ffc107;
        }
        .avaliacao-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .comentario-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Mensagens de status -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Botão Voltar -->
        <a href="index.php?page=home-form" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-left"></i> Voltar para lista
        </a>

        <!-- Cabeçalho com informações principais -->
        <div class="jogo-header">
            <div class="row">
                <div class="col-md-4">
                    <img src="./img/Games/<?= htmlspecialchars($jogo['imagem_path']) ?>"
                        alt="<?= htmlspecialchars($jogo['nome']) ?>" class="jogo-capa">
                </div>
                <div class="col-md-8">
                    <h1 class="mb-3"><?= htmlspecialchars($jogo['nome']) ?></h1>

                    <div class="jogo-meta d-flex flex-wrap gap-3 align-items-center">
                        <?php if ($mediaAvaliacoes > 0): ?>
                            <div class="avaliacao-media">
                                <i class="fas fa-star"></i> <?= $mediaAvaliacoes ?>/5
                                <small class="text-muted">(<?= count($avaliacoes) ?> avaliações)</small>
                            </div>
                        <?php endif; ?>

                        <div class="text-muted">
                            <i class="far fa-calendar-alt"></i>
                            <?= date('d/m/Y', strtotime($jogo['data_pub'])) ?>
                        </div>

                        <div class="text-primary">
                            <i class="fas fa-user-tie"></i> <?= htmlspecialchars($jogo['nome_criadora']) ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4>Sobre o jogo</h4>
                        <p class="lead"><?= nl2br(htmlspecialchars($jogo['descricao'])) ?></p>
                    </div>

                    <div class="mt-4">
                        <h5>Gêneros</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $generos = explode(',', $jogo['nome_subcategoria']);
                            foreach ($generos as $genero):
                                if (trim($genero)): ?>
                                    <span class="badge bg-primary"><?= htmlspecialchars(trim($genero)) ?></span>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Preços por Loja -->
        <div class="price-comparison">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">
                    <i class="fas fa-tags"></i> Preços nas Lojas
                </h3>
                <small class="text-muted" id="price-update-time"></small>
            </div>

            <div id="price-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-3">Buscando preços nas lojas...</p>
            </div>

            <div id="price-results"></div>
        </div>

        <!-- Avaliação por estrelas -->
        <?php if ($utilizador_logado): ?>
            <form method="post" action="index.php?page=avaliacao" class="mt-4">
                <div class="form-group mb-2">
                    <label for="avaliacao">Dê sua nota:</label>
                    <div id="avaliacao" class="rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="estrela<?= $i ?>" name="avaliacao" value="<?= $i ?>" 
                                <?= ($avaliacao_usuario && $avaliacao_usuario['avaliacao'] == $i) ? 'checked' : '' ?> required>
                            <label for="estrela<?= $i ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                <input type="hidden" name="id_utilizador" value="<?= $_SESSION['id_utilizador'] ?>">
                <input type="hidden" name="id_produto" value="<?= $jogoId ?>">
                <button type="submit" class="btn btn-warning"><i class="fas fa-star"></i> Avaliar</button>
            </form>

            <style>
                .rating {
                    direction: rtl;
                    unicode-bidi: bidi-override;
                    font-size: 2rem;
                }
                .rating input {
                    display: none;
                }
                .rating label {
                    color: #ddd;
                    cursor: pointer;
                }
                .rating input:checked ~ label,
                .rating label:hover,
                .rating label:hover ~ label {
                    color: #ffc107;
                }
            </style>
        <?php else: ?>
            <div class="alert alert-secondary mt-4">
                <i class="fas fa-lock"></i> Para avaliar, por favor <a href="index.php?page=login-form">inicie sessão</a>.
            </div>
        <?php endif; ?>

        <!-- Seção de Comentários -->
        <div class="bg-white p-4 rounded shadow-sm">
            <h3 class="mb-4">
                <i class="fas fa-comments"></i> Comentários
            </h3>

            <?php if (!empty($avaliacoes)): ?>
                <div class="comentarios-lista">
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                        <div class="comentario-item mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= htmlspecialchars($avaliacao['nome_utilizador']) ?></strong>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $avaliacao['avaliacao']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($avaliacao['data'])) ?></small>
                            </div>
                            <p class="mt-2"><?= nl2br(htmlspecialchars($avaliacao['comentario'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (isset($erroAvaliacoes)): ?>
                <div class="alert alert-warning"><?= $erroAvaliacoes ?></div>
            <?php else: ?>
                <div class="alert alert-info">Este jogo ainda não possui comentários.</div>
            <?php endif; ?>

            <!-- Formulário de novo comentário -->
            <?php if ($utilizador_logado): ?>
                <div class="mt-4">
                    <h5>Escreva um comentário</h5>
                    <form method="post" action="index.php?page=comentario">
                        <div class="form-group">
                            <textarea name="comentario" class="form-control" rows="3"
                                placeholder="Escreva aqui o seu comentário..." required></textarea>
                        </div>
                        <input type="hidden" name="id_utilizador" value="<?= $_SESSION['id_utilizador'] ?>">
                        <input type="hidden" name="id_produto" value="<?= $jogoId ?>">
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary mt-4">
                    <i class="fas fa-lock"></i> Para deixar um comentário, por favor <a href="index.php?page=login-form">inicie sessão</a>.
                </div>
            <?php endif; ?>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function () {
                const apiUrl = "./cache/get_Precos.php";
                const gameData = {
                    jogo_nome: "<?= addslashes($jogo['nome']) ?>"
                };

                $.ajax({
                    type: "GET",
                    url: apiUrl,
                    data: gameData,
                    dataType: 'json',
                    success: function (response) {
                        $('#price-loading').hide();

                        // Atualiza horário da consulta
                        const now = new Date();
                        $('#price-update-time').text('Atualizado: ' + now.toLocaleTimeString());

                        if (response.combined && response.combined.length > 0) {
                            renderPrices(response);
                        } else {
                            $('#price-results').html(`
                                <div class="alert alert-warning">
                                    Não encontramos preços para este jogo no momento.
                                </div>
                            `);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#price-loading').hide();
                        $('#price-results').html(`
                            <div class="alert alert-danger">
                                Erro ao buscar preços. <button onclick="location.reload()" class="btn btn-sm btn-outline-danger">Tentar novamente</button>
                            </div>
                        `);
                        console.error("Erro na API:", error);
                    }
                });

                function renderPrices(data) {
                    let html = '';

                    // Garantir que há dados combinados
                    if (data.combined && data.combined.length > 0) {
                        const steamItems = data.combined.filter(item => item.url.includes('steampowered.com'));
                        const epicItems = data.combined.filter(item => item.url.includes('epicgames.com'));

                        // Processar Steam
                        if (steamItems.length > 0) {
                            html += createPlatformSection(
                                'Steam',
                                'https://store.steampowered.com/favicon.ico',
                                steamItems
                            );
                        }

                        // Processar Epic Games com conversão de preço
                        if (epicItems.length > 0) {
                            const convertedEpic = epicItems.map(item => {
                                if (typeof item.price === 'number') {
                                    const euro = (item.price * 0.93).toFixed(2).replace('.', ',');
                                    item.price = `${euro} €`;
                                }
                                return item;
                            });

                            html += createPlatformSection(
                                'Epic Games',
                                'https://assets.streamlinehq.com/image/private/w_300,h_300,ar_1/f_auto/v1/icons/video-games/epic-games-hg3aynrgcuetqn170db1g9.png/epic-games-y5xqpgrdx4l1nft47f5gz7.png?_a=DATAdtAAZAA0',
                                convertedEpic
                            );
                        }

                        $('#price-results').html(html);
                    } else {
                        $('#price-results').html(`
                            <div class="alert alert-warning">
                                Não encontramos preços para este jogo no momento.
                            </div>
                        `);
                    }
                }

                function createPlatformSection(platformName, logoUrl, items) {
                    let sectionHtml = `
                        <div class="price-platform mb-4">
                            <div class="platform-header">
                                <img src="${logoUrl}" alt="${platformName}" class="platform-logo">
                                <span class="platform-name">${platformName}</span>
                            </div>
                    `;

                    items.forEach(item => {
                        sectionHtml += `
                            <div class="price-item">
                                <div class="price-name">${item.name}</div>
                                <div class="price-value">${item.price}</div>
                                <div class="price-link">
                                    <a href="${item.url}" target="_blank" class="btn btn-sm btn-primary">
                                        Ver Jogo <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                    });

                    sectionHtml += `</div>`;
                    return sectionHtml;
                }
            });
        </script>
    </div>
</body>
</html>
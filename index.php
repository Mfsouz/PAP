<?php
session_start();
include './bd/dbcon.php';

$utilizador_logado = isset($_SESSION['user_id']);

$subcat = 0;
if (isset($_GET['sc'])) {
    $subcat = (int) $_GET['sc']; // cast para int para segurança
}

// Query principal: busca todos os produtos (ou filtra por subcategoria)
if ($subcat !== 0) {
    $query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
              FROM produtos
              INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk
              WHERE produtos.subcategoria_fk = :subcat";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['subcat' => $subcat]);
} else {
    $query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
              FROM produtos
              INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$query_novos = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao
                FROM produtos
                INNER JOIN produto_imagem ON produto_imagem.id_imagem = produtos.imagem_fk
                WHERE produtos.data_pub >= DATE_SUB(NOW(), INTERVAL 5 DAY)";
$stmt_novos = $pdo->prepare($query_novos);
$stmt_novos->execute();
$produtos_novos = $stmt_novos->fetchAll(PDO::FETCH_ASSOC);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Jogos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .game-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            /* Aumentei um pouco o padding */
            text-align: center;
            margin: 15px;
            /* margens maiores */
            background-color: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .game-card img {
            width: 100%;
            height: 350px;
            /* Aumentei de 250px para 350px */
            object-fit: cover;
            border-radius: 8px;
        }

        .game-card h3 {
            font-size: 1.8rem;
            /* Aumentei de 1.5rem para 1.8rem */
            margin-top: 15px;
            /* aumentei a margem superior */
            flex-grow: 1;
        }



        .games-wrapper {
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 0px 25px rgb(57, 116, 204);
            padding: 15px;
        }

        .games-wrapper h2 {
            font-size: 3rem !important;
            font-weight: 500;
            text-align: center;
            background: linear-gradient(90deg, #007bff, #ff69b4);
            background-size: 200% 200%;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            animation: gradientFade 2s ease infinite;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
            padding-bottom: 10px;
            overflow: visible;
        }

        @keyframes gradientFade {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body>

    <?php include './includes/header.php'; ?>

    <!-- Seção de Destaque -->
    <header
        style="background: linear-gradient(90deg, #0d6efd, #e83e8c); color: white; text-align: center; padding: 3rem 0;">
        <div class="container">
            <h1>Bem-vindo à Loja de Jogos</h1>
            <p>Encontre os melhores jogos com descontos exclusivos!</p>
        </div>
    </header>

    <section class="container py-5">
        <div class="games-wrapper">

            <!-- Catálogo Completo -->
            <h2 class="text-center mb-4">
                <i class="bi bi-controller"></i> Catálogo
            </h2>
            <div class="row">
                <?php foreach ($produtos as $index => $produto): ?>
                    <?php $classe_oculta = $index >= 6 ? ' d-none mais-produtos' : ''; ?>
                    <div class="col-md-4<?php echo $classe_oculta; ?>">
                        <div class="game-card">
                            <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                                <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <?php else: ?>
                                <img src="./img/no-image.png" alt="Imagem não disponível">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <?php if ($utilizador_logado): ?>
                                <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>"
                                    class="btn btn-primary w-100 mt-2">Comprar</a>
                            <?php else: ?>
                                <a href="./pag/login.php" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
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
                                    <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" alt="Imagem não disponível">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <p class="text-danger">-<?php echo (int) $produto['desconto']; ?>% OFF</p>
                                <?php if ($utilizador_logado): ?>
                                    <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>"
                                        class="btn btn-primary w-100 mt-2">Comprar</a>
                                <?php else: ?>
                                    <a href="./pag/login.php" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
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
                                    <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" alt="Imagem não disponível">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <span class="badge bg-success">Novidade!</span>
                                <?php if ($utilizador_logado): ?>
                                    <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>"
                                        class="btn btn-primary w-100 mt-2">Comprar</a>
                                <?php else: ?>
                                    <a href="./pag/login.php" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include './includes/footer.php'; ?>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
                    // Scroll até o topo do catálogo
                    document.querySelector('.games-wrapper h2').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>


</body>

</html>
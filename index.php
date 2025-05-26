<?php
session_start();
include './bd/dbcon.php';

// Verifica se o utilizador está logado
$utilizador_logado = isset($_SESSION['user_id']);

// Consulta os produtos
$query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao 
          FROM produtos, produto_imagem
          WHERE produto_imagem.id_produto = produtos.id_produto;";
$stmt = $pdo->query($query);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Jogos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .game-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin: 15px;
            background-color: #fff;
            height: 97%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .game-card img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .game-card h3 {
            font-size: 1.5rem;
            margin-top: 10px;
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
            font-size: 5rem;
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
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>

<?php include './includes/header.php'; ?>

<?php if (!$utilizador_logado): ?>
    <div style="background: #ffc107; color: #333; padding: 10px; text-align: center;">
        ⚠️ Faça login para adicionar jogos ao carrinho.
    </div>
<?php endif; ?>

<!-- Seção de Destaque -->
<header style="background: linear-gradient(90deg, #0d6efd, #e83e8c); color: white; text-align: center; padding: 3rem 0;">
    <div class="container">
        <h1>Bem-vindo à Loja de Jogos</h1>
        <p>Encontre os melhores jogos com descontos exclusivos!</p>
    </div>
</header>

<section id="games" class="container py-5">
    <div class="games-wrapper">
        <h2 class="text-center mb-4">Catálogo</h2>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                            <img src="./img/Games/<?php echo $produto['link_imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                        <?php else: ?>
                            <img src="./img/no-image.png" alt="Imagem não disponível">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>

                        <?php if ($utilizador_logado): ?>
                            <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-primary w-100 mt-2">Comprar</a>
                        <?php else: ?>
                            <a href="./pag/login.php" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include './includes/footer.php'; ?>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include './bd/dbcon.php';
include './includes/header.php';

// Consultando os jogos do banco de dados
$query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao 
          FROM produtos, produto_imagem
          WHERE produto_imagem.id_produto = produtos.id_produto;";
$stmt = $pdo->query($query);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">
                <img src="./img/logo.png" alt="8Bit" height="40" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#games">Jogos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Carrinho</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pag/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Seção de Destaque (Banner) -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Bem-vindo à Loja de Jogos</h1>
            <p>Encontre os melhores jogos com descontos exclusivos!</p>
        </div>
    </header>

    <!-- Seção de Jogos -->
    <section id="games" class="container py-5">
        <h2 class="text-center mb-4">Jogos</h2>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                            <img src="./img/Games/<?php echo $produto['link_imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                        <?php else: ?>
                            <img src="./img/no-image.png" alt="Imagem não disponível">
                        <?php endif; ?>
                        <h3><?php echo $produto['nome']; ?></h3>
                        <button class="btn btn-buy">Comprar Agora</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include './includes/footer.php'; ?>

    <!-- Modal de Contacto -->
    <div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="./pag/contacto.php" method="POST"> 
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalContactoLabel">Fale Conosco</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="4" placeholder="Escreva sua mensagem..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
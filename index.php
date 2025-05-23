<?php
include './bd/dbcon.php';

// Consultando os jogos do banco de dados
$query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao 
          FROM produtos, produto_imagem
          WHERE produto_imagem.id_produto = produtos.id_produto;";
$stmt = $pdo->query($query);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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

    .game-card .btn-buy {
        margin-top: 10px;
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .game-card .btn-buy:hover {
        background-color: #0056b3;
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




    .footer {
        background-color: #333;
        color: white;
        padding: 20px;
    }

    .modal-footer {
        display: flex;
        flex-shrink: 0;
        padding: calc(var(--bs-modal-padding) - var(--bs-modal-footer-gap) * .5);
        background-color: var(--bs-modal-footer-bg);
        border-top: var(--bs-modal-footer-border-width) solid #007fff;
        border-bottom-right-radius: var(--bs-modal-inner-border-radius);
        border-bottom-left-radius: var(--bs-modal-inner-border-radius);
        justify-content: center;
        align-items: flex-start;
        align-content: flex-end;
        flex-direction: row;
    }

    .modal {
        --bs-modal-zindex: 1055;
        --bs-modal-width: 500px;
        --bs-modal-padding: 1rem;
        --bs-modal-margin: 0.5rem;
        --bs-modal-color: ;
        --bs-modal-bg: #fff;
        --bs-modal-border-color: var(--bs-border-color-translucent);
        --bs-modal-border-width: 1px;
        --bs-modal-border-radius: 0.5rem;
        --bs-modal-box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --bs-modal-inner-border-radius: calc(0.5rem - 1px);
        --bs-modal-header-padding-x: 1rem;
        --bs-modal-header-padding-y: 1rem;
        --bs-modal-header-padding: 1rem 1rem;
        --bs-modal-header-border-color: #007fff;
        --bs-modal-header-border-width: 1px;
        --bs-modal-title-line-height: 1.5;
        --bs-modal-footer-gap: 0.5rem;
        --bs-modal-footer-bg: ;
        --bs-modal-footer-border-color: var(--bs-border-color);
        --bs-modal-footer-border-width: 1px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: var(--bs-modal-zindex);
        display: none;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
    }
</style>

<body>
    <?php include './includes/header.php'; ?>

    <!-- Seção de Destaque (Banner) -->
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
                            <h3><?php echo $produto['nome']; ?></h3>
                            <button class="btn btn-buy">Comprar Agora</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
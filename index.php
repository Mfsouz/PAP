<?php
include './bd/dbcon.php';

// Consultando os jogos do banco de dados
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>8Bit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
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
            height: 450px;
            /* Define altura fixa */
            object-fit: cover;
            /* Corta e ajusta proporcionalmente */
            border-radius: 8px;
        }

        .game-card h3 {
            font-size: 1.5rem;
            margin-top: 10px;
            flex-grow: 1;
            /* Expande o título para ocupar espaço */
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
</head>

<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">8Bit</a>
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

    <!-- Seção de Contato -->
    <section id="contact" class="container py-5">
        <h2 class="text-center mb-4">Entre em Contato</h2>
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Seu Nome" required />
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" placeholder="Seu E-mail" required />
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="tel" placeholder="Seu Telefone" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Mensagem" rows="4" required></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" type="submit">Enviar Mensagem</button>
            </div>
        </form>
    </section>

    <!-- Modal de Contacto -->
    <div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalContactoLabel">Fale Conosco</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="contato.php" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" placeholder="Seu nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" rows="4" placeholder="Escreva sua mensagem..."
                                required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Rodapé -->
    <footer class="footer text-center py-4">
        <p>&copy; 2025 Loja de Jogos. Todos os direitos reservados12345.</p>
        <div>
            <a href="#" class="text-white me-3">Privacidade</a>
            <a href="#" class="text-white">Termos de Uso</a>
            <a href="#" class="text-white me-2 btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#modalContacto"
                type="button">Contacto</a>

        </div>
    </footer>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
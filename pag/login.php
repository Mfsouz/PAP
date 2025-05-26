<?php
session_start();
include '../bd/dbcon.php';

// Se o utilizador já estiver logado, redireciona para a página principal
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>8Bit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>

<style>
    .custom-card {
        border-radius: 35px;
        box-shadow: 0 0px 25px rgb(57, 116, 204);
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../img/logo.png" alt="8Bit" height="40" class="d-inline-block align-text-top" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php#games">Jogos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../pag/carrinho.php">Carrinho</a></li>
                    <li class="nav-item">
                        <a class="nav-link text-white bg-primary rounded px-3" href="./login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 custom-card" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Iniciar sessão</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="../bd/processaLogin.php" method="post">
                <div class="mb-3">
                    <label for="nome_utilizador" class="form-label">Utilizador:</label>
                    <input type="text" class="form-control" id="nome_utilizador" name="nome_utilizador" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <a href="./criarConta.php" class="btn btn-secondary w-100 mt-2">Criar Conta</a>
        </div>
    </main>

    <div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="./contacto.php" method="POST">
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

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="footer text-center py-4 bg-dark text-white mt-auto">
        <p>&copy; 2025 Loja de Jogos. Todos os direitos reservados.</p>
        <div>
            <a href="../privacidade.php" class="text-white me-3">Privacidade</a>
            <a href="../termos.php" class="text-white me-3">Termos de Uso</a>
            <a class="text-white me-3 btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#modalContacto">Contacto</a>
        </div>
    </footer>
</body>
</html>

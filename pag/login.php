<?php
session_start();
include '../bd/dbcon.php';
include '../includes/header.php';

// Consultar os produtos
$query = "SELECT produtos.*, produto_imagem.link_imagem, produto_imagem.titulo, produto_imagem.descricao 
          FROM produtos
          INNER JOIN produto_imagem ON produto_imagem.id_produto = produtos.id_produto";
$stmt = $pdo->prepare($query);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        background-color: #f8f9fa;
    }

    main {
        flex: 1;
    }

    .game-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        margin: 15px;
        background-color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .game-card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 8px;
    }

    .game-card h3 {
        font-size: 1.2rem;
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

    .login-container {
        max-width: 450px;
        width: 100%;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 53px;
        box-shadow: 0 0px 16px 10px #0d6efd30;
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .footer {
        background-color: #333;
        color: white;
        padding: 20px;
    }
</style>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../img/logo.png" alt="8Bit" height="40" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <main>
        <!-- Login -->
        <section class="container d-flex align-items-center justify-content-center" style="min-height: 100%;">
            <div class="login-container">
                <h2>Iniciar sessão</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <p style="color:red;"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></p>
                <?php endif; ?>
                <form action="check_login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Utilizador:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </section>
    </main>


    <?php include '../includes/footer.php'; ?>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>8Bit</title>
    <link rel="icon" href="./img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/novos_Jogos.css">
</head>

<?php
include './bd/dbcon.php';
?>

<!-- Barra de Navegação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="height: 60px; display: flex; justify-content: flex-start;">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img src="./img/logo.png" alt="8Bit" style="height: 70px; max-height: none; position: relative; top: 0px;" class="d-inline-block align-text-top">
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

                <!-- Dropdown de Categorias -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categorias
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoriasDropdown">
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT id_subcategoria, nome_subcategoria FROM subcategorias");
                            $stmt->execute();
                            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($categorias) > 0) {
                                foreach ($categorias as $row) {
                                    echo '<li><a class="dropdown-item" href="./?sc=' . $row['id_subcategoria'] . '">' . htmlspecialchars($row['nome_subcategoria']) . '</a></li>';
                                }
                            } else {
                                echo '<li><span class="dropdown-item text-muted">Nenhuma categoria</span></li>';
                            }
                        } catch (PDOException $e) {
                            echo '<li><span class="dropdown-item text-danger">Erro ao carregar categorias</span></li>';
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./pag/carrinho.php">Carrinho</a>
                </li>

                <li class="nav-item">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-link text-white rounded px-3" href="./pag/logout.php" style="background-color: #dc3545;">Logout</a>
                    <?php else: ?>
                        <a class="nav-link text-white rounded px-3" href="./pag/login.php" style="background-color: #e83e8c;">Login</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
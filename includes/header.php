<?php

$isAdmin = false;
if (isset($_SESSION['id_utilizador'])) {
    $stmt = $pdo->prepare("SELECT is_admin FROM utilizador WHERE id_utilizador = ?");
    $stmt->execute([$_SESSION['id_utilizador']]);
    $isAdmin = $stmt->fetchColumn() == 1;
}

$subcat = 0;
if (isset($_GET['sc'])) {
    $subcat = (int) $_GET['sc']; // cast para int para segurança
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>8Bit</title>
    <link rel="icon" href="./img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/novos_Jogos.css">
    <link href="./css/style_home.css" rel="stylesheet">
</head>

<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"
        style="height: 60px; display: flex; justify-content: flex-start;">
        <div class="container-fluid">
            <a class="navbar-brand" href="?page=home-form">
                <img src="./img/logo.png" alt="8Bit"
                    style="height: 70px; max-height: none; position: relative; top: 0px;"
                    class="d-inline-block align-text-top">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="?page=home-form">Início</a>
                    </li>

                    <!-- Dropdown de Categorias -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
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
                                        echo '<li><a class="dropdown-item" href="./?page=home-form&sc=' . $row['id_subcategoria'] . '">' . htmlspecialchars($row['nome_subcategoria']) . '</a></li>';
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

                    <?php if (isset($_SESSION['id_utilizador'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=perfil-form">Perfil</a>
                        </li>
                    <?php endif; ?>


                    <?php if ($isAdmin): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-warning fw-bold" href="#" id="adminDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear-fill"></i> Administração
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="?page=admin-produtos-form">Produtos</a></li>
                                <li><a class="dropdown-item" href="?page=admin-utilizadores-form">Utilizadores</a></li>
                                <li><a class="dropdown-item" href="?page=admin-categorias-form">Categorias</a></li>
                                <li><a class="dropdown-item" href="?page=admin-subcategorias-form">Subcategorias</a></li>
                                <li><a class="dropdown-item" href="?page=admin-criadoras-form">Criadoras</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <?php if (isset($_SESSION['id_utilizador'])): ?>
                            <a class="nav-link text-white rounded px-3" href="?page=logout"
                                style="background-color: #dc3545;">Logout</a>
                        <?php else: ?>
                            <a class="nav-link text-white rounded px-3" href="?page=login-form"
                                style="background-color: #e83e8c;">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
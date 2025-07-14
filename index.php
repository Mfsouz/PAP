<?php
session_start();
include './bd/dbcon.php';

$utilizador_logado = isset($_SESSION['id_utilizador']);

$page = '';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Habilitar erros para depuração (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Loja de Jogos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include './includes/header.php'; ?>

    <header
        style="background: linear-gradient(90deg, #0d6efd, #e83e8c); color: white; text-align: center; padding: 3rem 0;">
        <div class="container">
            <h1>Bem-vindo à Loja de Jogos</h1>
            <p>Encontre os melhores jogos com descontos exclusivos!</p>
        </div>
    </header>

    <main class="container py-5">
        <?php
        switch ($page) {
            case 'login-form':
                include './pag/login.php';
                break;

            case 'privacidade-form':
                include './pag/privacidade.php';
                break;

            case 'termos-form':
                include './pag/termos.php';
                break;

            case 'create-account-form':
                include './pag/criarConta.php';
                break;

            case 'admin-produtos-form':
                include './admin/CRUD/produtos.php';
                break;

            case 'admin-utilizadores-form':
                include './admin/CRUD/utilizadores.php';
                break;

            case 'admin-categorias-form':
                include './admin/CRUD/categorias.php';
                break;

            case 'admin-subcategorias-form':
                include './admin/CRUD/subcategorias.php';
                break;

            case 'admin-criadoras-form':
                include './admin/CRUD/criadoras.php';
                break;

            case 'perfil-form':
                include './pag/perfil.php';
                break;

            case 'processa_login':
                include './bd/processaLogin.php';
                break;

            case 'detalhes_jogos_form':
                include './pag/detalhesJogo.php';
                break;

            case 'editar-perfil':
                include './pag/editar_perfil.php';
                break;

            case 'comentario':
                include './pag/comentario.php';
                break;

            case 'avaliacao':
                include './pag/avaliacao.php';
                break;

            case 'home':
                include './pag/home.php';
                break;

            case 'carregar_comentarios':
                include './pag/carregar_comentarios.php';
                break;

            default:
                include './pag/home.php';
                break;
        }
        ?>
    </main>

    <?php include './includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
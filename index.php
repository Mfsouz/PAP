<?php
session_start();
include './bd/dbcon.php';

$utilizador_logado = isset($_SESSION['id_utilizador']);

$page = 'home';
if (isset($_GET['page'])) {
    $page = $_GET['page']; // cast para int para segurança
}

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

<?php include './includes/header.php'; ?>

<body>

    <!-- Seção de Destaque -->
    <header
        style="background: linear-gradient(90deg, #0d6efd, #e83e8c); color: white; text-align: center; padding: 3rem 0;">
        <div class="container">
            <h1>Bem-vindo à Loja de Jogos</h1>
            <p>Encontre os melhores jogos com descontos exclusivos!</p>
        </div>
    </header>

    <section class="container py-5">


        <?php

        switch ($page) {
            case 'login-form':
                include './pag/login.php';
                break;

            case 'home':
                include './pag/home.php';
                break;
        }
        ?>

        <?php include './includes/footer.php'; ?>

        <!-- Scripts Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const btnMais = document.getElementById('mostrarMaisBtn');
                const btnMenos = document.getElementById('mostrarMenosBtn');
                const maisProdutos = document.querySelectorAll('.mais-produtos');

                if (btnMais && btnMenos) {
                    btnMais.addEventListener('click', function() {
                        maisProdutos.forEach(el => el.classList.remove('d-none'));
                        btnMais.classList.add('d-none');
                        btnMenos.classList.remove('d-none');
                    });

                    btnMenos.addEventListener('click', function() {
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
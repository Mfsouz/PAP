<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Política de Privacidade - 8Bit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>

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
                    <li class="nav-item"><a class="nav-link active" href="#">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#games">Jogos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Carrinho</a></li>
                    <li class="nav-item">
                        <a class="nav-link text-white bg-primary rounded px-3" href="./pag/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <h1 class="mb-4">Política de Privacidade</h1>

        <p>No 8Bit, respeitamos a sua privacidade e estamos comprometidos em protegê-la. Esta política explica como coletamos, usamos e protegemos as informações pessoais que você fornece.</p>

        <h2>1. Informações que Coletamos</h2>
        <p>Podemos coletar os seguintes dados pessoais:</p>
        <ul>
            <li>Nome, nome de utilizador, e-mail e senha durante o cadastro;</li>
            <li>Informações sobre suas interações com o site;</li>
            <li>Dados coletados automaticamente, como endereço IP e tipo de navegador.</li>
        </ul>

        <h2>2. Uso das Informações</h2>
        <p>As informações coletadas são usadas para:</p>
        <ul>
            <li>Permitir a criação e acesso à sua conta;</li>
            <li>Melhorar nossos serviços e experiência do usuário;</li>
            <li>Enviar comunicados importantes relacionados à sua conta;</li>
            <li>Cumprir obrigações legais.</li>
        </ul>

        <h2>3. Compartilhamento de Dados</h2>
        <p>Não vendemos, alugamos ou compartilhamos suas informações pessoais com terceiros para fins comerciais sem seu consentimento, exceto quando exigido por lei.</p>

        <h2>4. Segurança</h2>
        <p>Adotamos medidas técnicas e organizacionais para proteger suas informações contra acesso não autorizado, perda, alteração ou divulgação indevida.</p>

        <h2>5. Seus Direitos</h2>
        <p>Você pode solicitar acesso, correção ou exclusão dos seus dados pessoais, bem como a revogação do consentimento para o tratamento, conforme previsto na legislação aplicável.</p>

        <h2>6. Cookies</h2>
        <p>Nosso site utiliza cookies para melhorar a experiência do usuário. Você pode configurar seu navegador para recusar cookies, porém algumas funcionalidades poderão ser afetadas.</p>

        <h2>7. Alterações nesta Política</h2>
        <p>Podemos atualizar esta política periodicamente. Recomendamos que você a revise regularmente para se manter informado sobre como protegemos suas informações.</p>

        <h2>8. Contato</h2>
        <p>Para dúvidas ou solicitações relacionadas à privacidade, entre em contato pelo e-mail: suporte@8bit.com.</p>

        <p class="mt-5 text-muted">Última atualização: 23 de maio de 2025</p>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="footer text-center py-4 bg-dark text-white mt-auto">
        <p>&copy; 2025 Loja de Jogos. Todos os direitos reservados.</p>
        <div>
            <a href="./privacidade.php" class="text-white me-3">Privacidade</a>
            <a href="./termos.php" class="text-white me-3">Termos de Uso</a>
            <a class="text-white me-3 btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#modalContacto">Contacto</a>
        </div>
    </footer>
</body>

</html>
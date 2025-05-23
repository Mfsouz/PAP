<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Termos de Uso - 8Bit</title>
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
        <h1 class="mb-4">Termos de Uso</h1>

        <p>Bem-vindo ao 8Bit! Ao utilizar nosso site, você concorda em cumprir e estar sujeito aos seguintes termos e condições de uso.</p>

        <h2>1. Aceitação dos Termos</h2>
        <p>Ao acessar e usar nosso site, você aceita os termos descritos neste documento. Se não concordar com algum termo, por favor, não utilize nosso serviço.</p>

        <h2>2. Uso do Site</h2>
        <p>Você concorda em utilizar o site apenas para fins legais e de acordo com todas as leis aplicáveis. É proibido:</p>
        <ul>
            <li>Publicar conteúdo ilegal, ofensivo ou que infrinja direitos de terceiros;</li>
            <li>Tentar obter acesso não autorizado a outras áreas do site;</li>
            <li>Utilizar ferramentas automáticas para coletar dados ou realizar ações no site;</li>
            <li>Distribuir vírus, malware ou qualquer código malicioso.</li>
        </ul>

        <h2>3. Cadastro e Segurança</h2>
        <p>Para acessar certas funcionalidades, você pode precisar se cadastrar. É sua responsabilidade manter a confidencialidade da sua conta e senha, e notificar-nos imediatamente de qualquer uso não autorizado.</p>

        <h2>4. Propriedade Intelectual</h2>
        <p>Todo o conteúdo do site, incluindo textos, imagens, logos e software, são protegidos por direitos autorais e pertencem ao 8Bit ou seus licenciadores.</p>

        <h2>5. Limitação de Responsabilidade</h2>
        <p>O 8Bit não se responsabiliza por danos diretos ou indiretos decorrentes do uso ou incapacidade de uso do site, nem por perdas de dados.</p>

        <h2>6. Modificações nos Termos</h2>
        <p>Podemos atualizar estes termos a qualquer momento. Recomendamos que revise-os periodicamente. O uso continuado do site após modificações constitui aceitação dos novos termos.</p>

        <h2>7. Contato</h2>
        <p>Se tiver dúvidas sobre estes termos, entre em contato pelo e-mail: suporte@8bit.com.</p>

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
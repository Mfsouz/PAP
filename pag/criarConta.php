<?php
unset($_SESSION['success']);
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>8Bit - Criar Conta</title>
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
    <main class="flex-grow-1 d-flex align-items-center justify-content-center mt-4 mb-4" style="min-height: 80vh;">
        <div class="card p-4 custom-card" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Criar Conta</h2>

            <!-- Exibir mensagens de erro e sucesso -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="./bd/processaSingin.php" method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" required />
                </div>
                <div class="mb-3">
                    <label for="nome_utilizador" class="form-label">Nome de Utilizador:</label>
                    <input type="text" class="form-control" id="nome_utilizador" name="nome_utilizador" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" class="form-control" id="email" name="email" required />
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required />
                </div>
                <div class="mb-3">
                    <label for="senha_confirm" class="form-label">Confirmar Senha:</label>
                    <input type="password" class="form-control" id="senha_confirm" name="senha_confirm" required />
                </div>

                <button type="submit" class="btn btn-primary w-100">Criar Conta</button>
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-secondary w-100" onclick="history.back()">Voltar</button>
                </div>
            </form>
        </div>
    </main>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
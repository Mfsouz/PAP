<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // ou ajuste o caminho se não usar Composer

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars($_POST['nome'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mensagem = htmlspecialchars($_POST['mensagem'] ?? '');

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP do Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'teuemail@gmail.com';         // ✅ Teu e-mail Gmail
        $mail->Password   = 'SENHA_DO_APP';               // ✅ Senha de App, não a senha do Gmail normal
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remetente e destinatário
        $mail->setFrom($email, $nome);
        $mail->addAddress('marcosssantos.8b@gmail.com');  // ✅ Destinatário

        // Conteúdo
        $mail->isHTML(false);
        $mail->Subject = 'Novo contato do site - Loja de Jogos';
        $mail->Body    = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem";

        $mail->send();
        header("Location: obrigado.html");
        exit;
    } catch (Exception $e) {
        echo "Erro ao enviar: {$mail->ErrorInfo}";
    }
} else {
    echo "Método inválido.";
}
?>

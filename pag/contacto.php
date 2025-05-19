<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars($_POST['nome'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mensagem = htmlspecialchars($_POST['mensagem'] ?? '');

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'marcossantos.8b@gmail.com';         
        $mail->Password   = 'tpnz bxua eaxo edly';  // Senha de app!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remetente fixo (tem que ser o mesmo usado no Username)
        $mail->setFrom('marcossantos.8b@gmail.com', 'Formulário 8Bit');
        $mail->addReplyTo($email, $nome);  // Para que você possa responder ao visitante
        $mail->addAddress('marcosssantos.8b@gmail.com');  // destinatário final

        // Conteúdo do e-mail
        $mail->isHTML(false);
        $mail->Subject = 'Novo contato do site - 8Bit';
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

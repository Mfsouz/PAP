<?php
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .cart-container { background: white; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; }
        .cart-item { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc; }
        .cart-item img { width: 80px; border-radius: 8px; }
        .cart-item-details { flex-grow: 1; margin-left: 15px; }
        .cart-item-details h3 { margin: 0; }
        .remove-btn { background: red; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 5px; }
        #cart-total { font-size: 1.2em; font-weight: bold; margin-top: 20px; text-align: right; }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>O Meu Carrinho</h2>
    <div id="cart-items"></div>
    <div id="cart-total">Total: €0.00</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        carregarCarrinho(<?= $user_id ?>);
    });

    async function carregarCarrinho(userId) {
        const res = await fetch("getCarrinho.php?user_id=" + userId);
        const produtos = await res.json();
        const container = document.getElementById('cart-items');
        container.innerHTML = '';
        let total = 0;

        produtos.forEach(item => {
            const subtotal = item.quantidade * 10; // Podes substituir por item.preco se estiver disponível
            total += subtotal;

            const itemHtml = `
                <div class="cart-item">
                    <img src="imagens/${item.id_imagem}" alt="${item.nome}">
                    <div class="cart-item-details">
                        <h3>${item.nome}</h3>
                        <p>Quantidade: ${item.quantidade}</p>
                        <p>Subtotal: €${subtotal.toFixed(2)}</p>
                    </div>
                    <button class="remove-btn" onclick="removerItem(${item.id}, ${userId})">Remover</button>
                </div>
            `;
            container.innerHTML += itemHtml;
        });

        document.getElementById('cart-total').textContent = `Total: €${total.toFixed(2)}`;
    }

    async function removerItem(itemId, userId) {
        await fetch(`removeCarrinho.php?id=${itemId}`);
        carregarCarrinho(userId);
    }
</script>

</body>
</html>

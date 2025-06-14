<?php
// Verifica se o utilizador está autenticado
if (!isset($_SESSION['id_utilizador'])) {
    header("Location: ?page=login-form");
    exit;
}

$user_id = $_SESSION['id_utilizador'];
?>

<div class="favorites-container" style="max-width:800px; margin:auto; background:white; padding:20px; border-radius:10px; font-family:Arial, sans-serif;">
    <h2>Os Meus Favoritos</h2>
    <div id="favorites-list"></div>
    <div id="favorites-empty" style="display:none; font-style:italic; color:#666;">Não tens favoritos adicionados.</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        carregarFavoritos(<?= $user_id ?>);
    });

    async function carregarFavoritos(userId) {
        const res = await fetch("getFavoritos.php?user_id=" + userId);
        const favoritos = await res.json();
        const container = document.getElementById('favorites-list');
        const emptyMsg = document.getElementById('favorites-empty');
        container.innerHTML = '';

        if (favoritos.length === 0) {
            emptyMsg.style.display = 'block';
            return;
        } else {
            emptyMsg.style.display = 'none';
        }

        favoritos.forEach(item => {
            const itemHtml = `
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px; border-bottom:1px solid #ccc; padding-bottom:10px;">
                    <img src="imagens/${item.id_imagem}" alt="${item.nome}" style="width:80px; border-radius:8px;">
                    <div style="flex-grow:1; margin-left:15px;">
                        <h3 style="margin:0;">${item.nome}</h3>
                    </div>
                    <button style="background:#dc3545; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;"
                        onclick="removerFavorito(${item.id}, ${userId})">Remover</button>
                </div>
            `;
            container.innerHTML += itemHtml;
        });
    }

    async function removerFavorito(itemId, userId) {
        // Supondo que removeFavorito.php faz o delete do favorito para o user
        await fetch(`removeFavorito.php?id=${itemId}&user_id=${userId}`);
        carregarFavoritos(userId);
    }
</script>
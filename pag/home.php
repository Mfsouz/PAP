        <div class="games-wrapper">

            <!-- Catálogo Completo -->
            <h2 class="text-center mb-4">
                <i class="bi bi-controller"></i> Catálogo
            </h2>
            <div class="row">
                <?php foreach ($produtos as $index => $produto): ?>
                    <?php $classe_oculta = $index >= 6 ? ' d-none mais-produtos' : ''; ?>
                    <div class="col-md-4<?php echo $classe_oculta; ?>">
                        <div class="game-card">
                            <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                                <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <?php else: ?>
                                <img src="./img/no-image.png" alt="Imagem não disponível">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <?php if ($utilizador_logado): ?>
                                <a href="./pag/detalhesJogo.php?id=<?php echo $produto['id_produto']; ?>"
                                    class="btn btn-primary w-100 mt-2">Comprar</a>
                            <?php else: ?>
                                <a href="?page=login-form" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($produtos) > 6): ?>
                <div class="text-center mt-4">
                    <button id="mostrarMaisBtn" class="btn btn-outline-primary">Mostrar Mais</button>
                    <button id="mostrarMenosBtn" class="btn btn-outline-primary d-none">Mostrar Menos</button>
                </div>
            <?php endif; ?>


            <!-- Promoções -->
            <h2 class="text-center my-5"><i class="bi bi-fire text-danger"></i> Promoções</h2>
            <div class="row">
                <?php foreach ($produtos as $produto): ?>
                    <?php if (!empty($produto['desconto']) && $produto['desconto'] > 0): ?>
                        <div class="col-md-4">
                            <div class="game-card">
                                <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                                    <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" alt="Imagem não disponível">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <p class="text-danger">-<?php echo (int) $produto['desconto']; ?>% OFF</p>
                                <?php if ($utilizador_logado): ?>
                                    <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>"
                                        class="btn btn-primary w-100 mt-2">Comprar</a>
                                <?php else: ?>
                                    <a href="?page=login-form" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <h2 class="text-center my-5"><i class="bi bi-stars text-success"></i> Novos Jogos</h2>
            <div class="novos-jogos-container">
                <div class="novos-jogos-track">
                    <?php
                    // Duplicar os produtos para o loop ser contínuo
                    $produtos_para_rolar = array_merge($produtos_novos, $produtos_novos);
                    ?>
                    <?php foreach ($produtos_para_rolar as $produto): ?>
                        <div class="col-md-4">
                            <div class="game-card">
                                <?php if (!empty($produto['link_imagem']) && file_exists('./img/Games/' . $produto['link_imagem'])): ?>
                                    <img src="./img/Games/<?php echo htmlspecialchars($produto['link_imagem']); ?>"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <?php else: ?>
                                    <img src="./img/no-image.png" alt="Imagem não disponível">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <span class="badge bg-success">Novidade!</span>
                                <?php if ($utilizador_logado): ?>
                                    <a href="./pag/buy.php?id=<?php echo $produto['id_produto']; ?>"
                                        class="btn btn-primary w-100 mt-2">Comprar</a>
                                <?php else: ?>
                                    <a href="./pag/login.php" class="btn btn-secondary w-100 mt-2">Entrar para comprar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
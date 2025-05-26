<?php
include 'dbcon.php';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$sql = "SELECT c.id, c.produto_id, c.quantidade, p.nome, p.id_imagem
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id_produto
        WHERE c.utilizador_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$itens = [];

while ($row = $result->fetch_assoc()) {
    $itens[] = $row;
}

header('Content-Type: application/json');
echo json_encode($itens);
?>

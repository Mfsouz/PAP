<?php
session_start();
include './bd/dbcon.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['id_utilizador'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

// Pega os parâmetros da query
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['id_utilizador'];

// Verifica se os parâmetros são válidos
if ($item_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Remove o favorito do utilizador
$sql = "DELETE FROM favoritos WHERE id_jogo = ? AND id_utilizador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao remover favorito']);
}

$stmt->close();
$conn->close();

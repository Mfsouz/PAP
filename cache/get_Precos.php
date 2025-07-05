<?php
$gameName = $_GET['jogo_nome'] ?? '';
$cacheKey = md5($gameName);
$cacheFile = __DIR__ . "/cache_salvo/{$cacheKey}.json";
$cacheDuration = 3600;

header('Content-Type: application/json');

if (empty($gameName)) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetro jogo_nome em falta']);
    exit;
}

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
    echo file_get_contents($cacheFile);
    exit;
}

$apiUrl = "https://magicloops.dev/api/loop/773f84df-aeb8-4634-ac43-1b4e8f4ebf1d/run";
$apiUrlWithParams = $apiUrl . '?' . http_build_query(['jogo_nome' => $gameName]);

$response = file_get_contents($apiUrlWithParams);

if ($response !== false) {
    $json = json_decode($response, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        file_put_contents($cacheFile, json_encode($json));
        echo json_encode($json);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Resposta inválida da API (JSON malformado)']);
    }
} else {
    http_response_code(502);
    echo json_encode(['error' => 'Erro ao obter dados da API']);
}

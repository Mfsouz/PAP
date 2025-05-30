<?php
$gameName = $_GET['jogo_nome'] ?? '';
$cacheKey = md5($gameName);
$cacheFile = __DIR__ . "/cache_salvo/{$cacheKey}.json";
$cacheDuration = 3600; // 1 hora

// Verifica se o cache existe e ainda está válido
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
    // Devolve o cache
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit;
}

// Caso contrário, consulta a API
$apiUrl = "https://magicloops.dev/api/loop/3691ba69-edee-4374-a5ea-dfdeab2af05e/run";
$apiUrlWithParams = $apiUrl . '?' . http_build_query(['jogo_nome' => $gameName]);

$response = file_get_contents($apiUrlWithParams);

if ($response !== false) {
    // Salva em cache
    file_put_contents($cacheFile, $response);

    header('Content-Type: application/json');
    echo $response;
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao obter dados da API']);
}

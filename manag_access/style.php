<?php
$filesParam = $_GET['file'] ?? $_GET['files'] ?? '';
$files = array_filter(array_map('trim', explode(',', $filesParam)));

header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$basePath = __DIR__ . '/../style/';
$found = false;

foreach ($files as $file) {
    $safe = basename($file);
    $path = $basePath . $safe;

    if (file_exists($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'css') {
        readfile($path);
        echo "\n\n"; // séparation entre fichiers
        $found = true;
    } else {
        echo "/* ⚠️ Fichier CSS introuvable : {$safe} */\n";
    }
}

if (!$found) {
    http_response_code(404);
    echo "/* ❌ Aucun fichier CSS trouvé */";
}
?>
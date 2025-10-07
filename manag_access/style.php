<?php
$filesParam = $_GET['file'] ?? $_GET['files'] ?? '';
$files = array_filter(array_map('trim', explode(',', $filesParam)));

header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$basePath = __DIR__ . '/../private/style/';
$found = false;

foreach ($files as $file) {
    $safe = basename($file);
    $path = $basePath . $safe;

    if (file_exists($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'css') {
        readfile($path);
        $found = true;
    }
}
if (!$found) {
    http_response_code(404);
}
?>
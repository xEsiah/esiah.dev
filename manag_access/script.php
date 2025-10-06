<?php
$file = basename($_GET['file'] ?? '');
$path = __DIR__ . '/../private/scripts/' . $file;

if (file_exists($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'js') {
    header('Content-Type: application/javascript');
    readfile($path);
    exit;
} else {
    http_response_code(404);
    echo '// Fichier JS introuvable';
}

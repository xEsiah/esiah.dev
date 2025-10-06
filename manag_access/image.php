<?php
$file = basename($_GET['file'] ?? '');
$path = __DIR__ . '/../private/images/' . $file;
$extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

if (file_exists($path) && in_array($ext, $extensions)) {
    $types = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml'
    ];
    header('Content-Type: ' . $types[$ext]);
    readfile($path);
    exit;
} else {
    http_response_code(404);
    echo 'Image introuvable';
}

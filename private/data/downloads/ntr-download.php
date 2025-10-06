<?php
$zipPath = $_SERVER['DOCUMENT_ROOT'] . '/Neo-Tokyo-Rush.zip';
$countFile = __DIR__ . '/../ntr-downloads.count';

if (!is_file($zipPath)) {
    http_response_code(404);
    exit('File not found');
}

$fp = fopen($countFile, 'c+');
if ($fp) {
    if (flock($fp, LOCK_EX)) {
        $raw = stream_get_contents($fp, -1, 0);
        $current = (int) trim($raw);
        $current = $current > 0 ? $current : 0;
        $current++;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, (string) $current);
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

header('Content-Description: File Transfer');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="Neo-Tokyo-Rush.zip"');
header('Content-Length: ' . filesize($zipPath));
header('Cache-Control: no-cache');
readfile($zipPath);

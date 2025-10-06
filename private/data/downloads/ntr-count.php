<?php
header('Content-Type: application/json; charset=utf-8');
$countFile = __DIR__ . '/../ntr-downloads.count';
$count = 0;
if (is_file($countFile)) {
    $count = (int) trim(file_get_contents($countFile));
}
echo json_encode(['downloads' => $count]);

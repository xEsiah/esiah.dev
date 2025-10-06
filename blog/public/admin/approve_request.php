<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../config/config.php';

$BASE = rtrim(BASE_URL, '/');

/* Accès: connecté */
if (empty($_SESSION['user']['id'])) {
    header('Location: ' . $BASE . '/login', true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $BASE . '/admin', true, 303);
    exit;
}

$requestId = (int) ($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($requestId <= 0 || !in_array($action, ['accept', '✔️ Accepter', 'rejeter', '🛑 Rejeter', 'reject'], true)) {
    $_SESSION['flash'] = "Requête invalide.";
    header('Location: ' . $BASE . '/admin', true, 303);
    exit;
}

/* Récup demande */
$stmt = $pdo->prepare("SELECT id, username, first_name, last_name, password FROM access_requests WHERE id = ?");
$stmt->execute([$requestId]);
$request = $stmt->fetch();
if (!$request) {
    $_SESSION['flash'] = "Demande introuvable.";
    header('Location: ' . $BASE . '/admin', true, 303);
    exit;
}

$pdo->beginTransaction();
try {
    if (in_array($action, ['accept', '✔️ Accepter'], true)) {
        $ins = $pdo->prepare("INSERT INTO authors (username, first_name, last_name, password) VALUES (?, ?, ?, ?)");
        $ins->execute([$request['username'], $request['first_name'], $request['last_name'], $request['password']]);
        $_SESSION['flash'] = "Demande acceptée : {$request['username']}";
    } else {
        $_SESSION['flash'] = "Demande rejetée : {$request['username']}";
    }

    $del = $pdo->prepare("DELETE FROM access_requests WHERE id = ?");
    $del->execute([$requestId]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = "Erreur: " . $e->getMessage();
}

header('Location: ' . $BASE . '/admin', true, 303);
exit;

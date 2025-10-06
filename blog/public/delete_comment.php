<?php
ob_start(); // Pour éviter les erreurs de header déjà envoyés
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = (int) ($_POST['comment_id'] ?? 0);

    // Récupérer le post_id lié au commentaire
    $stmt = $pdo->prepare("SELECT post_id FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch();

    if (!$comment) {
        die("❌ Commentaire introuvable.");
    }

    $postId = (int) $comment['post_id'];

    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        header("Location: post.php?id=" . $postId);
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $userRole = $_SESSION['user']['role'] ?? 'user'; // Défaut à "user" si non défini

    // Vérifie si l'utilisateur est l'auteur du post
    $stmt = $pdo->prepare("
        SELECT posts.author_id 
        FROM posts 
        JOIN comments ON comments.post_id = posts.id 
        WHERE comments.id = ?
        LIMIT 1
    ");
    $stmt->execute([$commentId]);
    $postData = $stmt->fetch();

    if (!$postData) {
        die("❌ Commentaire ou post introuvable.");
    }

    // Seul l'auteur du post ou un admin peut supprimer
    if ($userRole !== 'admin' && $userId !== (int) $postData['author_id']) {
        header("Location: post.php?id=" . $postId);
        exit;
    }

    // Suppression autorisée
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);

    header("Location: post.php?id=" . $postId);
    exit;
} else {
    echo "Méthode non autorisée.";
}

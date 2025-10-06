<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../config/config.php';

$BASE = rtrim(BASE_URL, '/');

/* ── Accès: connecté uniquement ── */
if (empty($_SESSION['user']['id'])) {
    header('Location: ' . $BASE . '/login', true, 303);
    exit;
}

/* Helper excerpt sans dépendre de mbstring */
function excerpt(string $text, int $limit = 150): string
{
    $text = trim($text);
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $limit, '…', 'UTF-8');
    }
    return (strlen($text) > $limit) ? substr($text, 0, $limit) . '…' : $text;
}

/* Données */
try {
    $posts = $pdo->query("SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC")->fetchAll();
    $requests = $pdo->query("SELECT id, first_name, last_name, username, motivation FROM access_requests")->fetchAll();
} catch (Throwable $e) {
    $posts = [];
    $requests = [];
}

require_once __DIR__ . '/../../includes/header.php';
?>

<h2 class="Administrationh3">Liste des posts</h2>
<?php foreach ($posts as $post): ?>
    <div class="post-list">
        <h3 style="color:#1e90ff;" class="infos"><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars(excerpt($post['content']))) ?></p>
        <p style="color:#1e90ff;"><small>Publié le <?= date('d/m/Y', strtotime($post['created_at'])) ?></small></p>

        <form action="<?= $BASE ?>/admin/functions/delete_post" method="POST"
            onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
            <button type="submit">🗑 Supprimer</button>
        </form>

        <a class="btn" href="<?= $BASE ?>/admin/edit?id=<?= (int) $post['id'] ?>">
            <button type="button">✏️ Éditer</button>
        </a>
    </div>
<?php endforeach; ?>

<h2 class="Administrationh3">Demandes d'adhésion</h2>
<?php foreach ($requests as $request): ?>
    <div class="post-list">
        <p class="infos">Nom:</p>
        <p><?= htmlspecialchars($request['first_name']) ?>     <?= htmlspecialchars($request['last_name']) ?></p>

        <p class="infos">Nom d'utilisateur:</p>
        <p><?= htmlspecialchars($request['username']) ?></p>

        <p class="infos">Motivation:</p>
        <p><?= nl2br(htmlspecialchars($request['motivation'])) ?></p>

        <form action="<?= $BASE ?>/admin/approve_request" method="POST" style="display:flex; gap:.5rem;">
            <input type="hidden" name="request_id" value="<?= (int) $request['id'] ?>">
            <button type="submit" name="action" value="accept">✔️ Accepter</button>
            <button type="submit" name="action" value="reject">🛑 Rejeter</button>
        </form>
    </div>
<?php endforeach; ?>

<?php include __DIR__ . '/functions/categ_managment.php'; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
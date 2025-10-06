<?php
/**
 * config.php — prod only
 * - Charge .env (incluant /home/USER/.env au-dessus de public_html)
 * - BASE_URL = chemin public (ex.: /blog)
 * - Connexion PDO MySQL (utf8mb4)
 */

/* ────────────── Localisation du .env ────────────── */
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
$homeFromDoc = $docRoot ? dirname($docRoot) : null; // ex: /home/USER

$candidates = [];
if ($homeFromDoc)
    $candidates[] = $homeFromDoc . '/.env';         // /home/USER/.env
if ($docRoot)
    $candidates[] = $docRoot . '/.env';             // /home/USER/public_html/.env
$candidates[] = __DIR__ . '/../../.env';                                  // …/public_html/.env
$candidates[] = __DIR__ . '/../.env';                                     // …/public_html/blog/.env
$candidates[] = __DIR__ . '/../../../.env';                               // …/.env (fallback)

$envFile = null;
foreach ($candidates as $p) {
    $real = realpath($p) ?: $p;
    if (is_file($real) && is_readable($real)) {
        $envFile = $real;
        break;
    }
}

/* ────────────── Chargement .env (minimal) ────────────── */
if ($envFile) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = ltrim($line);
        if ($line === '' || $line[0] === '#')
            continue;
        [$name, $value] = array_pad(explode('=', $line, 2), 2, '');
        $name = trim($name);
        $value = preg_replace('/^([\'"])(.*)\1$/', '$2', trim($value));
        if ($name !== '') {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

/* ────────────── Helper env() ────────────── */
function env(string $key, $default = null)
{
    $v = $_ENV[$key] ?? getenv($key);
    return ($v === false || $v === null || $v === '') ? $default : $v;
}

/* ────────────── BASE_URL (chemin public du blog) ──────────────
 * Exemple recommandé dans .env : BLOG_BASE_PATH=/blog
 */
$basePath = rtrim(env('BLOG_BASE_PATH', '/blog'), '/');
if ($basePath === '')
    $basePath = '/';
if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath); // ex.: /blog
}

/* ────────────── Paramètres MySQL ────────────── */
$host = env('DB_HOST', '127.0.0.1');
$db = env('DB_DATABASE', 'blog');
$user = env('DB_USERNAME', 'root');
$pass = env('DB_PASSWORD', '');
$port = (int) env('DB_PORT', 3306);
$socket = env('DB_SOCKET', ''); // ex.: /var/run/mysqld/mysqld.sock

// DSN MySQL (socket prioritaire)
if ($socket)
    $dsn = "mysql:unix_socket={$socket};dbname={$db};charset=utf8mb4";
else
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

$pdoOpts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

/* ────────────── Connexion PDO ────────────── */
if (!defined('SKIP_DB')) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $pdoOpts);
    } catch (PDOException $e) {
        http_response_code(503);
        if (PHP_SAPI !== 'cli') {
            header('Location: ' . rtrim(BASE_URL, '/') . '/503');
            exit;
        }
        fwrite(STDERR, "DB connection failed: " . $e->getMessage() . PHP_EOL);
    }
}

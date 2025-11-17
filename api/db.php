<?php
// api/db.php
// Conexión universal usando DATABASE_URL (Railway, Render, Neon, Supabase)

header('Content-Type: application/json');

$databaseUrl = getenv('DATABASE_URL');

if (!$databaseUrl) {
    http_response_code(500);
    echo json_encode(['error' => 'DATABASE_URL not found in environment']);
    exit();
}

// Parsear URL postgres://user:pass@host:port/dbname?param=value
$parts = parse_url($databaseUrl);

if (!$parts || !isset($parts['host'], $parts['user'], $parts['pass'], $parts['path'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid DATABASE_URL format']);
    exit();
}

$dbHost = $parts['host'];
$dbPort = $parts['port'] ?? 5432;
$dbUser = $parts['user'];
$dbPass = $parts['pass'];
$dbName = ltrim($parts['path'], '/');

// Asegurar que sslmode esté presente
$sslMode = "require";

$dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode={$sslMode}";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'DB connection failed',
        'details' => $e->getMessage()
    ]);
    exit();
}

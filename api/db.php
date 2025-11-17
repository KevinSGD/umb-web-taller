<?php
// api/db.php
// Detecta automáticamente DATABASE_URL (Render) o variables individuales para Supabase.

// 1. Intentar obtener DATABASE_URL
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {

    // Asegurar formato correcto
    $parts = parse_url($databaseUrl);

    if (!$parts || !isset($parts['host'], $parts['user'], $parts['pass'], $parts['path'])) {
        http_response_code(500);
        echo json_encode(['error' => 'DATABASE_URL is invalid']);
        exit();
    }

    $dbHost = $parts['host'];
    $dbPort = $parts['port'] ?? 5432;
    $dbUser = $parts['user'];
    $dbPass = $parts['pass'];
    $dbName = ltrim($parts['path'], '/');

    // DSN con SSL requerido por Supabase
    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode=require";

} else {

    // 2. Alternativa: Variables individuales
    $dbHost = getenv('SUPA_HOST');
    $dbPort = getenv('SUPA_PORT') ?: 5432;
    $dbUser = getenv('SUPA_USER');
    $dbPass = getenv('SUPA_PASS');
    $dbName = getenv('SUPA_DB');

    if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
        http_response_code(500);
        echo json_encode(['error' => 'Missing Supabase environment variables']);
        exit();
    }

    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode=require";
}

// 3. Conectar a la base de datos
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

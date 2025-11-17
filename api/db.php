<?php
// api/db.php
// Usa la variable de entorno DATABASE_URL (postgres://user:pass@host:port/dbname)
// Si prefieres, define individualmente SUPA_HOST, SUPA_DB, SUPA_USER, SUPA_PASS, SUPA_PORT en Render.

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    $parts = parse_url($databaseUrl);

    $dbHost = $parts['host'];
    $dbPort = $parts['port'] ?? 5432;
    $dbUser = $parts['user'];
    $dbPass = $parts['pass'];
    $dbName = ltrim($parts['path'], '/');

    // DSN con sslmode=require (necesario para conexiones remotas con Supabase)
    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode=require";
} else {
    // Alternativa si usas variables individuales en Render
    $dbHost = getenv('SUPA_HOST');
    $dbPort = getenv('SUPA_PORT') ?: 5432;
    $dbUser = getenv('SUPA_USER');
    $dbPass = getenv('SUPA_PASS');
    $dbName = getenv('SUPA_DB');
    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode=require";
}

try {
    // Opciones: persistente desactivada por seguridad en deploys compartidos
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]);
    exit();
}

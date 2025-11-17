<?php
// api/db.php

header("Content-Type: application/json");

// Detectar DATABASE_URL
$databaseUrl = getenv("DATABASE_URL");

if (!$databaseUrl) {
    http_response_code(500);
    echo json_encode(["error" => "DATABASE_URL not set"]);
    exit();
}

// Parsear URL
$parts = parse_url($databaseUrl);

$dbHost = $parts['host'] ?? null;
$dbPort = $parts['port'] ?? 5432;
$dbUser = $parts['user'] ?? null;
$dbPass = $parts['pass'] ?? null;
$dbName = ltrim($parts['path'], "/") ?: null;

// Validar
if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
    http_response_code(500);
    echo json_encode([
        "error" => "Invalid DATABASE_URL",
        "debug" => $parts
    ]);
    exit();
}

// DSN + SSL obligatorio
$dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;sslmode=require";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "DB connection failed",
        "details" => $e->getMessage()
    ]);
    exit();
}

<?php
// 1. Configuración de Variables de Entorno (o valores temporales para desarrollo)
// Render proveerá estos valores cuando se despliegue.
$host = getenv('DB_HOST') ?: "localhost";
$port = getenv('DB_PORT') ?: "5432";
$dbname = getenv('DB_NAME') ?: "postgres";
$user = getenv('DB_USER') ?: "postgres";
$password = getenv('DB_PASSWORD') ?: "tu_contraseña_temporal";

try {
    // Conexión usando PDO (PHP Data Objects) para PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // El último array activa el manejo de errores (lanzará excepciones)
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
} catch (PDOException $e) {
    // 2. Validación y manejo de error
    // En producción, solo deberías loguear el error, no mostrarlo al usuario.
    error_log("Error al conectar a PostgreSQL: " . $e->getMessage());
    // Devolvemos una respuesta de error al cliente
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}
// La variable $pdo estará disponible para ser usada en modelo.php
?>
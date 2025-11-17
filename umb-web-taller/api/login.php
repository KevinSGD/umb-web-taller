<?php
// api/login.php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $input['usuario'] ?? null;
    if (!$usuario) {
        http_response_code(400);
        echo json_encode(['error' => 'usuario requerido']);
        exit();
    }
    $_SESSION['usuario'] = $usuario;
    echo json_encode(['mensaje' => "Sesión iniciada para $usuario"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    session_destroy();
    echo json_encode(['mensaje' => 'Sesión cerrada']);
    exit();
}

echo json_encode(['sesion' => $_SESSION ?? []]);

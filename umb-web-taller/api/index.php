<?php
// api/index.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'modelo.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : null;

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Routing simple por método y parámetros
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $tarea = obtenerTarea((int)$_GET['id']);
        echo json_encode($tarea ?: []);
    } else {
        $tareas = obtenerTareas();
        echo json_encode($tareas);
    }
    exit();
}

if ($method === 'POST') {
    // crear
    $titulo = $input['titulo'] ?? null;
    if (!$titulo) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta el campo titulo']);
        exit();
    }
    $id = crearTarea($titulo);
    http_response_code(201);
    echo json_encode(['mensaje' => 'Tarea creada', 'id' => $id]);
    exit();
}

if ($method === 'PUT') {
    // actualizar: espera id en query o en body
    $id = $_GET['id'] ?? ($input['id'] ?? null);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta id']);
        exit();
    }
    $titulo = $input['titulo'] ?? null;
    $completada = array_key_exists('completada', $input) ? (bool)$input['completada'] : null;
    $ok = actualizarTarea((int)$id, $titulo, $completada);
    echo json_encode(['ok' => (bool)$ok]);
    exit();
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? ($input['id'] ?? null);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta id']);
        exit();
    }
    $ok = eliminarTarea((int)$id);
    echo json_encode(['ok' => (bool)$ok]);
    exit();
}

// método no permitido
http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);

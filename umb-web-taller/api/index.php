<?php
// Configuración CORS - Obligatorio para que React pueda acceder
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json"); // La respuesta siempre será JSON

// Salida temprana para la solicitud OPTIONS (CORS preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

require_once 'modelo.php';

$metodo = $_SERVER['REQUEST_METHOD'];

// Capturar datos para POST, PUT, DELETE (vienen en el cuerpo JSON)
// Usamos 'php://input' para leer el cuerpo de la petición HTTP
$datos = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {
    case 'GET':
        // READ: Obtener todas las tareas
        $tareas = obtenerTareas();
        echo json_encode($tareas);
        break;

    case 'POST':
        // CREATE: Crear una nueva tarea
        if (isset($datos['titulo'])) {
            crearTarea($datos['titulo']);
            http_response_code(201); // 201 Created
            echo json_encode(['mensaje' => 'Tarea creada']);
        } else {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['error' => 'Título requerido']);
        }
        break;

    case 'PUT':
        // UPDATE: Actualizar el estado de la tarea (completada)
        if (isset($datos['id']) && isset($datos['completada'])) {
            // Asegurarse de que 'completada' es un booleano
            $completada_bool = filter_var($datos['completada'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if ($completada_bool !== null) {
                actualizarTarea($datos['id'], $completada_bool);
                echo json_encode(['mensaje' => 'Tarea actualizada']);
            } else {
                 http_response_code(400);
                 echo json_encode(['error' => 'Estado completada inválido']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID y estado de completada requeridos']);
        }
        break;

    case 'DELETE':
        // DELETE: Eliminar una tarea
        if (isset($datos['id'])) {
            eliminarTarea($datos['id']);
            echo json_encode(['mensaje' => 'Tarea eliminada']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido para eliminar']);
        }
        break;

    default:
        // Método no soportado
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>
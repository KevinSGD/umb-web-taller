<?php
// api/modelo.php
require_once 'db.php';

// Crear tarea
function crearTarea($titulo) {
    global $pdo;
    $sql = "INSERT INTO tareas (titulo) VALUES (:titulo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo]);
    return $pdo->lastInsertId();
}

// Obtener todas las tareas
function obtenerTareas() {
    global $pdo;
    $sql = "SELECT id, titulo, completada, created_at FROM tareas ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Obtener una tarea por id
function obtenerTarea($id) {
    global $pdo;
    $sql = "SELECT id, titulo, completada, created_at FROM tareas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

// Actualizar tarea (titulo + completada)
function actualizarTarea($id, $titulo = null, $completada = null) {
    global $pdo;
    $fields = [];
    $params = [':id' => $id];

    if ($titulo !== null) {
        $fields[] = "titulo = :titulo";
        $params[':titulo'] = $titulo;
    }
    if ($completada !== null) {
        $fields[] = "completada = :completada";
        $params[':completada'] = $completada ? true : false;
    }
    if (empty($fields)) return false;

    $sql = "UPDATE tareas SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// Eliminar tarea
function eliminarTarea($id) {
    global $pdo;
    $sql = "DELETE FROM tareas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

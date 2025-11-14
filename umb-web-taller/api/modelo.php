<?php
require_once 'db.php';
global $pdo;

// CREATE (Crear Tarea)
function crearTarea($titulo) {
    global $pdo;
    
    // 1. Sanitizar el título
    $titulo_seguro = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    
    // 2. Usar consulta preparada con parámetros de nombre (:titulo)
    $sql = "INSERT INTO tareas (titulo) VALUES (:titulo)";
    $stmt = $pdo->prepare($sql);
    
    // 3. Vincular el parámetro y ejecutar
    return $stmt->execute([':titulo' => $titulo_seguro]);
}

// READ (Leer Todas las Tareas)
function obtenerTareas() {
    global $pdo;
    $sql = "SELECT id, titulo, completada FROM tareas ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    
    // Devolver todas las filas como un array asociativo
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// UPDATE (Actualizar Tarea - Marcar como completada)
function actualizarTarea($id, $completada) {
    global $pdo;
    
    $sql = "UPDATE tareas SET completada = :completada WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    // PDO::PARAM_BOOL es ideal para campos booleanos
    $stmt->bindParam(':completada', $completada, PDO::PARAM_BOOL); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// DELETE (Eliminar Tarea)
function eliminarTarea($id) {
    global $pdo;
    
    $sql = "DELETE FROM tareas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}
?>
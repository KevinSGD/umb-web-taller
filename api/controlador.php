<?php
require_once 'modelo.php';

$accion = $_GET['accion'] ?? '';

switch ($accion) {

    case 'listar_carreras':
        echo supabaseGET('carreras');
        break;

    case 'listar_estudiantes':
        echo supabaseGET('estudiantes');
        break;

    case 'agregar_estudiante':
        $data = [
            "nombre" => $_POST['nombre'],
            "codigo" => $_POST['codigo'],
            "carrera" => $_POST['carrera']
        ];
        echo supabasePOST('estudiantes', $data);
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
}
?>

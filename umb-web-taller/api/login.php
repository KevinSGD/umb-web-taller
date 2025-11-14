<?php
session_start(); // Inicia o reanuda la sesión

// Verificar si se ha enviado un formulario POST
if (isset($_POST['usuario'])) {
    $usuario = htmlspecialchars($_POST['usuario']); // Limpieza de input
    $_SESSION["usuario"] = $usuario;
    echo "Sesión iniciada para: " . $_SESSION["usuario"] . ". El ID de sesión es: " . session_id();
} elseif (isset($_GET['logout'])) {
    session_destroy();
    echo "Sesión destruida (Logout exitoso).";
} else {
    if (isset($_SESSION["usuario"])) {
        echo "Ya existe una sesión activa para: " . $_SESSION["usuario"];
    } else {
        echo "No hay sesión activa. Envía un POST con 'usuario' para iniciar sesión.";
    }
}
?>
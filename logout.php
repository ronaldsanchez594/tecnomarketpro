<?php
// logout.php - cierre seguro de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Elimina todas las variables de sesión
$_SESSION = [];

// Destruye la sesión por completo
session_unset();
session_destroy();

// Redirige al login
header('Location: login.php');
exit;
?>

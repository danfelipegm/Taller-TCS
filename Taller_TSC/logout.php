<?php
require_once 'lib/Session.php';
// Iniciar la sesión al principio del archivo
session_start();

// Incluir la clase Session
require_once 'lib/Session.php';

// Llamar al método estático destroy() para cerrar la sesión
Session::destroy();

// Finalizar el script para evitar que se ejecute código adicional después de la redirección
exit();
?>

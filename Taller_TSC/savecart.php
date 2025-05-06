<?php

session_start();
require_once 'lib/Session.php';

include 'config/config.php';

// Conexión a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'db_admin');

// Verificar si la conexión es exitosa
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total_general = 0;

    foreach ($_SESSION['cart'] as $item) {
        // Sanitizar los datos antes de insertarlos
        $nombre = mysqli_real_escape_string($conexion, $item['name']);
        
        // Eliminar puntos y comas del precio si están presentes, para asegurar que sea un número válido
        $precio = str_replace(['.', ','], '', $item['price']);
        $precio = (float) $precio; // Asegurarse de que sea un número flotante
        
        $cantidad = (int) $item['quantity']; // Asegurarse de que sea un número entero
        $precio_total = $precio * $cantidad;
        $total_general += $precio_total;

        // Insertar cada ítem del carrito en la tabla cart
        $query = "INSERT INTO cart (name, price, quantity, total_price, total) 
                  VALUES ('$nombre', $precio, $cantidad, $precio_total, 0)";
        
        // Verificar si la inserción fue exitosa
        if (!mysqli_query($conexion, $query)) {
            die("Error al insertar el carrito: " . mysqli_error($conexion));
        }
    }

    // Actualizar el total general para todos los registros insertados (opcional)
    $update_total = "UPDATE cart SET total = $total_general WHERE total = 0";
    
    if (!mysqli_query($conexion, $update_total)) {
        die("Error al actualizar el total: " . mysqli_error($conexion));
    }

    // Limpiar el carrito
    unset($_SESSION['cart']);

    // Redirecciona o muestra mensaje
    echo "<script>alert('Carrito guardado exitosamente.'); window.location.href='checkout.php';</script>";
} else {
    echo "<script>alert('El carrito está vacío.'); window.location.href='userindex.php';</script>";
}
?>

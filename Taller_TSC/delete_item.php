<?php
if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $conn = new mysqli("localhost", "root", "", "db_admin");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM cart WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: checkout.php");
exit;

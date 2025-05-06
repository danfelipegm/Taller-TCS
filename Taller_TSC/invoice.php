<?php
// Incluir la librería FPDF
require('lib/fpdf186/fpdf.php');

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "db_admin");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la información del cliente desde la sesión
session_start();
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];  // Asumiendo que ya estás guardando 'name' en la sesión

// Obtener los productos del carrito y sus descripciones
$sql = "SELECT c.name, c.quantity, c.total_price, p.description FROM cart c
        JOIN products p ON c.name = p.name"; // Unir cart y products por el nombre del producto
$result = $conn->query($sql);

// Crear una nueva instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Añadir el logo (ajusta la posición y tamaño según tus necesidades)
$pdf->Image('assets/logoempresa.png', 10, 10, 40);  // El logo se coloca en (10, 10) con un ancho de 40mm

// Establecer la fuente para el título
$pdf->SetFont('Arial', 'B', 16);

// Título de la factura
$pdf->Cell(190, 10, 'Factura de Compra - Taller TOTES BGA', 0, 1, 'C');

// Fecha de generación de la factura
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Fecha: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

// Información del cliente
$pdf->Cell(95, 10, 'Nombre: ' . $name, 0, 0, 'L');
$pdf->Cell(95, 10, 'Correo: ' . $email, 0, 1, 'L');
$pdf->Cell(95, 10, 'Usuario: ' . $username, 0, 0, 'L');

// Método de pago (esto debería ser capturado del formulario)
$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'Efectivo'; // Ejemplo
$pdf->Cell(95, 10, 'Metodo de Pago: ' . $metodo_pago, 0, 1, 'L');

// Información de la dirección (esto también debe ser capturado del formulario)
$departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
$ciudad = isset($_POST['ciudad']) ? $_POST['ciudad'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$codigo_postal = isset($_POST['codigo_postal']) ? $_POST['codigo_postal'] : '';

$pdf->Cell(95, 10, 'Departamento: ' . $departamento, 0, 0, 'L');
$pdf->Cell(95, 10, 'Ciudad: ' . $ciudad, 0, 1, 'L');
$pdf->Cell(95, 10, 'Direccion: ' . $direccion, 0, 0, 'L');
$pdf->Cell(95, 10, 'Codigo Postal: ' . $codigo_postal, 0, 1, 'L');

// Espacio para la tabla
$pdf->Ln(10);

// Tabla de productos facturados
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Producto', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 10, 'Precio', 1, 0, 'C');
$pdf->Cell(40, 10, 'Total', 1, 1, 'C');

// Rellenar la tabla con los productos
$pdf->SetFont('Arial', '', 12);
$total_general = 0;  // Variable para el total general

while ($row = $result->fetch_assoc()) {
    $nombre = $row['name'];
    $descripcion = $row['description'];
    $cantidad = $row['quantity'];
    $precio = $row['total_price'] / $cantidad; // Calcular precio unitario
    $total = $row['total_price'];

    // Usar MultiCell para permitir texto largo en la celda
    $pdf->Cell(60, 10, $nombre, 1, 0, 'L');
    $pdf->Cell(40, 10, $cantidad, 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($precio, 2, ',', '.'), 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($total, 2, ',', '.'), 1, 1, 'C');
    
    // Agregar la descripción en una nueva línea utilizando MultiCell
    $pdf->MultiCell(160, 10, 'Descripcion: ' . utf8_decode($descripcion), 1, 'L');
    
    $total_general += $total;  // Acumulando el total
}

// Calcular el impuesto (19%)
$impuesto = $total_general * 0.19;
$total_con_impuesto = $total_general + $impuesto;

// Subtotal, impuesto y total
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'Subtotal', 1, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($total_general, 2, ',', '.'), 1, 1, 'C');

$pdf->Cell(150, 10, 'Impuesto (19%)', 1, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($impuesto, 2, ',', '.'), 1, 1, 'C');

$pdf->Cell(150, 10, 'TOTAL', 1, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($total_con_impuesto, 2, ',', '.'), 1, 1, 'C');

// Salida del PDF al navegador
$pdf->Output('I', 'factura_' . $username . '.pdf');

// Cerrar la conexión
$conn->close();
?>

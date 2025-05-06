<?php
session_start();
require_once 'lib/Session.php';
Session::init();

include 'config/config.php';
$conn = mysqli_connect('localhost', 'root', '', 'db_admin');

// Si se solicita limpiar el carrito
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == 'true') {
  // Ejecutamos el DELETE para vaciar la tabla cart
  $delete_sql = "DELETE FROM cart";
  if ($conn->query($delete_sql) === TRUE) {
      echo "El carrito ha sido vacío exitosamente.";
  } else {
      echo "Error al vaciar el carrito: " . $conn->error;
  }
}

$query = "SELECT * FROM products WHERE state = 1";
$result = mysqli_query($conn, $query);

$username = Session::get('username');

// Verifica si el usuario ha regresado del checkout
if (isset($_SESSION['from_checkout']) && $_SESSION['from_checkout'] == true) {
  // Borra la marca de productos añadidos al carrito
  unset($_SESSION['added_to_cart']);
  unset($_SESSION['from_checkout']); // Elimina la variable después de procesar
}

// Llamamos a la función para borrar las marcas si el carrito está vacío
clearAddedMarksIfCartIsEmpty();

// Función que verifica si el carrito está vacío y elimina las marcas de productos añadidos
function clearAddedMarksIfCartIsEmpty() {
    // Verifica si el carrito está vacío
    if (empty($_SESSION['cart'])) {
        // Elimina las marcas de productos añadidos
        $_SESSION['added_to_cart'] = [];
    }
}

// Verifica si se está añadiendo un producto al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Obtén los datos del producto
    $product_query = "SELECT * FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    // Verifica si el producto ya está en el carrito
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        ];
        // Establece que el producto ha sido añadido
        $_SESSION['added_to_cart'][$product_id] = true;
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Eliminar un producto del carrito
if (isset($_GET['remove_id'])) {
  $remove_id = $_GET['remove_id'];

  // Eliminar el producto del carrito
  if (isset($_SESSION['cart'][$remove_id])) {
      unset($_SESSION['cart'][$remove_id]);
  }

  // Eliminar la marca de producto añadido
  if (isset($_SESSION['added_to_cart'][$remove_id])) {
      unset($_SESSION['added_to_cart'][$remove_id]);
  }
}

// Actualizar la cantidad de un producto en el carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Asegúrate de que la cantidad no sea mayor al stock disponible
    $product_query = "SELECT stock FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product_data = mysqli_fetch_assoc($product_result);
    $stock = $product_data['stock'];

    if ($quantity <= $stock && $quantity > 0) {
        // Actualiza la cantidad en el carrito
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        echo "<script>alert('Cantidad no válida. Asegúrate de no exceder el stock disponible.');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Productos</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
  <style>
    :root {
      /* Colores modo oscuro (originales) */
      --dark-bg: #2C2C2C;
      --dark-card-bg: #333;
      --dark-text: #fff;
      --dark-accent: #FF335F;
      --dark-secondary: #FF4C73;
      --dark-header-bg: #444;
      --dark-input-bg: #333;
      
      /* Colores modo claro */
      --light-bg: #FFFFFF;
      --light-card-bg: #DEDEDE;
      --light-text: #2C2C2C;
      --light-accent: #00289F;
      --light-secondary: #2E4998;
      --light-header-bg: #DEDEDE;
      --light-input-bg: #FFFFFF;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
      padding: 20px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .welcome {
      background-color: var(--header-bg);
      padding: 10px 15px;
      border-radius: 8px;
      font-size: 18px;
    }

    .btn-back {
      background-color: var(--accent-color);
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .product-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      justify-items: center;
    }

    .product-card {
      background-color: var(--card-bg);
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      position: relative;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 350px;
    }

    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .price-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: var(--accent-color);
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
      font-size: 14px;
    }

    .product-card img {
      width: 100%;
      height: auto;
      margin: 20px 0;
      border-radius: 8px;
    }

    h3 {
      font-size: 18px;
      color: var(--secondary-color);
      margin-bottom: 15px;
    }

    input[type="number"] {
      width: 100%;
      padding: 10px;
      background-color: var(--input-bg);
      border: none;
      color: var(--text-color);
      font-size: 16px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 10px;
    }

    button {
      background-color: var(--accent-color);
      border: none;
      color: white;
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: var(--secondary-color);
    }

    table {
      width: 100%;
      margin-top: 40px;
      border-collapse: collapse;
    }

    table, th, td {
      border: 1px solid var(--accent-color);
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: var(--accent-color);
      color: white;
    }

    td img {
      width: 50px;
      height: auto;
    }

    .action-btn {
      background-color: var(--secondary-color);
      border: none;
      color: white;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 5px;
    }

    .added-message {
      color: #2ecc71;
      font-weight: bold;
    }

    button[disabled] {
      background-color: #95a5a6;
      cursor: not-allowed;
    }

    /* Estilos para el interruptor de tema */
    .theme-switch {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 100;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #00289F;
      transition: .4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #FF335F;
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    .slider i {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: white;
      font-size: 12px;
    }

    .slider .sun {
      left: 6px;
    }

    .slider .moon {
      right: 6px;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="dark-mode">

<div class="theme-switch">
  <label class="switch">
    <input type="checkbox" id="theme-toggle">
    <span class="slider">
      <i class="fas fa-sun sun"></i>
      <i class="fas fa-moon moon"></i>
    </span>
  </label>
</div>

<div class="header">
  <div style="display: flex; flex-direction: column;">
    <?php if (isset($username)): ?>
      <div class="welcome">Bienvenido a tu carrito de compras: <strong><?= htmlspecialchars($username) ?></strong></div>
    <?php endif; ?>
    <h1 style="margin-top: 10px; font-size: 28px; color: var(--secondary-color); font-weight: 700;">Servicios del Taller</h1>
  </div>
  <a href="userindex.php" class="btn-back">Regresar a Taller</a>
</div>

<div class="product-container">
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <div class="product-card">
    <div class="price-badge"><?= $row['price'] ?>/-</div>
    <img src="assets/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
    <h3><?= htmlspecialchars($row['name']) ?></h3>

    <form action="" method="post">
      <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
      <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock'] ?>" required>
      <?php if (isset($_SESSION['added_to_cart'][$row['id']])): ?>
        <button type="submit" disabled>Producto añadido</button>
      <?php else: ?>
        <button type="submit">Añadir al carrito</button>
      <?php endif; ?>
    </form>
  </div>
<?php endwhile; ?>
</div>

<h2 style="color: var(--secondary-color); text-align: center; margin-top: 40px;">Carrito de Compras</h2>

<table> 
  <tr>
    <th>Imagen</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Cantidad</th>
    <th>Precio Total</th>
    <th>Acción</th>
  </tr>
  <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
    <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
      <?php 
$precioNumerico = str_replace('.', '', $product['price']);
$precioNumerico = floatval($precioNumerico);

$precioTotalNumerico = $precioNumerico * $product['quantity'];
$precioTotalFormateado = number_format($precioTotalNumerico, 0, ',', '.'); 
?>

<tr>
    <td><img src="assets/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
    <td><?= htmlspecialchars($product['name']) ?></td>
    <td><?= number_format($precioNumerico, 0, ',', '.') ?>/-</td>
    <td>
        <form action="" method="post">
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" min="1" max="10" required>
            <button type="submit">Actualizar</button>
        </form>
    </td>
    <td><?= $precioTotalFormateado ?>/-</td>
    <td><a href="?remove_id=<?= $product_id ?>" class="action-btn">Remover</a></td>
</tr>

    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="6">El carrito está vacío.</td></tr>
  <?php endif; ?>
</table>

<?php if (!empty($_SESSION['cart'])): ?>
  <div style="text-align: center; margin-top: 30px;">
    <form id="checkoutForm" action="savecart.php" method="post">
     <button type="button" onclick="confirmCheckout()" style="background-color: var(--accent-color); padding: 12px 25px; font-size: 18px; font-weight: bold; border-radius: 8px;">
    Proceder a facturación
</button>

    </form>
  </div>

  <script>
    function confirmCheckout() {
      if (confirm("¿Está seguro de ir a facturación?")) {
        document.getElementById("checkoutForm").submit();
      }
    }

    // Funcionalidad del interruptor de tema
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('theme-toggle');
      const body = document.body;
      
      // Verificar preferencia del usuario
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'light') {
        setLightTheme();
        themeToggle.checked = true;
      } else {
        setDarkTheme();
        themeToggle.checked = false;
      }
      
      // Manejar cambio de tema
      themeToggle.addEventListener('change', function() {
        if (this.checked) {
          setLightTheme();
          localStorage.setItem('theme', 'light');
        } else {
          setDarkTheme();
          localStorage.setItem('theme', 'dark');
        }
      });
      
      function setLightTheme() {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        
        // Aplicar variables CSS para modo claro
        document.documentElement.style.setProperty('--bg-color', 'var(--light-bg)');
        document.documentElement.style.setProperty('--text-color', 'var(--light-text)');
        document.documentElement.style.setProperty('--card-bg', 'var(--light-card-bg)');
        document.documentElement.style.setProperty('--accent-color', 'var(--light-accent)');
        document.documentElement.style.setProperty('--secondary-color', 'var(--light-secondary)');
        document.documentElement.style.setProperty('--header-bg', 'var(--light-header-bg)');
        document.documentElement.style.setProperty('--input-bg', 'var(--light-input-bg)');
      }
      
      function setDarkTheme() {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
        
        // Aplicar variables CSS para modo oscuro
        document.documentElement.style.setProperty('--bg-color', 'var(--dark-bg)');
        document.documentElement.style.setProperty('--text-color', 'var(--dark-text)');
        document.documentElement.style.setProperty('--card-bg', 'var(--dark-card-bg)');
        document.documentElement.style.setProperty('--accent-color', 'var(--dark-accent)');
        document.documentElement.style.setProperty('--secondary-color', 'var(--dark-secondary)');
        document.documentElement.style.setProperty('--header-bg', 'var(--dark-header-bg)');
        document.documentElement.style.setProperty('--input-bg', 'var(--dark-input-bg)');
      }
    });
  </script>
<?php endif; ?>

</body>
</html>


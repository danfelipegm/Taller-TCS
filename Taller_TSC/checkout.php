<?php
session_start();
require_once 'lib/Session.php';
Session::init();

$username = Session::get('username');
$email = Session::get('email');
$name = Session::get('name'); // Asumiendo que ya estás guardando 'name' en la sesión

$conn = new mysqli("localhost", "root", "", "db_admin");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los productos del carrito
$sql = "SELECT name, quantity, total_price FROM cart";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Completa tu orden</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            /* Colores modo oscuro (originales) */
            --dark-bg: #191919;
            --dark-box-bg: #2C2C2C;
            --dark-text: #fff;
            --dark-accent: #FF335F;
            --dark-secondary: #FF4C73;
            --dark-border: #555;
            --dark-form-bg: #fff;
            --dark-form-shadow: #ccc;
            --dark-readonly-bg: #eee;
            
            /* Colores modo claro */
            --light-bg: #FFFFFF;
            --light-box-bg: #DEDEDE;
            --light-text: #191919;
            --light-accent: #00289F;
            --light-secondary: #2E4998;
            --light-border: #2E4998;
            --light-form-bg: #FFFFFF;
            --light-form-shadow: rgba(0, 40, 159, 0.2);
            --light-readonly-bg: #f5f5f5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 40px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        h1 {
            text-align: center;
            color: var(--secondary-color);
        }
        .cart-box {
            background-color: var(--box-bg);
            color: var(--text-color);
            padding: 20px;
            margin: 20px auto;
            width: 50%;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .cart-item {
            margin-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            position: relative;
        }
        .cart-item a {
            color: var(--accent-color);
            position: absolute;
            top: 0;
            right: 0;
            font-weight: bold;
            text-decoration: none;
            padding: 0 10px;
        }
        form {
            background-color: var(--form-bg);
            width: 50%;
            margin: 0 auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px var(--form-shadow);
            transition: all 0.3s ease;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        input[readonly] {
            background-color: var(--readonly-bg);
            color: var(--text-color);
            font-weight: bold;
        }
        button {
            background-color: var(--accent-color);
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        a {
            color: var(--secondary-color);
            background-color: var(--form-bg);
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid var(--secondary-color);
            display: inline-block;
            transition: all 0.3s ease;
        }
        a:hover {
            background-color: var(--secondary-color);
            color: #fff;
        }

        /* Estilos para el interruptor de tema */
        .theme-switch {
            position: fixed;
            top: 20px;
            right: 80px; /* Ajustado para no solapar con el botón existente */
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

<a href="cartshop.php?clear_cart=true" style="position: absolute; top: 20px; right: 20px; color: var(--secondary-color); background-color: var(--form-bg); padding: 10px 20px; border-radius: 5px; text-decoration: none; border: 1px solid var(--secondary-color);">Regresar a mi carrito de compras</a>

<h1>COMPLETA TU ORDEN</h1>

<div class="cart-box">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="cart-item">
                <a href="delete_item.php?name=<?php echo urlencode($row['name']); ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')">X</a>

                <strong>Producto:</strong> <?php echo htmlspecialchars($row['name']); ?><br>
                <strong>Cantidad:</strong> <?php echo $row['quantity']; ?><br>
                <strong>Total:</strong> $<?php echo number_format($row['total_price'], 0, ',', '.'); ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay productos en el carrito.</p>
    <?php endif; ?>
</div>

<form action="invoice.php" method="post">
    <label>Nombre</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>

    <label>Correo</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>

    <label>Nombre de usuario</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>

    <label>Método de pago</label>
    <select name="metodo_pago" required>
        <option value="">Selecciona un método</option>
        <option value="Efectivo">Efectivo</option>
        <option value="Tarjeta">Tarjeta</option>
    </select>

    <label>Departamento</label>
    <input type="text" name="departamento" placeholder="Tu departamento" required>

    <label>Ciudad</label>
    <input type="text" name="ciudad" placeholder="Tu ciudad" required>

    <label>Dirección</label>
    <input type="text" name="direccion" placeholder="Dirección exacta" required>

    <label>Código postal</label>
    <input type="text" name="codigo_postal" placeholder="Código postal" required>

    <button type="submit">ORDENAR AHORA</button>
</form>

<script>
    // Funcionalidad del interruptor de tema
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('theme-toggle');
      const body = document.body;
      
      // Manejar cambio de tema
      themeToggle.addEventListener('change', function() {
        if (this.checked) {
          setLightTheme();
        } else {
          setDarkTheme();
        }
      });
      
      function setLightTheme() {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        
        // Aplicar variables CSS para modo claro
        document.documentElement.style.setProperty('--bg-color', 'var(--light-bg)');
        document.documentElement.style.setProperty('--text-color', 'var(--light-text)');
        document.documentElement.style.setProperty('--box-bg', 'var(--light-box-bg)');
        document.documentElement.style.setProperty('--accent-color', 'var(--light-accent)');
        document.documentElement.style.setProperty('--secondary-color', 'var(--light-secondary)');
        document.documentElement.style.setProperty('--border-color', 'var(--light-border)');
        document.documentElement.style.setProperty('--form-bg', 'var(--light-form-bg)');
        document.documentElement.style.setProperty('--form-shadow', 'var(--light-form-shadow)');
        document.documentElement.style.setProperty('--readonly-bg', 'var(--light-readonly-bg)');
      }
      
      function setDarkTheme() {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
        
        // Aplicar variables CSS para modo oscuro
        document.documentElement.style.setProperty('--bg-color', 'var(--dark-bg)');
        document.documentElement.style.setProperty('--text-color', 'var(--dark-text)');
        document.documentElement.style.setProperty('--box-bg', 'var(--dark-box-bg)');
        document.documentElement.style.setProperty('--accent-color', 'var(--dark-accent)');
        document.documentElement.style.setProperty('--secondary-color', 'var(--dark-secondary)');
        document.documentElement.style.setProperty('--border-color', 'var(--dark-border)');
        document.documentElement.style.setProperty('--form-bg', 'var(--dark-form-bg)');
        document.documentElement.style.setProperty('--form-shadow', 'var(--dark-form-shadow)');
        document.documentElement.style.setProperty('--readonly-bg', 'var(--dark-readonly-bg)');
      }
    });
</script>

</body>
</html>


<?php $conn->close(); ?>

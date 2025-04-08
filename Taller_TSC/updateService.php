<?php
$conn = mysqli_connect('localhost', 'root', '', 'db_admin');
include 'inc/header.php';

Session::CheckSession();

$id = $_GET['edit'];

if (isset($_POST['update_product'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_stock = $_POST['product_stock'];
  $product_type = $_POST['product_type'];
$product_state = $_POST['product_state'];

   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'assets/imagenservicio/' . $product_image;

   if (empty($product_name) || empty($product_price)) {
      $message[] = 'Por favor, complete todos los campos obligatorios.';
   } else {
      // Si se carga una nueva imagen
      if (!empty($product_image)) {
        $update_data = "UPDATE products SET name='$product_name', price='$product_price', image='$product_image', stock='$product_stock', type='$product_type', state='$product_state' WHERE id='$id'";
        move_uploaded_file($product_image_tmp_name, $product_image_folder);
     } else {
        $update_data = "UPDATE products SET name='$product_name', price='$product_price', stock='$product_stock', type='$product_type', state='$product_state' WHERE id='$id'";
     }
     

      $upload = mysqli_query($conn, $update_data);

      if ($upload) {
         header('location:addService.php');
         exit;
      } else {
         $message[] = 'Error al actualizar el servicio.';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Editar Servicio</title>   
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

:root {
   --primary: #FF335F;
   --primary-hover: #FF4C73;
   --text-color: #ffffff;
   --background-main: #191919;
   --background-secondary: #2C2C2C;
   --input-bg: #3a3a3a;
   --box-shadow: 0 .5rem 1rem rgba(255, 255, 255, 0.05);
   --border: .1rem solid rgba(255, 255, 255, 0.2);
}

* {
   font-family: 'Poppins', sans-serif;
   margin: 0; padding: 0;
   box-sizing: border-box;
   outline: none; border: none;
   text-decoration: none;
   text-transform: capitalize;
   color: var(--text-color);
}

body {
   background: var(--background-main);
}

html {
   font-size: 62.5%;
   overflow-x: hidden;
}

.btn {
   display: block;
   width: 100%;
   cursor: pointer;
   border-radius: .5rem;
   margin-top: 1rem;
   font-size: 1.7rem;
   padding: 1rem 3rem;
   background: var(--primary);
   color: var(--text-color);
   text-align: center;
   transition: background 0.3s;
}

.btn:hover {
   background: var(--primary-hover);
}

.message {
   display: block;
   background: var(--background-secondary);
   padding: 1.5rem 1rem;
   font-size: 2rem;
   color: var(--text-color);
   margin-bottom: 2rem;
   text-align: center;
   border-radius: 0.5rem;
   border: var(--border);
}

.container {
   max-width: 1200px;
   padding: 2rem;
   margin: 0 auto;
}

.admin-product-form-container form {
   max-width: 50rem;
   margin: 0 auto;
   padding: 2rem;
   border-radius: .5rem;
   background: var(--background-secondary);
   box-shadow: var(--box-shadow);
}

.admin-product-form-container form h3 {
   text-transform: uppercase;
   color: var(--text-color);
   margin-bottom: 1rem;
   text-align: center;
   font-size: 2.5rem;
}

.admin-product-form-container form .box {
   width: 100%;
   border-radius: .5rem;
   padding: 1.2rem 1.5rem;
   font-size: 1.7rem;
   margin: 1rem 0;
   background: var(--input-bg);
   color: var(--text-color);
   text-transform: none;
   border: var(--border);
}

.product-display {
   margin: 2rem 0;
}

.product-display .product-display-table {
   width: 100%;
   text-align: center;
   background: var(--background-secondary);
   color: var(--text-color);
   border-collapse: collapse;
}

.product-display .product-display-table thead {
    background: var(--thead-bg);
}

.product-display .product-display-table th,
.product-display .product-display-table td {
   padding: 1rem;
   font-size: 2rem;
   border-bottom: var(--border);
}

.product-display .product-display-table .btn:first-child {
   margin-top: 0;
}

.product-display .product-display-table .btn:last-child {
   background: crimson;
}

.product-display .product-display-table .btn:last-child:hover {
   background: #a11;
}

.product-display img {
   border-radius: 0.5rem;
   box-shadow: var(--box-shadow);
}

@media (max-width: 991px){
   html {
      font-size: 55%;
   }
}

@media (max-width: 768px){
   .product-display {
      overflow-x: scroll;
   }

   .product-display .product-display-table {
      width: 80rem;
   }
}

@media (max-width: 450px){
   html {
      font-size: 50%;
   }
}
#theme-toggle {
   position: fixed;
   bottom: 20px;
   right: 20px;
   background-color: var(--primary);
   color: var(--text-color);
   padding: 1rem 2rem;
   font-size: 1.6rem;
   border-radius: 2rem;
   cursor: pointer;
   box-shadow: var(--box-shadow);
   z-index: 999;
   transition: background 0.3s;
}
#theme-toggle:hover {
   background-color: var(--primary-hover);
}


   </style>
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '<span class="message">' . $msg . '</span>';
   }
}
?>

<div class="container">
   <div class="admin-product-form-container centered">
      <?php
      $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
      if (mysqli_num_rows($select) > 0) {
         $row = mysqli_fetch_assoc($select);
      ?>
      <form action="" method="post" enctype="multipart/form-data">
         <h3 class="title">Actualizar Servicio</h3>
         <input type="text" class="box" name="product_name" value="<?php echo $row['name']; ?>" placeholder="Nombre del servicio">
         <input type="number" min="0" class="box" name="product_price" value="<?php echo $row['price']; ?>" placeholder="Precio del servicio">
         <input type="number" min="0" class="box" name="product_stock" value="<?php echo $row['stock']; ?>" placeholder="Stock del servicio">
<input type="text" class="box" name="product_type" value="<?php echo $row['type']; ?>" placeholder="Tipo de servicio">
<select name="product_state" class="box">
   <option value="DISPONIBLE" <?php if($row['state'] == 'DISPONIBLE') echo 'selected'; ?>>DISPONIBLE</option>
   <option value="NO DISPONIBLE" <?php if($row['state'] == 'NO DISPONIBLE') echo 'selected'; ?>>NO DISPONIBLE</option>
</select>

         <input type="file" class="box" name="product_image" accept="image/png, image/jpeg, image/jpg">
         <img src="assets/imagenservicio/<?php echo $row['image']; ?>" height="100" style="margin-top: 10px; border-radius: 8px;">
         <input type="submit" value="Actualizar Servicio" name="update_product" class="btn">
         <a href="addService.php" class="btn">Volver</a>
      </form>
      <?php } else {
         echo "<p>Servicio no encontrado.</p>";
      } ?>
   </div>
</div>

</body>
</html>

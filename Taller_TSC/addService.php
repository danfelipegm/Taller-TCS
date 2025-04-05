<?php
$conn = mysqli_connect('localhost','root','','db_admin');
include 'inc/header.php';

Session::CheckSession();

if(isset($_POST['add_product'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_stock = $_POST['product_stock'];
    $product_type = $_POST['product_type'];
    $product_state = $_POST['product_state'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'assets/imagenservicio'.$product_image;
 
    if(empty($product_name) || empty($product_price) || empty($product_image) || empty($product_description) || empty($product_stock) || empty($product_type) || $product_state === ''){
       $message[] = 'Por favor llene todos los campos';
    }else{
       $insert = "INSERT INTO products(name, price, image, description, stock, type, state) 
                  VALUES ('$product_name', '$product_price', '$product_image', '$product_description', '$product_stock', '$product_type', '$product_state')";
       $upload = mysqli_query($conn, $insert);
       if($upload){
          move_uploaded_file($product_image_tmp_name, $product_image_folder);
          $message[] = 'Nuevo servicio añadido exitosamente';
       }else{
          $message[] = 'No se pudo añadir el servicio';
       }
    }
 }
 ;

if(isset($_GET['delete'])){
   $id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM products WHERE id = $id");
   header('location:addService.php');
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
if(isset($message)){
   foreach($message as $message){
      echo '<span class="message">'.$message.'</span>';
   }
}
?>
   
<div class="container">

   <div class="admin-product-form-container">
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
         <h3>Agregar un nuevo servicio al Taller</h3>
         <input type="text" placeholder="Ingrese el nombre del Servicio" name="product_name" class="box">
         <input type="text" placeholder="Ingrese una descripcion del servicio" name="product_description" class="box">
         <input type="number" placeholder="Ingrese el precio del Servicio" name="product_price" class="box">
         <input type="number" placeholder="Ingrese el stock disponible" name="product_stock" class="box" min="0">
<input type="text" placeholder="Ingrese el tipo de servicio" name="product_type" class="box">
<select name="product_state" class="box">
   <option value="">Seleccione estado</option>
   <option value="1">DISPONIBLE</option>
   <option value="0">NO DISPONIBLE</option>
</select>
         <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
         <input type="submit" class="btn" name="add_product" value="Agregar Servicio">
      </form>
   </div>

   <?php
   $select = mysqli_query($conn, "SELECT * FROM products");
   ?>
   <div class="product-display">
      <table class="product-display-table">
      <thead>
<tr>
   <th>Imagen</th>
   <th>Nombre</th>
   <th>Descripción</th>
   <th>Precio</th>
   <th>Stock</th>
   <th>Tipo</th>
   <th>Estado</th>
   <th>Acción</th>
</tr>
</thead>

         <?php while($row = mysqli_fetch_assoc($select)){ ?>
            <tr>
   <td><img src="assets/imagenservicio<?php echo $row['image']; ?>" height="100" alt=""></td>
   <td><?php echo $row['name']; ?></td>
   <td><?php echo $row['description']; ?></td>
   <td>$<?php echo $row['price']; ?>/-</td>
   <td><?php echo $row['stock']; ?></td>
   <td><?php echo $row['type']; ?></td>
   <td><?php echo $row['state'] ? 'DISPONIBLE' : 'NO DISPONIBLE'; ?></td>
   <td>
      <a href="updateService.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> Editar </a>
      <a href="addService.php?delete=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> Eliminar </a>
   </td>
</tr>


         <?php } ?>
      </table>
   </div>

</div>
<div id="theme-toggle" onclick="toggleTheme()">🌙 Modo Oscuro / Claro</div>
<script>
function toggleTheme() {
   const root = document.documentElement;
   const dark = {
      '--primary': '#FF335F',
      '--primary-hover': '#FF4C73',
      '--text-color': '#ffffff',
      '--background-main': '#191919',
      '--background-secondary': '#2C2C2C',
      '--input-bg': '#3a3a3a',
      '--box-shadow': '0 .5rem 1rem rgba(255, 255, 255, 0.05)',
      '--border': '.1rem solid rgba(255, 255, 255, 0.2)',
      '--thead-bg': '#1f1f1f'
   };
   const light = {
      '--primary': '#007BFF',
      '--primary-hover': '#3399FF',
      '--text-color': '#000000',
      '--background-main': '#f4f4f4',
      '--background-secondary': '#ffffff',
      '--input-bg': '#eaeaea',
      '--box-shadow': '0 .5rem 1rem rgba(0, 0, 0, 0.1)',
      '--border': '.1rem solid rgba(0, 0, 0, 0.2)',
      '--thead-bg': '#ddd'
   };

   const isDark = root.style.getPropertyValue('--background-main') === dark['--background-main'];
   const theme = isDark ? light : dark;

   for (let variable in theme) {
      root.style.setProperty(variable, theme[variable]);
   }
}
</script>


</body>
</html>

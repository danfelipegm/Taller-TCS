<?php
include 'inc/header.php';
Session::CheckLogin();
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
   $userLog = $users->userLoginAuthotication($_POST);
}
if (isset($userLog)) {
  echo $userLog;
}

$logout = Session::get('logout');
if (isset($logout)) {
  echo $logout;
}
?>

<style>
  .full-screen-login {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #191919 0%, #2C2C2C 100%);
  }
  .login-card {
    width: 100%;
    max-width: 600px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
  }
  .card-header {
    background: rgba(0, 0, 0, 0.7);
    padding: 30px 20px;
  }
  .card-body {
    padding: 40px;
    background: white;
  }
  .form-group {
    margin-bottom: 25px;
  }
  .form-control {
    height: 50px;
    font-size: 16px;
    padding: 10px 15px;
  }
  .btn-success {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    font-weight: bold;
    background: #FF335F;
    border: none;
    transition: all 0.3s;
  }
  .btn-success:hover {
    background: #FF4C73;
    transform: translateY(-2px);
  }
  label {
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
    font-size: 16px;
  }
  h3 {
    font-size: 28px;
    margin: 0;
  }
</style>

<div class="full-screen-login">
  <div class="login-card">
    <div class="card-header">
      <h3 class="text-center" style="color: white; font-weight: bold;">
        <i class="fas fa-sign-in-alt mr-2"></i>Inicio de Sesión
      </h3>
    </div>
    <div class="card-body">
      <form class="" action="" method="post">
        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input type="email" name="email" class="form-control" placeholder="Ingrese su correo">
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña">
        </div>
        <div class="form-group">
          <button type="submit" name="login" class="btn btn-success">
            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include 'inc/footer.php';
?>
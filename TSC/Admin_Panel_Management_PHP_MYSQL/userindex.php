<?php
// Iniciar la sesión y manejar la sesión del usuario
session_start();
require_once 'lib/Session.php';  // Asegúrate de que la ruta sea correcta
Session::init();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index.css" />
    <title>Taller Mecánico - TOTES BGA</title>
  </head>
  <body>
    <header>
      <div class="top-nav">
        <div class="logo">
          <img src="assets/Logo.png" alt="TOTES BGA" />
        </div>
        <nav>
          <ul>
            <li><a href="#">Inicio</a></li>
            <li class="dropdown">
              <a href="#" class="dropbtn">Mecánica Básica</a>
              <ul class="dropdown-content">
                <li><a href="#">Revisión de Motor</a></li>
                <li><a href="#">Frenos</a></li>
                <li><a href="#">Suspensión</a></li>
                <li><a href="#">Otros Servicios</a></li>
              </ul>
            </li>
            <li><a href="#">Mecánica Especializada</a></li>
            <li><a href="#">Nosotros</a></li>
            <li><a href="#">Servicio al Cliente</a></li>
            <li><a href="#">Contáctanos</a></li>
            <li><a href="logout.php">Cerrar Sesion</a></li>
          </ul>
        </nav>
      </div>
    </header>
    
    <section class="hero">
      <div class="hero-text">
        <h1>
          Bienvenido al taller de <span class="highlight">TOTES BGA</span>
        </h1>
        <div class="welcome-message">
          <?php
          $username = Session::get('username');
          if (isset($username)) {
            echo "<span class='badge badge-lg badge-secondary text-white'>Bienvenido, $username</span>";
          }
          ?>
        </div>
        <p class="highlight-text">Tu taller mecánico de confianza</p>
        <p class="description-text">
          Nos apasiona dejar tu vehículo en las mejores condiciones. Somos un
          taller especializado en mantenimiento, reparación y personalización de
          autos, con un equipo de expertos listos para brindarte un servicio de
          calidad y confianza.
        </p>
        <div class="hero-buttons">
          <a href="#" class="btn">Contáctanos</a>
          <a href="#" class="btn btn-alt">Agendar cita</a>
        </div>
      </div>
      <div class="hero-image">
        <img src="assets/mecanico.png" alt="Mecánico" />
      </div>
    </section>

    <section class="services">
      <div class="service">
        <img src="assets/Mecanica Basica.jpg" alt="Mecánica Básica" />
        <h3>Mecánica Básica</h3>
        <p>
          Servicio integral para el mantenimiento y reparación del vehículo,
          incluyendo revisión de motor, frenos, suspensión y más.
        </p>
        <a href="#" class="btn btn-alt">Agendar Cita</a>
      </div>
      <div class="service">
        <img
          src="assets/Mecanica Especializada.jpg"
          alt="Mecánica Especializada"
        />
        <h3>Mecánica Especializada</h3>
        <p>
          Diagnóstico y reparación avanzada de sistemas específicos como
          inyección electrónica y transmisión.
        </p>
        <a href="#" class="btn btn-alt">Agendar Cita</a>
      </div>
      <div class="service">
        <img src="assets/Pintura.jpg" alt="Latonería y Pintura" />
        <h3>Latonería y Pintura</h3>
        <p>
          Transforma tu vehículo con mejoras en carrocería, pintura y sistemas
          de sonido.
        </p>
        <a href="#" class="btn btn-alt">Agendar Cita</a>
      </div>
    </section>

    <footer>
      <div class="footer-logo">
        <img src="assets/Logo.png" alt="TOTES BGA" />
      </div>
      <p>© 2025 TotesBGA Colombia. Todos los derechos reservados</p>
    </footer>
  </body>
</html>


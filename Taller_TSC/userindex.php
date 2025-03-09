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
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="assets/iconotaller.png"> 
    <title>Taller Mecánico - TOTES BGA</title>
    <style>

header {
    position: relative;
    z-index: 1000; /* Asegura que esté sobre otros elementos */
    overflow: visible; /* Permite que los submenús se muestren */
}
        .navbar {
    position: relative;
    z-index: 1000; /* Asegura que el menú tiene prioridad */
}

.submenu {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: rgba(34, 34, 34, 0.4); /* Aumenta la transparencia al 70% */

    display: none;
    z-index: 2000;
    width: 200px; /* Define un ancho para evitar que los ítems se alineen horizontalmente */
    border: 2px solid #ff2d55; /* Agrega un borde de color rojo */
    border-radius: 5px; /* Bordes redondeados para un mejor diseño */
    padding: 5px 0; /* Espaciado interno */
    backdrop-filter: blur(7px); /* Aplica un ligero desenfoque al fondo */
}

.nav-item:hover .submenu {
    display: block;
}

        nav ul li {
            position: relative;
        }

        nav ul li:hover .submenu {
            display: block;
        }

        .submenu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: block;
        }

        .submenu ul li {
    padding: 10px;
    display: block; /* Asegura que cada elemento se muestre uno debajo del otro */
    width: 100%;
}

.submenu ul li a {
    display: block; /* Hace que el enlace ocupe todo el ancho del `li` */
    color: white;
    text-decoration: none;
    padding: 5px 10px;
    transition: color 0.3s ease-in-out; /* Transición suave al cambiar de color */

}

.submenu ul li a:hover {
  color: #ff2d55; /* Cambia solo el color del texto a rojo */
}
    </style>
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
                    <li>
                        <a href="#">Mecánica Básica</a>
                        <div class="submenu">
                            <ul>
                                <li><a href="cambioaceite.html">Cambio de Aceite y filtros</a></li>
                                <li><a href="cambiobateria.html">Diagnostico y cambio de bateria</a></li>
                                <li><a href="mantenimientofreno.html">Mantenimiento General de frenos</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">Mecánica Especializada</a>
                        <div class="submenu">
                            <ul>
                                <li><a href="servicioscanner.html">Servicios de Scanner</a></li>
                                <li><a href="reparacionmotor.html">Reparacion de motor</a></li>
                                <li><a href="correasmotor.html">Correas de motor</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="nosotros.html">Nosotros</a></li>
                    <li><a href="https://wa.me/573153576670?text=Hola%20*Taller%20mecanico%20TOTES%20BGA*.%20Necesito%20m%C3%A1s%20informaci%C3%B3n%20sobre%20Taller%20mecanico%20TOTES%20BGA%20http://localhost/Taller_TSC/userindex.php">Servicio al Cliente</a></li>
                    <li><a href="contactanos.html">Contáctanos</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
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
        <p class="highlight-text">A tu taller mecánico de confianza</p>
        <p class="description-text">
          Nos apasiona dejar tu vehículo en las mejores condiciones. Somos un
          taller especializado en mantenimiento, reparación y personalización de
          autos, con un equipo de expertos listos para brindarte un servicio de
          calidad y confianza.
        </p>
        <div class="hero-buttons">
    <a href="contactanos.html" class="btn">Contáctanos</a>
    <a href="https://calendar.google.com/calendar/appointments/schedules/AcZssZ1wlvUVXo0VD46RBwd4_-1J-_rNX6AnvM_a1DiSakJjGLYXywHSS8zqgnWrD1zsdnpURAHjGPkv?gv=true" class="btn btn-alt" target="_blank">Agendar cita</a>
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
        <a href="https://calendar.google.com/calendar/appointments/schedules/AcZssZ1wlvUVXo0VD46RBwd4_-1J-_rNX6AnvM_a1DiSakJjGLYXywHSS8zqgnWrD1zsdnpURAHjGPkv?gv=true" class="btn btn-alt" target="_blank">Agendar cita</a>
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
        <a href="https://calendar.google.com/calendar/appointments/schedules/AcZssZ1wlvUVXo0VD46RBwd4_-1J-_rNX6AnvM_a1DiSakJjGLYXywHSS8zqgnWrD1zsdnpURAHjGPkv?gv=true" class="btn btn-alt" target="_blank">Agendar cita</a>
      </div>
      <div class="service">
        <img src="assets/Pintura.jpg" alt="Latonería y Pintura" />
        <h3>Latonería y Pintura</h3>
        <p>
          Transforma tu vehículo con mejoras en carrocería, pintura y sistemas
          de sonido.
        </p>
        <a href="https://calendar.google.com/calendar/appointments/schedules/AcZssZ1wlvUVXo0VD46RBwd4_-1J-_rNX6AnvM_a1DiSakJjGLYXywHSS8zqgnWrD1zsdnpURAHjGPkv?gv=true" class="btn btn-alt" target="_blank">Agendar cita</a>
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



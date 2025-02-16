<?php
session_start();
include_once 'php/conexion.php';
// Hace una select donde recoge todas las imagenes
$consulta = "SELECT * FROM imagenes";
//Establece conexion con la base de datos
$resultado = mysqli_query($conn, $consulta);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_calendario.css" type="text/css" rel="stylesheet">
    <meta name="theme-color" content="#FF5733">
</head>
<body>
    <header>
        <img src="img/A2.png" width="120px" alt="Logo">
        <nav>
            <a href="index.php">Inicio</a>
            <a href="" id="contactLink">Contactar</a>
            <?php
            /*Ahora al iniciar sesion te lanzara un mensaje diciendo "Hola username(osea, el nombre del que haya iniciado sesion)"
            La idea es que, a parte del nombre, haya como una minilista donde ponga la opcion de cerrar la sesion(Esta a medias ya que cuando haces click en el nombre se cierra la sesion, yo quiero ponerlo en una opcion aparte)
            Modificado dia 8*/
            // Ahora ya esta modificado para que aparezca la pestaña de cerrar sesion en un bloque cuando pasas el raton por encima del nombre de usuario
            
            if(isset($_SESSION['username'])) {
                echo '<div class="bloque">
                        <button>Hola, ' . htmlspecialchars($_SESSION['username']) . ' </button>
                        <div class="bloquecito">
                            <a href="/php/cerrarSesion.php">Cerrar sesión</a>
                        </div>
                      </div>';
            } else {
                ?>
            <a href="php/registro.php">Iniciar Sesion / Registrarse</a>
            <?php
            }
            ?>
        </nav>
    </header>

    <!-- Juanmi -->
    <div class="main-container">
        <div class="calendar-section">
            <div class="calendar">
                <div class="calendar-header">
                    <button id="prev">&#8592;</button>
                    <h2 id="monthYear"></h2>
                    <button id="next">&#8594;</button>
                </div>
                <div class="calendar-grid" id="calendarDays">
                    <!-- Días se generan dinámicamente -->
                </div>
            </div>
        </div>
        <div class="events-section">
            <div class="eventos">
                <h1 class="events-header">Eventos :</h1>
                <button class="event-button" id="proposeEventButton">Proponer Evento</button>
            </div>
        </div>    
    </div>

    <div id="eventList" class="events-list">

    <!-- Los eventos aparecerán aquí -->

    <!-------------------------------MOSTRADOR DE EVENTOS---------------------------------------------------------------->
    
    

    <!-------------------------------MOSTRADOR DE EVENTOS---------------------------------------------------------------->
    </div>

    <div class="mapa_contenedor">
        <div class="map-section">
            <iframe 
                src="https://www.openstreetmap.org/export/embed.html?bbox=-7.5,37.0,-5.5,40.5&layer=mapnik" 
                allowfullscreen>
            </iframe>
        </div>

        <!-- Información de contacto -->
        <div class="contacto">
            <h2>¿Donde nos encontramos?</h2>
            <p>
                <i class="fas fa-map-marker-alt"></i> <!-- Icono de ubicación -->
                Dirección: Convento del Rosario, Av. Fuente del Maestre, 78, 06300 Zafra, Badajoz
            </p>
            <p>
                <i class="fas fa-phone"></i> <!-- Icono de teléfono -->
                Teléfono: 924 02 99 24

            </p>
            <!--Modificado dia 6: He quitado un atributo que sobraba(No afecta al javascript, solo es diseño)-->
        </div>
    </div>
    <!-- Modal para datos de contacto -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" id="closeContactModal">&times;</button>
            <h3>Datos de Contacto</h3>
            <p><strong>Teléfono:</strong> 924 029 924</p>
            <p><strong>Email:</strong> axo@gmail.com</p>
            <p><strong>Redes Sociales:</strong></p>
            <ul class="Redes">
                <li class="facebook"><a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a></li>
                <li class="twitter"><a href="https://www.twitter.com" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                <li class="instagram"><a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </ul>
        </div>
    </div>

    <!-- Modal para agregar evento -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <button class="" id="closeModal">&times;</button>
            <h3>Proponer un Evento</h3>
            <form id="eventForm" action="../php/crearEvento.php">
                <input type="text" id="eventTitle" name="nombreEvento" placeholder="Evento" required>
                <textarea id="eventDescription" name="descripcion" placeholder="Descripción" required></textarea>
                <input type="text" id="eventLocation" name="ubicacion" placeholder="Ubicación" required>
                <input type="text" id="eventTarget" name="target" placeholder="A quién va dirigido" required>
                <!--Agregado dia 7(Falta meterlo en javascript)-->
                <labe>Hora de inicio del evento</labe>
                <input type="time" id="eventTime" name="hora" placeholder="Hora" required>
                <label>Hora de finalizacion del evento</label>
                <input type="time" id="eventTimeEnd" name="hora" placeholder="Hora" required>
                <input type="hidden" id="selectedImageId" name="id_imagen"> <!-- Campo oculto para el ID de la imagen(Falta por hacer para la base de datos) -->
                <label>Selecciona una imagen de fondo</label>
                <!--Muestra las imagenes de la select hecha del principio del documento-->
                <div class="imagenes">
                <?php
                while($row = mysqli_fetch_assoc($resultado)) {
                    $imagenBase64 = base64_encode($row['imagen']);
                    echo '<img src="data:image/jpeg;base64,' . $imagenBase64 . '" alt="Imagen" class="selectable-image">';
                }
                ?>
</div>
                <button class="modal-close2" type="submit">Guardar Evento</button>
            </form>
        </div>
    </div>
    <script src="js/calendario.js"></script>
</body>
</html>
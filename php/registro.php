<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=., initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style_registro.css">
</head>
<body>
    <main>
    <div class="contenedor">
        <div class="sub_contenedor1">
            <img src="../img/A2.png" width="300px" alt="Imagen empresarial">
        
        </div>

        <div class="sub_contenedor2">
            <form method="POST">
                <h2>Registro</h2>
                <div class="usuarios">
                    <div class="campo">
                        <img src="../img/icons8-usuario-50(1).png" alt="Icono de usuario">
                        <input type="text" name="nombre" placeholder="Nombre de usuario">
                    </div>

                    <div class="campo">
                        <img src="../img/icons8-nuevo-post-50(1).png" alt="Icono de usuario">
                        <input type="text" name="correo" placeholder="Correo electronico">
                    </div>

                     <div class="campo">
                        <img src="../img/icons8-marcador-50.png" alt="Icono de usuario">
                        <input type="text" name="localidad" placeholder="Localidad">
                    </div>

                    <div class="campo">
                        <img src="../img/icons8-nuevo-post-50(1).png" alt="Icono de usuario">
                        <input type="tel" name="tlf" placeholder="Numero de telefono">
                    </div>

                     <div class="campo">
                        <img src="../img/icons8-contraseña-30.png" alt="Icono de usuario">
                        <input type="password" name="contraseña" placeholder="Contraseña">
                    </div>
                </div>
                <input class="boton" type="submit" value="Registrate">

                <div class="linea-horizontal">
                    <p> ¿Eres cliente? Inicia sesion</p>
                </div>
                <a class="boton" href="../views/sesion.html">Iniciar sesion</a>

            </form>
        </div>

    </div>
    </main>   
    
    
    <?php
    include_once 'conexion.php';
    include_once 'tablaUsuario.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        //Recuperar valores del HTML
        $nombre = $_POST['nombre'];
        $password = $_POST['contraseña'];
        $correo = $_POST['correo'];
        $localidad= $_POST['localidad'];
        $tlf = $_POST['tlf'];
        
        //Comprobar si el usuario existe
        $resultado = comprobarExisteUsuario($conn, $nombre);

        //Si existe muestra un mensaje de error
        if(mysqli_num_rows($resultado) > 0) {
            echo '
            <script>
            alert("Este nombre ya esta en uso");
            </script>
            ';
        } else { //Si no existe crea el usuario
            insertarUsario($conn, $nombre, $correo, $password, $localidad, $tlf);
        }

    }
    ?>
</body>
</html>
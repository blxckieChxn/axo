<?php
session_start();
include_once 'conexion.php';
include_once 'tablaUsuario.php';
include_once 'tablaEventos.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //recuperar variables del HTML
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo']; 
    $contraseña = $_POST['contraseña'];

    //Comprobamos que las credenciales son validas
    $resultado = compCredencialesUsuario($conn, $nombre, $contraseña);

    if($resultado > 0) //inicia sesion
    {
        //TODO variables de sesion
        $_SESSION["cookie"]="";
        $_SESSION["username"] = $nombre;
        //Cargar en esta variable la fecha del dia seleccionado en el calendario
        $fecha = date("YYYY-M-d"); //TODO

        //Aqui cargamos el JSON de eventos ya que en el index no podemos incluir PHP
        //Asique se cargarán justo cuando el usuario inicie sesion
        cargarEventosJSON($conn, $fecha);

        header("Location: ../index.php");
    } else { //credenciales no validas -> mensaje de error
        echo '
        <script>
        alert("Datos incorrectos");
        </script>
        ';
        header("location: inicio.html");

    } 

}
?>
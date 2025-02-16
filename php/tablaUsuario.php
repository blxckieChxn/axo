<?php
include_once 'conexion.php';

//Funcion para crear un usuario
//Comprueba si existe el nombre de usuario
function comprobarExisteUsuario($conn, $nombre){
    $consulta = "SELECT * FROM usuarios where nombre= '$nombre'";
    $resultado = mysqli_query($conn, $consulta);
    return $resultado;
};

//Funcion para crear un usuario
//Inserta el usuario en la base de datos
function insertarUsario($conn, $nombre, $correo, $password, $localidad, $tlf){ //QUE PARAMETROS??
        $consulta = "INSERT INTO usuarios (uid, nombre, correo, passwd, localidad, tlf) values ('' , '$nombre', '$correo', SHA2('$password', 256), '$localidad', '$tlf')";
        $result = mysqli_query($conn, $consulta);
            
        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Crear sesion exitoso
            session_start();
            $_SESSION['username'] = $nombre;
            header("Location: sesion.html");

        } else {
            // Credenciales inválidas, inicio de sesión fallido
            echo "Nombre de usuario o contraseña incorrectos.";
        }
    };

//Funcion para iniciar sesion
//Comprueba que el usuario y la contraseña coinciden con la base de datos
function compCredencialesUsuario($conn, $nombre, $contraseña){
    $sql = "Select * from usuarios where nombre = '$nombre' AND passwd = '$contraseña'";
    $resultado = mysqli_query($conn, $sql);

    return $resultado;
};
?>
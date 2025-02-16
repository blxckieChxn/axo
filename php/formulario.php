<?php

include_once 'tablaEventos.php';

//obtener valores de los campos del registro
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nomEvento = $_POST["nombreEvento"];
    $descripcion = $_POST["descripcion"];
    $ubi = $_POST["ubicacion"];
    $categoria = $_POST["target"];
    $horaIni = strval( $_POST["horaInicio"]);
    $horaFin = strval( $_POST["horaFin"]); //Pasar las horas a string 

    //Tratamiento del string para quitarle ":"
    list($hInici,$mInici) = explode(":",$horaIni);
    list($hFin,$mFin) = explode(":",$horaFin);

    echo "<br> Hora inicio: $hInici : $mInici";
    echo "<br> Hora fin: $hFin : $mFin";

    //Calculo de duracion del evento
    $hDuracion = $hFin - $hInici;
    $mDuracion = $mFin - $mInici;

    echo "<br> El evento dura: $hDuracion horas y $mDuracion minutos.";

    //Recuperar UID del creador del evento
    //$uid = $_SESSION["UID"]; //TODO implementar UID
    // Ejecutar la query
    
    echo "<br>Evento: $nomEvento";
    
    
    //Comprobar si el evento ya existe
    $comp = compEventoExiste(/*$uid*/ $conn, $nomEvento);
    
    if (mysqli_num_rows($comp) > 0) { //Si existe muestra un mensaje de error
        echo '
            <script>
            alert("Este nombre ya esta en uso");
            </script>
            ';

    } else { //Si no existe crea el evento
        crearEvento(/*$uid*/$conn, $nomEvento, $descripcion, $ubi, $categoria, $horaIni, $hDuracion);
        echo '
            <script>
            alert("Evento creado");
            </script>
            ';
    }

    

    header('Location: ../index.html');
}

?>
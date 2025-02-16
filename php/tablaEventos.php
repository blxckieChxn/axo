<?php
include_once 'conexion.php';

    /*Funcion para crear un evento y almacenarlo en la BD
      No devuelve nada
    */
    function crearEvento(/*$uid*/ $conn, $creador, $nomEvento, $descripcion, $ubi, $categoria, $fecha, $horaIni, $duracion, $id_imagen){
        $sql = "INSERT INTO eventos (creador, nombre, descripcion, ubicacion, categoria, fecha, hora, duracion, id_imagen) VALUES ($creador, '$nomEvento', '$descripcion', '$ubi', '$categoria', '$fecha', '$horaIni', $duracion, $id_imagen)";
      
        $ejecutar = mysqli_query($conn, $sql);
    };

    /*Funcion que comprueba si un evento existe comparando por el nombre
      Si no existe: Devuelve 1 
      Si existe: Devuelve las filas de la BD
    */
    function compEventoExiste(/*$uid*/$conn, $nomEvento){ //Comprueba si existe ya un evento con el mismo nombre
        $consulta = "SELECT * FROM eventos where nombre = '$nomEvento'";
        $resultado = mysqli_query($conn, $consulta);
        if($resultado == null){
            return 1;
        } else {
            return $resultado;
        }
        
    };

    /*  TODO
        Funcion que borra un evento de la tabla eventos de la BD
        No devuelve nada
    */
    function borrarEvento($conn){

    };

    /*Funcion que recupera los eventos de la BD
      No devuelve nada pero almacena el resultado en un JSON
    */
    function cargarEventosJSON($conn){
        //Array de eventos
        $eventos = array();

        // Inicializar el archivo eventos JSON
        $archivo_json = '../JSON/mostrarEventos.json';
        
        //Carga desde base de datos
        $sql = "SELECT * FROM eventos";
        $resultado = $conn->query($sql);

        // Abrir el archivo en modo escritura
        $file = fopen($archivo_json, 'w');

        // Verificar si la consulta tiene resultados
        if ($resultado->num_rows > 0) {

            //VARIABLES DE SOPORTE
            //Obtenemos la cantidad de filas que devuelve la consulta SQL
            $filas=$resultado->num_rows;
            //contador;
            $i=0; 

            //Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
            fwrite($file, "[");

            // Recorrer todas las filas y volcar cada fila como una línea JSON en el archivo
            while ($fila = $resultado->fetch_assoc()) {
                
                // Convertir la fila en formato JSON
                $json_fila = json_encode($fila);
                
                //La ultima fila no debe añadir el caracter ',' ya que da error en el formato JSON
                //Asique vamos a contar todas las filas y en la ultima no añadimos el caracter ','
                //Por eso '$filas-1'
                if($i<$filas-1){

                    // Escribir la fila JSON en el archivo (y agregar salto de línea)
                    fwrite($file, $json_fila . "," . PHP_EOL);

                    //Aumentamos el contador de fila
                    $i++;
                } else {
                    // Escribir la ULTIMA fila JSON en el archivo (y agregar salto de línea)
                    fwrite($file, $json_fila . PHP_EOL);
                }                
            }

            //Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
            fwrite($file, "]");
        } else {
            echo "No se encontraron eventos.";
        }

        // Cerrar el archivo
        fclose($file);

        // Cerrar la conexión
        $conn->close();
    }

    /*Funcion que recupera los eventos de la BD del día seleccionado
      No devuelve nada pero almacena el resultado en un JSON
    */
    function cargarEventosDia($conn, $fecha){
        //Array de eventos
        $eventos = array();

        // Inicializar el archivo eventosDia JSON
        $archivo_json = '../json/mostrarEventosDia.json';
        
        //Carga desde base de datos
        $sql = "SELECT * FROM eventos where fecha='$fecha'";
        $resultado = $conn->query($sql);

        // Abrir el archivo en modo escritura
        $file = fopen($archivo_json, 'w');

        // Verificar si la consulta tiene resultados
        if ($resultado->num_rows > 0) {

            //VARIABLES DE SOPORTE
            //Obtenemos la cantidad de filas que devuelve la consulta SQL
            $filas=$resultado->num_rows;
            //contador;
            $i=0; 

            //Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
            fwrite($file, "[");

            // Recorrer todas las filas y volcar cada fila como una línea JSON en el archivo
            while ($fila = $resultado->fetch_assoc()) {
                
                // Convertir la fila en formato JSON
                $json_fila = json_encode($fila);
                
                //La ultima fila no debe añadir el caracter ',' ya que da error en el formato JSON
                //Asique vamos a contar todas las filas y en la ultima no añadimos el caracter ','
                //Por eso '$filas-1'
                if($i<$filas-1){

                    // Escribir la fila JSON en el archivo (y agregar salto de línea)
                    fwrite($file, $json_fila . "," . PHP_EOL);

                    //Aumentamos el contador de fila
                    $i++;
                } else {
                    // Escribir la ULTIMA fila JSON en el archivo (y agregar salto de línea)
                    fwrite($file, $json_fila . PHP_EOL);
                }                
            }

            //Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
            fwrite($file, "]");
        } else {
            echo "No se encontraron eventos.";
        }

        // Cerrar el archivo
        fclose($file);

        // Cerrar la conexión
        $conn->close();
    }

    /*Funcion que sube los eventos a la BD 
      No devuelve nada
      Lee la info de un JSON 
    */

    function subirEventoDB($conn){
        // Leer el archivo JSON
        $json = file_get_contents('../JSON/subirEvento.json');      
        
        // Decodificar el JSON a un array asociativo
        $data = json_decode($json, true);

        //Array dentro de array
        $creador=$data[0]['creator'];
        $nombre = $data[0]['title'];
        $descripcion = $data[0]['description'];
        $ubicacion = $data[0]['location'];
        $categoria = $data[0]['target'];
        $horaIni = strval($data[0]['time']);
        $horaFin = strval($data[0]['timeEnd']);
        $date = $data[0]['dateSelected'];
        
        //Tratamiento del string para quitarle ":"
        list($hInici,$mInici) = explode(":",$horaIni);
        list($hFin,$mFin) = explode(":",$horaFin);


        //Calculo de duracion del evento
        $hFin = (int)$hFin;
        $hInici = (int)$horaIni;
        $hDuracion = $hFin - $hInici;

        //1 = id_imagen
        crearEvento($conn, $creador, $nombre, $descripcion, $ubicacion, $categoria, $date, $horaIni, $hDuracion, 1);
    }

?>
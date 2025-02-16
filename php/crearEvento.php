<?php
include_once 'conexion.php';
include_once 'tablaEventos.php';




// Obtener el cuerpo de la solicitud (el JSON enviado)
$jsonData = file_get_contents('php://input');

// Decodificar el JSON a un array asociativo
$data = json_decode($jsonData, true);

// Acceder a los datos y procesarlos
if ($data) 
{
    $titulo = $data['title'];
    $descripcion = $data['description'];
    $ubicacion= $data['location'];
    $categoria = $data['target'];
    $horaIni = $data['time'];
    $horaFin = $data['timeEnd'];
    $creador = $data['creator'];
    $fecha = $data['dateSelected'];

    $response = [
        'status' => 'success',
        'message' => 'Datos recibidos correctamente',
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'ubicacion'=> $ubicacion,
        'categoria' => $categoria,
        'horaIni' => $horaIni,
        'horaFin' => $horaFin,
        'creador' => $creador,
        'fecha' => $fecha,
        
    ];

    // Devolver la respuesta al cliente
    echo json_encode($response);
    // Llamar a la función para guardar el evento en la base de datos
    //subirEventoDB($conn, $titulo, $descripcion, $ubicacion, $categoria, $horaIni);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos válidos.']);
}


// Inicializar el archivo subirEvento JSON
$archivo_json = '../json/subirEvento.json';
if (file_exists($archivo_json)) {
    if (unlink($archivo_json)) {
        //echo "El archivo ha sido eliminado exitosamente.";
    } else {
        echo "Hubo un error al intentar eliminar el archivo.";
    }
} else {
    echo "El archivo no existe.";
}

// Abrir el archivo en modo escritura
$file = fopen($archivo_json, 'w');

//Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
fwrite($file, "[");

 //Tratamiento del formato del fichero JSON
 fwrite($file, $jsonData);

 //Tratamiento del formato del fichero JSON (Debe iniciar con un corchete y acabar con otro)
fwrite($file, "]");

 // Cerrar el archivo
 fclose($file);

 //Subir evento a BD
subirEventoDB($conn);
?>
<?php

$servername = "localhost";
$db_username = "root"; // Usuario de la base de datos
$db_password = ""; // Contraseña de la base de datos
$dbname = "axo";//nombre abse de datos

// Crear conexión
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Verificar la conexión
if ($conn->connect_error)
{
    die("Error en la conexión: " . $conn->connect_error);
}

?>
<?php
session_start();
session_destroy();
header('Location: ../views/sesion.html');
exit();
?>
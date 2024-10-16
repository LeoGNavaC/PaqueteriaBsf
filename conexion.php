<?php
  $host 	= 'localhost';
  $nom 	= 'root';
  $pass 	= '';
  $db 	= 'paqueteriabsf';

  $conn = mysqli_connect($host, $nom, $pass, $db);

  if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
  }	
?>

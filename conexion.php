<?php
  $host 	= 'localhost';
  $nom 	= 'root';
  $pass 	= '';
  $db 	= 'logincrud10';//hambiente de pruebas
  //Para que pueda realizar diferentes tipos de conexiones tengo que usar la misma variable, por ejemplo usar conn para las consultas SQL hola mundooooooo
  $conn = mysqli_connect($host, $nom, $pass, $db);

  if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
  }	
?>


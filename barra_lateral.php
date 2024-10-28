<?php
	session_start();
	include('conexion.php');
	if(isset($_SESSION['usuarioingresando'])){
		$usuarioingresado = $_SESSION['usuarioingresando'];
		$buscandousu = mysqli_query($conn,"SELECT * FROM usuarios WHERE correo = '".$usuarioingresado."'");
		$mostrar=mysqli_fetch_array($buscandousu);	
	} else {
		header('location: index.php');
	}
?>

<html>
	<head>
		<title>Paqueteria BFS</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="preload" href="normalize.css" as="style">
        <link rel="stylesheet" href="normalize.css">
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="BarraLateral">
			<ul><!--Se encuentran los links a las demas paginas-->
				<li><a href="productos_tabla.php" >• LLegada del paquete</a></li>
				<li><a href="categoria_tabla.php" >• Captura de empresas (Paqueteria)</a></li>
				<li><a href="datos_bsf.php">• Entrega Paqueteria</a></li>
				<!--<li><a href="productos_tabla_correspondencia.php">• Llegada de correspondencia</a></li><!**************modificacion****-->
				<!--<li><a href="categoria_tabla_correspondencia.php">• Captura de empresas (correspondencia)</a></li><!**************modificacion****-->
				<!--<li><a href="datos_bsf_correspondencia.php">• Entrega correspondencia</a></li><!**************modificacion****-->
				<li><a href="cerrar_sesion.php" >• Cerrar sesión</a></li>
			</ul>
			<hr>
		</div>
	</body>
</html>
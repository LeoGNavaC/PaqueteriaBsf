<?php 
	include('conexion.php');
	include("categoria_tabla_correspondencia.php");//*************Se realizo modificacion */

	$pagina = $_GET['pag'];
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">   
		<link rel="preload" href="normalize.css" as="style">
        <link rel="stylesheet" href="normalize.css"> 
		<title>Paqueteria BSF </title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="caja_popup2"> 
			<form class="contenedor_popup" method="POST">
				<table>
					<tr><th colspan="2" >Agregar Paqueteria</th></tr>
					<tr><!--Nombre de la paqueteria-->
						<td>Nombre de la empresa</td>
						<td><input type="text" name="txtnom" autocomplete="off" class="CajaTexto" required></td>
					</tr>
					<tr><!--Boton para poder confirmar--> 	
						<td colspan="2" >
							<?php echo "<a class='BotonesTeam' href=\"categoria_tabla_correspondencia.php?pag=$pagina\">Cancelar</a>";?>&nbsp;<!--***********Se realizo modificacion*******-->
							<input class='BotonesTeam' type="submit" name="btnregistrar" value="Registrar" onClick="javascript: return confirm('¿Deseas registrar esta categoría?');">
						</td>
					</tr>
				</table>
			</form>
 		</div>
	</body>
</html>

<?php
	if(isset($_POST['btnregistrar']))
	{   
		$vainom 	= $_POST['txtnom'];

		$queryadd	= $conn->prepare("INSERT INTO categoria_productos_correspondencia(nombre) VALUES(?)");//*************Se realizo modificacion */
		$queryadd->bind_param("s", $vainom);		
		
		if($queryadd->execute())
		{
			
			echo "<script>window.location= 'categoria_tabla_correspondencia.php?pag=$pagina' </script>";//*************Se realizo modificacion */
			
		}else
		{

			echo "<script>alert('Id duplicado, intenta otra vez');</script>";
			
		}
	}
?>


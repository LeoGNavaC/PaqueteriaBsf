<?php 
	include("conexion.php");
	include("datos_bsf_correspondencia.php");//***************se realizo modificacion */

	$pagina = $_GET['pag'];
	$id = $_GET['id'];

	//$querybuscar = mysqli_query($conn, "SELECT id, nombresocio, numeroguia, paque, estatus, fecha_entrega FROM productos WHERE id = '$id'");//***********se realizo modificacion */
	$querybuscar = mysqli_query($conn, "SELECT id,numeroguia,paque,nombresocio,estatus FROM productos_correspondencia WHERE id = '$id'"); //*******se realizo modificacion */
	
	while($mostrar = mysqli_fetch_array($querybuscar)){	
		$proid 		= $mostrar['id'];
		$pronomso	= $mostrar['nombresocio'];
		$prodes	    = $mostrar['numeroguia'];
		$procat	    = $mostrar['paque'];
		$proest		= $mostrar['estatus'];
		//$proent		= $mostrar['fecha_entrega'];
		
	}
?>

<html>
<meta charset='UTF-8'>
	<body>
		<div class="caja_popup4">
			<form class="contenedor_popup3" method="POST">
				<table>
					<tr><th colspan="2">Estatus del Paquete</th></tr>	
					<tr> 			
						<tr style="display: none"> 		
							<td><b>Id: </b></td>
							<td><input class="CajaTexto" type="number" name="id" value="<?php echo $proid;?>" readonly></td>
						</tr>

						<tr> 
						<td><b>N°Guía: </b></td>
						<td><?php echo $prodes;?></td>
						</tr>

					<tr> 
						<td><b><Param>Paqueteria</Param>: </b></td>
						<td><?php echo $procat;?></td>
					</tr>

					<tr>
    					<td><b>Escribe: </b></td>
						<td><textarea class="CajaTexto" type="text" name="fue" style="width: 283px; height: 90px;"  required><?php echo $proest;?></textarea></td>
						<!--<td><input class="CajaTexto" type="text" name="txtest" value="<?php /*echo $proest;*/ ?>" style="width: 390px; height: 204px;" required></td>-->
					</tr>							

					<!--**********++se realizo modificacion
					<tr> 
						<td><b>Fecha/No entregado: </b></td>
						<td><input class="CajaTexto" type="datetime-local" step="any" name="fecha" value="<?php echo $proent; ?>" required ></td>
					</tr>
					-->

						<td colspan="2" >
							<input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar" onClick="javascript: return confirm('¿Verifico que los datos sean correctos?');">
							<?php echo "<a class='BotonesTeam' href=\"datos_bsfcorrespondencia.php?pag=$pagina\">Cancelar</a>";?>&nbsp;<!--************Se realizo modificacion******-->
							
<!--Crear la ventana para envíar el correo al residente-->
							
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>

<?php
		
		if(isset($_POST['btnregistrar'])){

			/* Tome la fecha de la region */
			date_default_timezone_set('America/Mexico_City');//*****************se realizo modificacion */

			$proid1 	= $_POST['id'];    
			$proest1	= $_POST['fue']; // Cambié la variable $proest a $proest1
			$proent1	= date("Y-m-d H:i:s");
			$procorreoS = $_POST['email']; //correo de los socios
			$prodes1 = $_POST['gia'];
			$procat1 = $_POST['paque'];
	
			$querymodificar = mysqli_query($conn, "UPDATE productos_correspondencia SET numeroguia='$prodes',paque='$procat',estatus='$proest1',fecha_entrega='$proent1' WHERE id = '$proid1'");
			echo "<script>window.location= 'datos_bsf_correspondencia.php?pag=$pagina' </script>";//***************Se realizo modificacion */
		}
	?>

<!--Nota: para poder enviar los datos de un formulario a una base de datos se usa el name no el id-->
<!--action="https://formsubmit.co/leonava988@gmail.com"   ********************  Esto sirve para mandar el correo-->

					
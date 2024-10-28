numeroguia<!--En esta pagina podemos visualizar las caracteristicas del dato, pero sin poder modificar algo-->
<?php 
	include("conexion.php");
	include("productos_tabla_correspondencia.php");
	$pagina = $_GET['pag'];
	$id = $_GET['id'];

	$querybuscar = mysqli_query($conn, "SELECT id,nombre,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios 
	FROM productos_correspondencia where id = '$id'");
	
	while($mostrar = mysqli_fetch_array($querybuscar)){
		$proid 	= $mostrar['id'];
		$pronom	= $mostrar['nombre'];
		$prodes	= $mostrar['numeroguia'];
		$propre	= $mostrar['fecha'];
		$propaquete= $mostrar['paque'];
		$pronomso=$mostrar['nombresocio'];
		$prodir = $mostrar['direccion'];
		$proori	= $mostrar['orientacion'];
		$procom	= $mostrar['comentarios'];
	}
?>

<html>
	<body>
		<div class="caja_popup2">
			<form class="contenedor_popup" method="POST">
				<table>

					<tr>
						<th colspan="2">Ver datos</th>
					</tr>

					<tr> 
						<td><b>Id:</b></td>
						<td><?php echo $proid;?></td>
					</tr>	

					<tr> 
						<td><b>Nombre del repartidor: </b></td>
						<td><?php echo $pronom;?></td>
					</tr>

					<tr> 
						<td><b>N°Guía: </b></td>
						<td><?php echo $prodes;?></td>
					</tr>

					<tr> 
						<td><b>Fecha: </b></td>
						<td><?php echo $propre;?></td>
					</tr>

					<tr> 
						<td><b><Param>Paqueteria</Param>: </b></td>
						<td><?php echo $propaquete;?></td>
					</tr>

					<tr>
						<td><b>Nombre del titular: </b></td>
						<td><?php echo $pronomso;?></td>
					</tr>

					<tr>
						<td><b>Direccion: </b></td><!--La etiqueta b nos ayuda a que se vea mas negrita la letra-->
						<td><?php echo $prodir;?></td>
					</tr>

					<tr>
						<td><b>Orientacion: </b></td>
						<td><?php echo $proori;?></td>
					</tr>

					<tr>
						<td><b>Comentarios</b></td>
						<td><?php echo $procom;?></td>
					</tr>
					
					<tr>
						<td colspan="2">
							<?php echo "<a class='BotonesTeam' href=\"productos_tabla_correspondencia.php?pag=$pagina\">Regresar</a>";?>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>


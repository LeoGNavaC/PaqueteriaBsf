<!--Muestra los apartados que se encuentran aun lado izquierdo de la pantalla-->
<?php
	include('conexion.php');
	include("barra_lateral.php");
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Paquetería BSF</title>
	<body>
		<div class="ContenedorPrincipal">	
			<?php
	
				$filasmax = 10;//Este me ayuda a mostrar una cantidad de datos en la tabla, en este caso muestra hasta 10
	
				if (isset($_GET['pag'])){
					$pagina = $_GET['pag'];
				} else {
					$pagina = 1;
				}
	
				if(isset($_POST['btnbuscar'])){
					$buscar = $_POST['txtbuscar'];
					$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.numeroguia,pro.fecha,pro.paque,pro.nombresocio,pro.direccion,pro.orientacion,pro.foto_nombre,pro.comentarios,pro.estatus,pro.fecha_entrega,pro.receptor,cat.nombre as categoria 
					FROM productos pro INNER JOIN categoria_productos cat ON pro.categoria_id=cat.id WHERE pro.direccion LIKE '%".$buscar."%' OR pro.numeroguia LIKE '%".$buscar."%'");//se realizo modificacion
				}
				else{//***********SE REALIZO MODIFICACION */
					$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.numeroguia,pro.fecha,pro.paque,pro.nombresocio,pro.direccion,pro.orientacion,pro.foto_nombre,pro.comentarios,pro.estatus,pro.fecha_entrega,pro.receptor,cat.nombre as categoria 
					FROM productos pro, categoria_productos cat WHERE pro.categoria_id=cat.id ORDER BY pro.id DESC LIMIT " . (($pagina - 1) * $filasmax)  . "," . $filasmax);
				}	
	
				$resultadoMaximo = mysqli_query($conn, "SELECT count(*) as num_productos FROM productos");
	
				$maxusutabla = mysqli_fetch_assoc($resultadoMaximo)['num_productos'];
		
			?>
			<div class="ContenedorTabla">
				<form method="POST">
					<h2>Tabla del repartidor</h2>
		
					<div class="ContBuscar">
						<div style="float: left;">
							<a href="datos_bsf.php" class="BotonesTeam">Inicio</a>
							<a href="productos_exportar.php" class="BotonesTeam">Exportar</a>
							<input class="BotonesTeam" type="submit" value="Buscar" name="btnbuscar">
							<input class="CajaTextoBuscar" type="text" name="txtbuscar"  placeholder="Ingresar N° de guía o nombre del socio" autocomplete="off" >
						</div>
						<div style="float:right;">
							
						</div>
					</div>
				</form>
					<table>
						<tr>
							<th>Id</th>
							<th>N° Guía</th>
							<th>Fecha/recepción</th>
							<th>Paqueteria(empresa)</th>
							<th>Dirección</th>
							<th>Nombre del residente</th>
							<th>Orientacion</th>
							<th>Foto</th>
							<th>Comentarios</th>
							<th>Estatus</th>
							<th>Fecha/entrega</th>
							<th>Receptor</th>
							<th>Acción</th>
						</tr>
		
						<?php

							while ($mostrar = mysqli_fetch_assoc($sqlusu)) {

								echo "<tr>";
								echo "<td>".$mostrar['id']."</td>";
								echo "<td>".$mostrar['numeroguia']."</td>";
								echo "<td>".$mostrar['fecha']."</td>";
								//echo "<td>".$mostrar['paque']."</td>";//Muestra el numero de la empresa, nos referimos a categoria_id, y pasa porque esta heredando lo de la llave foreana
								echo "<td>".$mostrar['categoria']."</td>";
								echo "<td>".$mostrar['nombresocio']."</td>";
								echo "<td>".$mostrar['direccion']."</td>";
								echo "<td>".$mostrar['orientacion']."</td>";
								echo "<td>".$mostrar['foto_nombre']."</td>"; 
								//echo "<td style='width:30%'><img src='data:image/jpg;base64,".base64_encode($mostrar['foto']).";'></td>";// --- echo "<td>".$mostrar['foto']."</td>";
								echo "<td>".$mostrar['comentarios']."</td>";
								if ($mostrar["estatus"] == "Entregado") {
									//echo "Holaaaaaa!!!!!!";
									echo "<td style='width:10%'><font color='green'><b>".$mostrar['estatus']."</font></td>";
								} else {
									//echo "Holiiiiii!!!!!!";
									echo "<td style='width:10%'><font color='red'><b>".$mostrar['estatus']."</font></td>";
								}
								//echo "<td style='width:50%'>".$mostrar['estatus']."</td>";
								echo "<td>".$mostrar['fecha_entrega']."</td>";
								echo "<td>".$mostrar['receptor']."</td>";  
								echo "<td style='width:25%'>
										<a class='BotonesTeam2' href=\"estatus.php?id=$mostrar[id]&pag=$pagina\">&#x2714;</a>
										<a class='BotonesTeam3' href=\"estatus2.php?id=$mostrar[id]&pag=$pagina\">&#x2718;</a>
								</td>";  
								echo "</tr>";
							}
						?>
					</table>
					
					<div class="contador" style='text-align:right'>
						<br>
						<?php echo "Total de registros: ".$maxusutabla;?>
					</div>
				</div>
				<div style='text-align:right'>
					<br>
				</div>
				<div style="text-align:center">
					<?php
						if (isset($_GET['pag'])) {
						if ($_GET['pag'] > 1) {
					?>
					<a class="BotonesTeam4" href="datos_bsf.php?pag=<?php echo $_GET['pag'] - 1; ?>">Anterior</a>
					<?php
						} 
						else 
						{
						?>
					<a class="BotonesTeam4" href="#" style="pointer-events: none">Anterior</a>
					<?php
						}
					?>
	
				<?php
					} 
					else 
					{
				?>
				<a class="BotonesTeam4" href="#" style="pointer-events: none">Anterior</a>
				<?php
					}
					
					if (isset($_GET['pag'])) {
					if ((($pagina) * $filasmax) < $maxusutabla) {
				?>
				<a class="BotonesTeam4" href="datos_bsf.php?pag=<?php echo $_GET['pag'] + 1; ?>">Siguiente</a>
				<?php
					} else {
				?>
				<a class="BotonesTeam4" href="#" style="pointer-events: none">Siguiente</a>
				<?php
					}
				?>
				<?php
					} else {
				?>
				<a class="BotonesTeam4" href="datos_bsf.php?pag=2">Siguiente</a>
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>


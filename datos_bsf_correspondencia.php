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
				//Esto sirve para ubicar la pagina en la que estamos
				if (isset($_GET['pag'])){
					$pagina = $_GET['pag'];
				} else {
					$pagina = 1;
				}
	
				if(isset($_POST['btnbuscar'])){//**********+Se realizo modificacion */
					/*$buscar = $_POST['txtbuscar'];
					$sqlbus = $conn->prepare("SELECT id,nombre,numeroguia,fecha,paque,direccion,comentarios,estatus,fecha_entrega FROM productos_correspondencia WHERE numeroguia LIKE ? OR nombre LIKE ?");
					$parambus = "%".$buscar."%";
					$sqlbus->bind_param("ss",$parambus,$parambus);
					$sqlbus->execute();*/
					$buscar = $_POST['txtbuscar'];
					$sqlusu = mysqli_query($conn, "SELECT id,nombre,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega FROM productos_correspondencia WHERE numeroguia LIKE '%".$buscar."%' OR direccion LIKE '%".$buscar."%'");//***Se realizo modificacion */

				}
				else{//***********SE REALIZO MODIFICACION */
					/*$sqlbus = $conn->prepare("SELECT id,nombre,numeroguia,fecha,paque,direccion,comentarios,estatus,fecha_entrega FROM productos_correspondencia ORDER BY id DESC LIMIT " .(($pagina - 1) * $filasmax) . "," . $filasmax);
					$sqlbus->execute();
					*/
					$sqlusu = mysqli_query($conn, "SELECT id,nombre,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega FROM productos_correspondencia ORDER BY id DESC LIMIT " . (($pagina - 1) * $filasmax) . "," . $filasmax);//********Se realizo modificacion */
					
				}	
	
				$resultadoMaximo = mysqli_query($conn, "SELECT count(*) as num_productos FROM productos_correspondencia");
	
				$maxusutabla = mysqli_fetch_assoc($resultadoMaximo)['num_productos'];
		
			?>
			<div class="ContenedorTabla">
				<form method="POST">
					<h2>Tabla del repartidor</h2>
		
					<div class="ContBuscar">
						<div style="float: left;">
							<a href="datos_bsf_correspondencia.php" class="BotonesTeam">Inicio</a>
							<a href="productos_exportar_correspondencia.php" class="BotonesTeam">Exportar</a>
							<input class="BotonesTeam" type="submit" value="Buscar" name="btnbuscar">
							<input class="CajaTextoBuscar" type="text" name="txtbuscar"  placeholder="Ingresar N° de guía o nombre del socio" autocomplete="off" >
						</div>
						<div style="float:right;">
							
						</div>
					</div>
				</form>
					<table>
						<tr><!--*************Se realizo modificacion**-->
							<th>Id</th>
							<th>Nombre del repartidor</th>
							<th>N° Guía</th>
							<th>Fecha/recepción</th>
							<th>Empresa</th>
							<th>Nombre del titular</th>
							<th>Dirección</th>
							<th>Orientacion</th>
							<th>Comentarios</th>
							<th>Estatus</th>
							<th>Fecha/entrega</th>
							<th>Acción</th>
						</tr>
		
						<?php

							while ($mostrar = mysqli_fetch_assoc($sqlusu)) {//*********Se realizo modificacion */

								echo "<tr>";
									echo "<td>".$mostrar['id']."</td>";
									echo "<td>".$mostrar['nombre']."</td>";
									echo "<td>".$mostrar['numeroguia']."</td>";
									echo "<td>".$mostrar['fecha']."</td>";
									//echo "<td>".$mostrar['paque']."</td>";//Muestra el numero de la empresa, nos referimos a categoria_id, y pasa porque esta heredando lo de la llave foreana
									echo "<td>".$mostrar['paque']."</td>";
									echo "<td>".$mostrar['direccion']."</td>";
									echo "<td>".$mostrar['nombresocio']."</td>";
									echo "<td>".$mostrar['orientacion']."</td>";
									//echo "<td style='width:30%'><img src='data:image/jpg;base64,".base64_encode($mostrar['foto']).";'></td>";// --- echo "<td>".$mostrar['foto']."</td>";
									echo "<td style='width:40%'>".$mostrar['comentarios']."</td>";
									if ($mostrar["estatus"] == "Entregado") {
										//echo "Holaaaaaa!!!!!!";
										echo "<td style='width:10%'><font color='green'><b>".$mostrar['estatus']."</font></td>";
									} else {
										//echo "Holiiiiii!!!!!!";
										echo "<td style='width:10%'><font color='red'><b>".$mostrar['estatus']."</font></td>";
									}
									//echo "<td style='width:50%'>".$mostrar['estatus']."</td>";
									echo "<td>".$mostrar['fecha_entrega']."</td>";
									echo "<td style='width:25%'>
										<a class='BotonesTeam2' href=\"estatus_correspondencia.php?id=$mostrar[id]&pag=$pagina\">&#x2714;</a>
										<!--<a class='BotonesTeam3' href=\"estatus2_correspondencia.php?id=$mostrar[id]&pag=$pagina\">&#x2718;</a>-->
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
					<a class="BotonesTeam4" href="datos_bsf_correspondencia.php?pag=<?php echo $_GET['pag'] - 1; ?>">Anterior</a><!--****Se realizo modificacion***-->
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
				<a class="BotonesTeam4" href="datos_bsf_correspondencia.php?pag=<?php echo $_GET['pag'] + 1; ?>">Siguiente</a><!--*****Se realizo modificacion**-->
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
				<a class="BotonesTeam4" href="datos_bsf_correspondencia.php?pag=2">Siguiente</a><!--******Se realizo modificacion**-->
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>


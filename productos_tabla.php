<!--Esta pagina muestra todo el contenido de la tabla-->
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
	
				if(isset($_POST['btnbuscar'])){//se realizo modificacion en la consulta
					$buscar = $_POST['txtbuscar'];
					$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.nombre,pro.numeroguia,pro.fecha,pro.paque,pro.nombresocio,pro.direccion,pro.orientacion,pro.foto_nombre,pro.comentarios,cat.nombre as categoria 
					FROM productos pro INNER JOIN categoria_productos cat ON pro.categoria_id=cat.id WHERE pro.direccion LIKE '%".$buscar."%' OR pro.numeroguia LIKE '%".$buscar."%'");//se realizo modificacion
				}
				else{//se realizo modificacion en la consulta
					$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.nombre,pro.numeroguia,pro.fecha,pro.paque,pro.nombresocio,pro.direccion,pro.orientacion,pro.foto_nombre,pro.comentarios,cat.nombre as categoria 
					FROM productos pro, categoria_productos cat WHERE pro.categoria_id=cat.id ORDER BY pro.id DESC LIMIT " . (($pagina - 1) * $filasmax)  . "," . $filasmax);//se realizo modificacion
				}

				
	
				$resultadoMaximo = mysqli_query($conn, "SELECT count(*) as num_productos FROM productos");
	
				$maxusutabla = mysqli_fetch_assoc($resultadoMaximo)['num_productos'];
		
			?>

			<div class="ContenedorTabla">
				<form method="POST">
					<h2>Tabla de registro</h2>
		
					<div class="ContBuscar">
						<div style="float: left;">
							<a href="productos_tabla.php" class="BotonesTeam">Inicio</a>
							<a href="productos_exportar.php" class="BotonesTeam">Exportar</a>
							<input class="BotonesTeam" type="submit" value="Buscar" name="btnbuscar">
							<input class="CajaTextoBuscar" type="text" name="txtbuscar"  placeholder="Ingresar N° de guía o nombre del socio" autocomplete="off" >
						</div>
						<div style="float:right;">
							<?php echo "<a class='BotonesTeam5' href=\"productos_registrar.php?pag=$pagina\">Agregar Datos</a>";?>
						</div>
					</div>
				</form>
					<table>
						<tr>
							<th>Id</th>
							<th>Nombre del repartidor</th>
							<th>N° Guía</th>
							<th>Fecha:A/M/D </th>
							<th>Paqueteria(empresa)</th>
							<th>Nombre del titular</th><!--Se realizo modificacion-->
							<th>Dirección</th>
							<th>Orientacion</th>
							<th>Foto</th>
							<th>Comentarios</th>
							<th>Acción</th>
						</tr>
		
						<?php
					
							while ($mostrar = mysqli_fetch_assoc($sqlusu)) {

								echo "<tr>";
									echo "<td>".$mostrar['id']."</td>";
									echo "<td>".$mostrar['nombre']."</td>";
									echo "<td>".$mostrar['numeroguia']."</td>";
									echo "<td>".$mostrar['fecha']."</td>";
									//echo "<td>".$mostrar['paque']."</td>";//Muestra el numero de la empresa, nos referimos a categoria_id, y pasa porque esta heredando lo de la llave foreana
									echo "<td>".$mostrar['categoria']."</td>";
									echo "<td>".$mostrar['direccion']."</td>";
									//echo "<td>" . (isset($mostrar['nombrepaquete']) ? $mostrar['nombrepaquete'] : 'N/A') . "</td>";
									echo "<td>".$mostrar['nombresocio']."</td>";
									echo "<td>".$mostrar['orientacion']."</td>";
									echo "<td>".$mostrar['foto_nombre']."</td>";
									//echo "<td><img height='70px' src='data:image/*;base64,".base64_encode($mostrar['foto']).";'></td>";//prueba para la foto
									/*echo "<td>".$mostrar['foto']."</td>"; <td><img src="data:image/jpg;base64,<?php echo base64_encode($profot);?>" width="300" height="300" alt="Foto"></td>//prueba para la foto*/
									echo "<td>".$mostrar['comentarios']."</td>";
									
									
									echo "<td style='width:24%'>
										<a class='BotonesTeam1' href=\"productos_ver.php?id=$mostrar[id]&pag=$pagina\">&#x1F50D;</a> 
										<a class='BotonesTeam2' href=\"productos_modificar.php?id=$mostrar[id]&pag=$pagina\">&#128397;</a> 
										<a class='BotonesTeam3' href=\"productos_eliminar.php?id=$mostrar[id]&pag=$pagina\" onClick=\"return confirm('¿Estás seguro de eliminar el Registro $mostrar[nombre]?')\">&#128465;</a>
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
					<a class="BotonesTeam4" href="productos_tabla.php?pag=<?php echo $_GET['pag'] - 1; ?>">Anterior</a>
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
				<a class="BotonesTeam4" href="productos_tabla.php?pag=<?php echo $_GET['pag'] + 1; ?>">Siguiente</a>
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
				<a class="BotonesTeam4" href="productos_tabla.php?pag=2">Siguiente</a>
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>


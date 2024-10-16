<?php 
	include("conexion.php");
	include("productos_tabla_correspondencia.php");

	$pagina = isset($_GET['pag']) ? $_GET['pag'] : 1;
	$id = isset($_GET['id']) ? $_GET['id'] : 0;

	// Consulta segura para obtener datos del producto
	$smt = $conn->prepare("
		SELECT
			u.nom AS repartidor_nombre,
			cp.id AS categoria_id,
			cp.nombre AS categoria_nombre,
			r.name AS residente_nombre
		FROM
			usuarios u
		JOIN
			categoria_productos_correspondencia cp ON 1=1
		JOIN
			residentes r ON 1=1
		WHERE
			u.correo = ?
	");
	$querybuscar = $conn->prepare("SELECT id, nombre, numeroguia, fecha, paque, nombresocio, direccion, orientacion, comentarios FROM productos_correspondencia WHERE id = ?");
	$querybuscar->bind_param("i", $id);
	$querybuscar->execute();
	$result = $querybuscar->get_result();

	if($result->num_rows > 0) {
		$mostrar = $result->fetch_assoc();
		$proid = $mostrar['id'];
		$pronom = $mostrar['nombre'];
		$prodes = $mostrar['numeroguia'];
		$propre = $mostrar['fecha']; // fecha
		$propaquete = $mostrar['paque']; // paquete
		$pronomso = $mostrar['nombresocio'];
		$prodir = $mostrar['direccion'];
		$proori = $mostrar['orientacion'];
		$procom = $mostrar['comentarios'];
	} else {
		// Manejo de caso en el que no se encuentra el producto
		echo "<script>alert('Producto no encontrado'); window.location='productos_tabla_correspondencia.php?pag=$pagina';</script>";
		exit();
	}
?>

<html>
	<body>
		<div class="caja_popup2">
			<form id="Form" class="contenedor_popup" method="POST" enctype="multipart/form-data">
				<table>
					<tr><th colspan="2">Modificar datos</th></tr>	
					<tr> 
						<td><b>Id: </b></td>
						<td><input class="CajaTexto" type="number" name="txtid" value="<?php echo $proid;?>" readonly></td>
					</tr>

					<tr> 
						<td><b>Nombre del repartidor: </b></td>
						<td>
							<select name="txtnom" class='CajaTexto' required>
								<?php	
									if($pronom == 'Edmundo Gabriel Rivera'){
										echo '<option>' . $pronom . '</option>';
										echo '<option>Victor Acosta</option>';
										echo '<option>Raul Zarate</option>';
										echo '<option>Martin Ortiz</option>';
										echo '<option>Antonio Gomez</option>';
									} else if ($pronom == 'Victor Acosta'){
										echo '<option>' . $pronom . '</option>';
										echo '<option>Edmundo Gabriel Rivera</option>';
										echo '<option>Raul Zarate</option>';
										echo '<option>Martin Ortiz</option>';
										echo '<option>Antonio Gomez</option>';
									} else if ($pronom == 'Raul Zarate'){
										echo '<option>' . $pronom . '</option>';
										echo '<option>Edmundo Gabriel Rivera</option>';
										echo '<option>Victor Acosta</option>';
										echo '<option>Martin Ortiz</option>';
										echo '<option>Antonio Gomez</option>';
									} else if ($pronom == 'Martin Ortiz'){
										echo '<option>' . $pronom . '</option>';
										echo '<option>Edmundo Gabriel Rivera</option>';
										echo '<option>Victor Acosta</option>';
										echo '<option>Raul Zarate</option>';
										echo '<option>Antonio Gomez</option>';
									} else if ($pronom == 'Antonio Gomez'){
										echo '<option>' . $pronom . '</option>';
										echo '<option>Edmundo Gabriel Rivera</option>';
										echo '<option>Victor Acosta</option>';
										echo '<option>Raul Zarate</option>';
										echo '<option>Martin Ortiz</option>';
									} else {
										echo '<option>Edmundo Gabriel Rivera</option>';
										echo '<option>Victor Acosta</option>';
										echo '<option>Raul Zarate</option>';
										echo '<option>Martin Ortiz</option>';
										echo '<option>Antonio Gomez</option>';
									}
								?> 
							</select>
						</td>
					</tr>

					<!--******************************************************++Se realizo la modificacion-->

					<tr> 
						<td><b>N° Guía: </b></td>
						<td><input class="CajaTexto" type="text" name="txtdes" value="<?php echo $prodes;?>"></td>
					</tr>

					<tr> 
						<td><b>	Empresa: </b></td>
						<td>
							<select name="txtcat" class='CajaTexto' required>
								<?php	
									$qrproductos = mysqli_query($conn, "SELECT nombre FROM categoria_productos_correspondencia ");
									while($mostrarcat = mysqli_fetch_array($qrproductos)) { 
										if($mostrarcat['nombre']==$propaquete){
											echo '<option selected="selected" value="'.$mostrarcat['nombre'].'">' .$mostrarcat['nombre']. '</option>';
										} else {
											echo '<option value="'.$mostrarcat['nombre'].'">' .$mostrarcat['nombre']. '</option>';
										}
									}	
								?> 
							</select>
						</td>
					</tr>

					<tr> 
						<td><b>Direccion: </b></td>
						<td>
							<input class="CajaTexto" type="text" name="txtnomso" value="<?php echo $pronomso;?>" readonly>
							<input list="residentesN" id="inputN" name="txtnomso" class="CajaTexto">
							<datalist id="residentesN">
								<?php
									$qrcategoria = mysqli_query($conn, "SELECT * FROM residentes");
									while($mostrarresi = mysqli_fetch_array($qrcategoria)) {
										if($mostrarresi['name'] == $pronomso){
											echo '<option value="' . $pronomso . '">';
										} else {
											echo '<option value="' . $mostrarresi['name'] . '">' . $mostrarresi['idresidentes'] .'</option>';
										}
									}
								?>
							</datalist>
						</td>
					</tr>

					<tr>
						<td><b>Nombre del Titular: </b></td>
						<td>
							<input class="CajaTexto" type="text" name="txtdirec" value="<?php echo $prodir;?>" readonly>
							<input list="residentesD" id="inputD" name="txtdirec" class="CajaTexto">
							<datalist id="residentesD">
							
							</datalist>
							<script>
								document.getElementById('inputN').addEventListener('change',function () {
									var seleccionNombre = this.value;//obtenemos aqui  el nombre del primer datalist, se guarda aqui
									var residentesD		= document.getElementById('residentesD');

									residentesD.innerHTML	= '';//limpiamos el datalist2

									var xhr	= new XMLHttpRequest();//obtenemos las opciones del segundo datalist
									xhr.onreadystatechange = function() {
										if(this.readyState == 4 && this.status == 200) {
											var opcion = JSON.parse(this.responseText);
											opcion.forEach(function(option) {
												residentesD.innerHTML += '<option value="' + option + '">';
											});
										}
									};
									xhr.open("GET", "get_options.php?name=" + seleccionNombre, true);
									xhr.send();
								});
							</script>
						</td>
					</tr>
					
					<tr>
						<td><b>Orientación: </b></td>
						<td>
							<select name="txtori" class="CajaTexto" required>
								<?php 
									if($proori == 'Oriente'){
										echo '<option>' . $proori . '</option>';
										echo '<option>Poniente</option>';
									} else {
										echo '<option>' . $proori . '</option>';
										echo '<option>Oriente</option>';
									}
								?>
							</select>
						</td>
					</tr>

					<tr>
						<td><b>Comentarios </b></td>
						<td><textarea class="CajaTexto" type="text" name="txtcom" style="width: 383px; height: 201px;"  required><?php echo $procom;?></textarea></td>
					</tr>

					<td colspan="2" >
						<?php echo "<a class='BotonesTeam' href=\"productos_tabla_correspondencia.php?pag=$pagina\">Cancelar</a>";?>&nbsp;
						<input class='BotonesTeam' type="submit" name="btnregistrar" value="Modificar" onClick="javascript: return confirm('¿Deseas modificar este producto');">
					</td>

				</table>
			</form>
		</div>
	</body>
</html>

<?php
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			//Ajustamos la hora
			date_default_timezone_set('America/Mexico_City');//*****************************Se realizo modificacion

			// Variables del formulario
			$proid1 = $_POST['txtid'];
			$pronom1 = $_POST['txtnom'];
			$prodes1 = empty($_POST['txtdes']) ? $prodes : $_POST['txtdes'];
			$propre1 = date("Y-m-d H:i:s"); // fecha *******************************se realizo modificacion
			$propaquete1 = $_POST['txtcat']; // paquete
	
			// Validación de nombre del socio y dirección
			$pronomso1 = empty($_POST['txtnomso']) ? $pronomso : $_POST['txtnomso'];
			$prodir1 = empty($_POST['txtdirec']) ? $prodir : $_POST['txtdirec'];
	
			$proori1 = $_POST['txtori'];
			$procat1 = $_POST['txtcat'];

			$procom = $_POST['txtcom'];
	
			// Consulta preparada para la actualización con la nueva foto
			$stmt = $conn->prepare("UPDATE productos_correspondencia SET nombre=?, numeroguia=?, fecha=?, paque=?, nombresocio=?, direccion=?, orientacion=?, comentarios=? WHERE id=?");
			
			// Vinculación de parámetros
			$stmt->bind_param("ssssssssi", $pronom1, $prodes1, $propre1, $propaquete1, $pronomso1, $prodir1, $proori1, $procom, $proid1);
			
			// Ejecución de la consulta
			if ($stmt->execute()) {
				echo "<script>window.location= 'productos_tabla_correspondencia.php?pag=$pagina'</script>";
			} else {
				echo "Error: " . $stmt->error;
			}
	
			$stmt->close();

			$conn->close();
	
			// Actualización de los nombres de las empresas en la tabla 'productos'
			//$conn->query("UPDATE productos AS p JOIN categoria_productos AS cp ON p.categoria_id = cp.id SET p.paque = cp.nombre WHERE p.categoria_id = cp.id");
		} 
?>


<?php 
	include("conexion.php");
	include("productos_tabla.php");

	$pagina = isset($_GET['pag']) ? $_GET['pag'] : 1;
	$id = isset($_GET['id']) ? $_GET['id'] : 0;

	// Consulta segura para obtener datos del producto
	$querybuscar = $conn->prepare("SELECT pro.id, pro.nombre, numeroguia, fecha, pro.paque, pro.nombresocio, pro.direccion, pro.orientacion, foto_nombre, foto, comentarios, pro.categoria_id, cat.nombre as categoria 
	FROM productos pro JOIN categoria_productos cat ON pro.categoria_id=cat.id WHERE pro.id = ?");
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
		$procat = $mostrar['categoria_id'];
		$profotN = $mostrar['foto_nombre'];
		$profot = $mostrar['foto'];
		$procom = $mostrar['comentarios'];
	} else {
		// Manejo de caso en el que no se encuentra el producto
		echo "<script>alert('Producto no encontrado'); window.location='productos_tabla.php?pag=$pagina';</script>";
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
						<td><input class="CajaTexto" type="text" name="txtdes" value="<?php echo $prodes;?>" required></td>
					</tr>

					<tr> 
						<td><b>	Empresa: </b></td>
						<td>
							<select name="txtcat" class='CajaTexto' required>
								<?php	
									$qrproductos = mysqli_query($conn, "SELECT * FROM categoria_productos ");
									while($mostrarcat = mysqli_fetch_array($qrproductos)) { 
										if($mostrarcat['id']==$procat){
											echo '<option selected="selected" value="'.$mostrarcat['id'].'">' .$mostrarcat['nombre']. '</option>';
										} else {
											echo '<option value="'.$mostrarcat['id'].'">' .$mostrarcat['nombre']. '</option>';
										}
									}	
								?> 
							</select>
						</td>
					</tr>

					<tr> 
						<td><b>Nombre del Titular: </b></td>
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
						<td><b>Direccion: </b></td>
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
						<td><b>Desea modificar la foto: </b></td>
						<td>

							<select id="opcion" name="opcion" class="CajaTexto" onchange="mostrarB()">
								<option value="No">No</option>
								<option value="Si">Si</option>	
							</select>

							<script>
								function mostrarB(){
									var opcionS = document.getElementById("opcion").value;
									var miVid = document.getElementById("Video");//Con esto traigo el elemento
									var miCan = document.getElementById("Canvas");
									var miBot = document.getElementById("BotonesVideo");
										
									/*Sirve esta condicion para mostrar o dejar de mostrar el boton */
									if(opcionS === "Si"){
										miVid.style.display = "block";//con esto lo muestro
										miCan.style.display = "block";
										miBot.style.display = "block";
										//alert('Holaaaaaaaa siiii');
									} else {
										miVid.style.display = "none";//con esto lo muestro
										miCan.style.display = "none";
										miBot.style.display = "none";
										//alert('holaaaaaaaa nooooo');
									}
								}
							</script>

							<br><br>

							<!--En esta seccion va toda la camara y tomar la foto-->
							<div id="Video" style="display:none">
                            	<video muted id="video" width="640" height="480" autoplay></video>
							</div>
							<div id="Canvas">
								<canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
							</div>
							<div id="BotonesVideo" style="display:none">
								<button type="button" id="capture">
									<i class="zmdi zmdi-camera-party-mode" style="color: #333;"></i> 
									Tomar Foto
								</button>
								<input type="hidden" id="photoInput" name="photo">
							</div>

							<br><br><br>

							<!--Sirve para verificar si los datos estan ahi-->
							<img id="imagenP" src="data:image/*;base64,<?php echo base64_encode($profot);?>" width="300" height="300" alt="Foto">
							<p id="nombreP"><?php echo $profotN; ?></p>	
						</td>
					</tr>

					<tr>
						<td><b>Comentarios </b></td>
						<td>
							<select name="txtcom" class="CajaTexto" required>
								<?php 
									if($procom == 'El paquete esta en buen estado'){
										echo '<option>' . $procom . '</option>';
										echo '<option>El paquete se encuentra dañado</option>';
									} else {
										echo '<option>' . $procom . '</option>';
										echo '<option>El paquete esta en buen estado</option>';
									}
								
								?>
							</select>
						</td>
					</tr>

						<td colspan="2" >
							<?php echo "<a class='BotonesTeam' href=\"productos_tabla.php?pag=$pagina\">Cancelar</a>";?>&nbsp;
							<input class='BotonesTeam' type="submit" name="btnregistrar" value="Modificar" onClick="javascript: return confirm('¿Deseas modificar este producto');">
						</td>
					</tr>
				</table>
			</form>
			
		</div>

		<script >
			// Accedemos a la cámara
			const video = document.getElementById('video');//guardamos esa variable
			navigator.mediaDevices.getUserMedia({ video: true })//Verificamos si esta abierta o no
			.then(stream => {
				video.srcObject = stream;
				})
				.catch(err => {
					console.error('Error al acceder a la cámara: ', err);
				});

			// Capturamos la foto
			const captureButton = document.getElementById('capture');
			const canvas = document.getElementById('canvas');
			const photoInput = document.getElementById('photoInput');

			captureButton.addEventListener('click', () => {//tomamos la foto del canvas
				const context = canvas.getContext('2d');//La dejamos en 2D la imagen del canvas
				context.drawImage(video, 0, 0, canvas.width, canvas.height);//tomamos las memidas del canvas
				const photoData = canvas.toDataURL('image/png');//lo volvemos imagen
				photoInput.value = photoData;//tomamos el valor de la imagen
				canvas.style.display = 'block';//lo mostramos en un canvas aparte
			});

			// Enviamos el formulario
			const photoForm = document.getElementById('photoForm');//Enviamos el formulario
			photoForm.addEventListener('submit', (event) => {
				const formData = new FormData(photoForm);
				fetch('save_photo.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.text())
				.then(data => {
					console.log(data);
					alert('Foto guardada exitosamente.');
				})
				.catch(err => {
					console.error('Error al guardar la foto: ', err);
				});
			});
		</script>
	</body>
</html>

<?php
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['opcion'] == 'Si') {

			//Ajustamos la hora
			date_default_timezone_set('America/Mexico_City');//*****************************Se realizo modificacion

			// Variables del formulario
			$proid1 = $_POST['txtid'];
			$pronom1 = $_POST['txtnom'];
			$prodes1 = $_POST['txtdes'];
			$propre1 = date("Y-m-d H:i:s"); // fecha *******************************se realizo modificacion
			$propaquete1 = $_POST['txtcat']; // paquete
	
			// Validación de nombre del socio y dirección
			$pronomso1 = empty($_POST['txtnomso']) ? $pronomso : $_POST['txtnomso'];
			$prodir1 = empty($_POST['txtdirec']) ? $prodir : $_POST['txtdirec'];
	
			$proori1 = $_POST['txtori'];
			$procat1 = $_POST['txtcat'];
	
			// Manejo de la imagen
			$profotN1 = 'foto_' . date('d-m-y', time()) . '.png';
			$profot1 = $_POST['photo'];
	
			// Decodificación de la imagen base64
			if ($profot1) {
				list($type, $data) = explode(';', $profot1);
				list(, $data) = explode(',', $data);
				$data = base64_decode($data);
			}
	
			$procom = $_POST['txtcom'];
	
			// Consulta preparada para la actualización con la nueva foto
			$stmt = $conn->prepare("UPDATE productos SET nombre=?, numeroguia=?, fecha=?, paque=?, nombresocio=?, direccion=?, orientacion=?, foto_nombre=?, foto=?, comentarios=?, categoria_id=? WHERE id=?");
			
			// Vinculación de parámetros
			$stmt->bind_param("sssssssssssi", $pronom1, $prodes1, $propre1, $propaquete1, $pronomso1, $prodir1, $proori1, $profotN1, $data, $procom, $procat1, $proid1);
			
			// Ejecución de la consulta
			if ($stmt->execute()) {
				echo "<script>window.location= 'productos_tabla.php?pag=$pagina'</script>";
			} else {
				echo "Error: " . $stmt->error;
			}
	
			$stmt->close();
	
			// Actualización de los nombres de las empresas en la tabla 'productos'
			$conn->query("UPDATE productos AS p JOIN categoria_productos AS cp ON p.categoria_id = cp.id SET p.paque = cp.nombre WHERE p.categoria_id = cp.id");
	
			$conn->close();

		} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['opcion'] == 'No') {

			//Ajustamos la hora
			date_default_timezone_set('America/Mexico_City'); //*********************se realizo modificacion */

			// Variables del formulario
			$proid1 = $_POST['txtid'];
			$pronom1 = $_POST['txtnom'];
			$prodes1 = $_POST['txtdes'];
			$propre1 = date("Y-m-d H:i:s"); // fecha ************************se realizo modificacion
			$propaquete1 = $_POST['txtcat']; // paquete
	
			// Validación de nombre del socio y dirección
			$pronomso1 = empty($_POST['txtnomso']) ? $pronomso : $_POST['txtnomso'];
			$prodir1 = empty($_POST['txtdirec']) ? $prodir : $_POST['txtdirec'];
	
			$proori1 = $_POST['txtori'];
			$procat1 = $_POST['txtcat'];
	
			$procom = $_POST['txtcom'];
	
			// Consulta preparada para la actualización con la nueva foto
			$stmt = $conn->prepare("UPDATE productos SET nombre=?, numeroguia=?, fecha=?, paque=?, nombresocio=?, direccion=?, orientacion=?, comentarios=?, categoria_id=? WHERE id=?");
			
			// Vinculación de parámetros
			$stmt->bind_param("sssssssssi", $pronom1, $prodes1, $propre1, $propaquete1, $pronomso1, $prodir1, $proori1, $procom, $procat1, $proid1);
			
			// Ejecución de la consulta
			if ($stmt->execute()) {
				echo "<script>window.location= 'productos_tabla.php?pag=$pagina'</script>";
			} else {
				echo "Error: " . $stmt->error;
			}
	
			$stmt->close();
	
			// Actualización de los nombres de las empresas en la tabla 'productos'
			$conn->query("UPDATE productos AS p JOIN categoria_productos AS cp ON p.categoria_id = cp.id SET p.paque = cp.nombre WHERE p.categoria_id = cp.id");
	
			$conn->close();

		}
		
?>


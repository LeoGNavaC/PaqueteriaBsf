<?php 
	include("conexion.php");
	include("datos_bsf.php");//***************se realizo modificacion */

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;


	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';


	$pagina = $_GET['pag'];
	$id = $_GET['id'];

	//$querybuscar = mysqli_query($conn, "SELECT id, nombresocio, numeroguia, paque, estatus, fecha_entrega FROM productos WHERE id = '$id'");//***********se realizo modificacion */
	$querybuscar = mysqli_query($conn, "SELECT p.id, p.nombresocio, p.numeroguia, p.estatus, cp.nombre AS categoria FROM productos p, categoria_productos cp WHERE p.id = '$id' AND p.categoria_id = cp.id"); //*******se realizo modificacion */
	
	while($mostrar = mysqli_fetch_array($querybuscar)){	
		$proid 		= $mostrar['id'];
		$pronomso	= $mostrar['nombresocio'];
		$prodes	    = $mostrar['numeroguia'];
		$procat	    = $mostrar['categoria'];
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

					<tr style="display: none">
						<td><b>Correo</b></td>
						<td>
							<input type="text" name="txtresiprueba" class="CajaTexto" value="<?php echo $pronomso; ?>" readonly>
							<select name="email" class='CajaTexto' style="display:none" readonly>
								<?php
									$qrcategoria = mysqli_query($conn,"SELECT * FROM residentes WHERE name = '$pronomso'");
									while($mostrarresi = mysqli_fetch_array($qrcategoria)){
										echo '<option>' . $mostrarresi['email'] . '</option>';
									}
								?>
							</select>
						</td>
					</tr>

						<td colspan="2" >
							<input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar" onClick="javascript: return confirm('¿Verifico que los datos sean correctos?');">
							<?php echo "<a class='BotonesTeam' href=\"datos_bsf.php?pag=$pagina\">Cancelar</a>";?>&nbsp;<!--************Se realizo modificacion******-->
							
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
			$proid1 	= $_POST['id'];    
			$proest1	= $_POST['fue']; // Cambié la variable $proest a $proest1
			$proent1	= date("Y-m-d H:i:s");
			$procorreoS = $_POST['email']; //correo de los socios
			$prodes1 = $_POST['gia'];
			$procat1 = $_POST['paque'];
			
			//$procuerpoCorreo	= "Para: $procorreoS" . "\t\n" . " Su Id: $proid1" . " Fue: $proest1" . " Fecha: $proent1" . phpversion(); //Cuerpo del correo
			$procuerpoCorreo  = "<p style='background-color: #2424ec; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 30px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Para:</u></em></strong>              $procorreoS </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Id de seguimiento:</u></em></strong> $proid1 </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Número de guía:</u></em></strong>    $prodes </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Paquetería:</u></em></strong>        $procat </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Su paquete no fue entregado debido a:</u></em></strong>    $proest1 </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Fecha:</u></em></strong>             $proent1 </p>";
			$procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: 'Georgia', serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'>" . phpversion() . "</p><br>";

			$proAsunto	= "Su paquete no pudo ser entregado";
			
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com'; // Cambia esto por el host SMTP de Gmail
			$mail->SMTPAuth = true;
			$mail->Username = 'paqueteria@acbsf.org.mx'; // Cambia esto por tu dirección de correo electrónico de Gmail
			$mail->Password = 'enjd dffp fcxo gmnd'; // Cambia esto por tu contraseña de Gmail o por una contraseña de aplicación si usas autenticación de dos factores
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;
	
			$mail->setFrom('paqueteria@acbsf.org.mx', 'DeliveryBSF'); // Cambia esto por tu dirección de correo electrónico y tu nombre
			$mail->addAddress($procorreoS); // Agrega el destinatario
	
			$mail->isHTML(true);
			 $mail->CharSet = 'UTF-8'; // Asegurarse de que la codificación sea UTF-8
			$mail->Subject = $proAsunto;
			$mail->Body = $procuerpoCorreo;
	
			if($mail->send()) {
				echo "Correo enviado correctamente";
			} else {
				echo "Error al enviar el correo: " . $mail->ErrorInfo;
			}
	
			$querymodificar = mysqli_query($conn, "UPDATE productos SET numeroguia='$prodes',paque='$procat',estatus='$proest1',fecha_entrega='$proent1' WHERE id = '$proid1'");
			echo "<script>window.location= 'datos_bsf.php?pag=$pagina' </script>";//***************Se realizo modificacion */
		}
	?>

<!--Nota: para poder enviar los datos de un formulario a una base de datos se usa el name no el id-->
<!--action="https://formsubmit.co/leonava988@gmail.com"   ********************  Esto sirve para mandar el correo-->

					
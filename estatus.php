<?php 
    include("conexion.php");
    include("datos_bsf.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    $pagina = $_GET['pag'];
    $id = $_GET['id'];

    $querybuscar = mysqli_query($conn, "SELECT p.id, p.nombresocio, p.numeroguia, p.estatus, p.receptor, cp.nombre AS categoria FROM productos p, categoria_productos cp WHERE p.id = '$id' AND p.categoria_id = cp.id"); //*******se realizo modificacion */
    
    while($mostrar = mysqli_fetch_array($querybuscar)){    
        $proid      = $mostrar['id'];
        $pronomso   = $mostrar['nombresocio'];
        $prodes     = $mostrar['numeroguia'];
        $procat     = $mostrar['categoria'];
        $proest     = $mostrar['estatus'];
        $prorec     = $mostrar['receptor'];
    }
?>

<html>
<meta charset='UTF-8'>
    <body>
        <div class="caja_popup4">
            <form class="contenedor_popup3" method="POST">
                <table>
                    <tr><th colspan="2">Estatus del Paquete</th></tr>  
                    
                    <tr style="display: none">     
                        <td><b>Id: </b></td>
                        <td><input class="CajaTexto" type="number" name="id" value="<?php echo $proid;?>" readonly></td>
                    </tr>

                    <tr> 
                        <td><b>N°Guía: </b></td>
                        <td><input class="CajaTexto" type="text" name="gia" value="<?php echo $prodes;?>" readonly></td>
                    </tr>

                    <tr> 
                        <td><b>Paqueteria: </b></td>
                        <td><?php echo $procat;?></td>
                    </tr>

                    <tr style="display: none;">
                        <td><b>Fue: </b></td>
                        <td>
                            <select name="fue" class="CajaTexto">
                                <option value="Entregado">Entregado</option>
                            </select>
                        </td>    
                    </tr>

                    <tr>
                        <td><b>Nombre del receptor: </b></td>
                        <td><textarea class="CajaTexto" type="text" name="receptor" style="width: 283px; height: 40px;" required><?php echo $prorec;?></textarea></td>
                    </tr>      

                    <tr style="display: none;">
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

                    <tr>
                        <td colspan="2" >
                            <input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar">
                            <?php echo "<a class='BotonesTeam' href=\"datos_bsf.php?pag=$pagina\">Cancelar</a>";?>&nbsp;<!--**********Se realizo modificacion****-->
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
        date_default_timezone_set('America/Mexico_City');

        $proid1     = $_POST['id'];    
        $proest1    = $_POST['fue'];
        $proent1    = date("Y-m-d H:i:s");
        $procorreoS = $_POST['email']; // correo del socio
        $prodes1    = mysqli_real_escape_string($conn, $_POST['gia']);
        $prorec1    = $_POST['receptor'];
        
        // Cuerpo del correo
        $procuerpoCorreo  = "<p style='background-color: #2424ec; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 30px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Para:</u></em></strong> $procorreoS</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Id de seguimiento:</u></em></strong> $proid1</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Número de guía:</u></em></strong> $prodes</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Paquetería:</u></em></strong> $procat</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Estatus:</u></em></strong> $proest1</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>Fecha de entrega:</u></em></strong> $proent1</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'><strong style='color: #b45508;'><em><u>El paquete se entregó a:</u></em></strong> $prorec1</p>";
        $procuerpoCorreo .= "<p style='background-color: #f4f4f9; color: #333; font-family: Georgia, serif; font-size: 18px; line-height: 1.6; padding: 10px; border-left: 10px solid #6fb119; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 20px 0;'>" . phpversion() . "</p><br>";

        $proAsunto   = "Su paquete fue entregado con éxito";
        
        // Configuración de PHPMailer
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'paqueteria@acbsf.org.mx';//paqueteria@acbsf.org.mx
        $mail->Password = 'enjd dffp fcxo gmnd'; // Reemplaza con la contraseña correcta
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('paqueteria@acbsf.org.mx', 'DeliveryBSF');
        $mail->addAddress($procorreoS);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $proAsunto;
        $mail->Body = $procuerpoCorreo;

        if($mail->send()) {
            echo "Correo enviado correctamente";
        } else {
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }

        // Actualización en la base de datos
        $querymodificar = mysqli_query($conn, "UPDATE productos SET numeroguia='$prodes1', paque='$procat', estatus='$proest1', fecha_entrega='$proent1', receptor='$prorec1' WHERE id = '$proid1'");
        echo "<script>window.location= 'datos_bsf.php?pag=$pagina' </script>";//************se realizo modificacion */
    }
?>

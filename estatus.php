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

    //$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.numeroguia,pro.fecha,pro.nombresocio,pro.direccion,pro.orientacion,cat.nombre AS categoria FROM productos pro, categoria_productos cat WHERE pro.categoria_id=cat.id ORDER BY pro.id DESC LIMIT " . (($pagina - 1) * $filasmax)  . "," . $filasmax);
    // Consulta para obtener los datos del producto **********************+ realizo modificacion en la consulta
    $querybuscar = mysqli_query($conn, "SELECT p.id, p.nombresocio, p.numeroguia, p.estatus, p.receptor, cp.nombre AS categoria FROM productos p, categoria_productos cp WHERE p.id = '$id' AND p.categoria_id = cp.id"); //*******se realizo modificacion */
    
    while($mostrar = mysqli_fetch_array($querybuscar)){    
        $proid      = $mostrar['id'];
        $pronomso   = $mostrar['nombresocio'];
        $prodes     = $mostrar['numeroguia'];
        $procat     = $mostrar['categoria'];
        $proest     = $mostrar['estatus'];
        //$proent     = $mostrar['fecha_entrega'];//****************se realizo modificacion */
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
                        <td><?php echo $prodes;?></td>
                    </tr>

                    <tr> 
                        <td><b>Paqueteria: </b></td>
                        <td><?php echo $procat;?></td>
                    </tr>

                    <tr>
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
                    
                    <!-- ***********************se realizo modificacion
                    <tr> 
                        <td><b>Fecha/Entrega: </b></td>
                        <td><input class="CajaTexto" type="datetime-local" step="any" name="fecha" value="<?php //echo $proent; ?>" required ></td>
                    </tr>
                    -->

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
                            <input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar" onClick="javascript: return confirm('¿Verifico que los datos sean correctos?');">
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
        date_default_timezone_set('America/Mexico_City');//*****************se realizo modificacion */

        $proid1     = $_POST['id'];    
        $proest1    = $_POST['fue'];
        $proent1    = date("Y-m-d H:i:s");//*******************Se realizo una modificacion
        $procorreoS = $_POST['email']; // correo del socio
        $prodes1    = $_POST['gia'];
        $procat1    = $_POST['paque'];
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
        $querymodificar = mysqli_query($conn, "UPDATE productos SET numeroguia='$prodes', paque='$procat', estatus='$proest1', fecha_entrega='$proent1', receptor='$prorec1' WHERE id = '$proid1'");
        echo "<script>window.location= 'datos_bsf.php?pag=$pagina' </script>";//************se realizo modificacion */
    }
?>

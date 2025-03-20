<?php 
    include("conexion.php");
    include("datos_bsf_correspondencia.php");

    $pagina = $_GET['pag'];
    $id = $_GET['id'];

    $querybuscar = mysqli_query($conn, "SELECT id,paque,direccion,numeroguia,estatus FROM productos_correspondencia WHERE id = '$id'"); 
    
    while($mostrar = mysqli_fetch_array($querybuscar)){    
        $proid      = $mostrar['id'];
        $procat     = $mostrar['paque'];
        $pronomso   = $mostrar['direccion'];
        $prodes     = $mostrar['numeroguia'];
        $proest     = $mostrar['estatus'];
    }
?>

<html lang="es">
    <head>
        <meta charset='UTF-8'>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="javascript/scriptECpc.js" defer></script>
    </head>
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
                        <td><b>Empresa de correspondencia: </b></td>
                        <td><?php echo $procat;?></td>
                    </tr>

                    <tr style="display: none">
                        <td><b>Fue: </b></td>
                        <td>
                            <select name="fue" class="CajaTexto">
                                <option value="Entregado">Entregado</option>
                            </select>
                        </td>    
                    </tr>
                    
                    <tr>
                        <td>
                            <label>Lo entrego: </label>
                        </td>

                        <td>
                            <select id="select-repartidores" name="slt-repartidores">
                                <option value="">Cargando...</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" >
                            <input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar">
                            <?php echo "<a class='BotonesTeam' href=\"datos_bsf_correspondencia.php?pag=$pagina\">Cancelar</a>";?>&nbsp;<!--**********Se realizo modificacion****-->
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
        $prodes1    = mysqli_real_escape_string($conn,$_POST['gia']);
        $procat1    = $_POST['paque'];
        $prorec1    = $_POST['receptor'];
        $prore1     = $_POST['slt-repartidores'];

        // Actualización en la base de datos
        $querymodificar = mysqli_query($conn, "UPDATE productos_correspondencia SET repartidorEn='$prore1', numeroguia='$prodes1', paque='$procat', estatus='$proest1', fecha_entrega='$proent1'  WHERE id = '$proid1'");
        echo "<script>window.location= 'datos_bsf_correspondencia.php?pag=$pagina' </script>";//************se realizo modificacion */
    }
?>

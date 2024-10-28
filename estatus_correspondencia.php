<?php 
    include("conexion.php");
    include("datos_bsf_correspondencia.php");

    $pagina = $_GET['pag'];
    $id = $_GET['id'];

    //$sqlusu = mysqli_query($conn, "SELECT pro.id,pro.numeroguia,pro.fecha,pro.nombresocio,pro.direccion,pro.orientacion,cat.nombre AS categoria FROM productos pro, categoria_productos cat WHERE pro.categoria_id=cat.id ORDER BY pro.id DESC LIMIT " . (($pagina - 1) * $filasmax)  . "," . $filasmax);
    // Consulta para obtener los datos del producto **********************+ realizo modificacion en la consulta
    //$querybuscar = mysqli_query($conn, "SELECT id,paque,direccion,numeroguia,estatus FROM productos_correspondencia WHERE p.id = '$id' AND p.categoria_id = cp.id");
    $querybuscar = mysqli_query($conn, "SELECT id,paque,direccion,numeroguia,estatus FROM productos_correspondencia WHERE id = '$id'"); //*******se realizo modificacion */
    
    while($mostrar = mysqli_fetch_array($querybuscar)){    
        $proid      = $mostrar['id'];
        $procat     = $mostrar['paque'];
        $pronomso   = $mostrar['direccion'];
        $prodes     = $mostrar['numeroguia'];
        $proest     = $mostrar['estatus'];
        //$proent     = $mostrar['fecha_entrega'];//****************se realizo modificacion */
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
                    
                    <!-- ***********************se realizo modificacion
                    <tr> 
                        <td><b>Fecha/Entrega: </b></td>
                        <td><input class="CajaTexto" type="datetime-local" step="any" name="fecha" value="<?php //echo $proent; ?>" required ></td>
                    </tr>
                    -->

                    <tr>
                        <td colspan="2" >
                            <input class='BotonesTeam' type="submit" name="btnregistrar" value="Aceptar" onClick="javascript: return confirm('¿Verifico que los datos sean correctos?');">
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
        $prodes1    = $_POST['gia'];
        $procat1    = $_POST['paque'];
        $prorec1    = $_POST['receptor'];

        // Actualización en la base de datos
        $querymodificar = mysqli_query($conn, "UPDATE productos_correspondencia SET numeroguia='$prodes', paque='$procat', estatus='$proest1', fecha_entrega='$proent1' WHERE id = '$proid1'");
        echo "<script>window.location= 'datos_bsf_correspondencia.php?pag=$pagina' </script>";//************se realizo modificacion */
    }
?>

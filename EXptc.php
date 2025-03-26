<?php 
    include("conexion.php");

    //Definen encabezados para descargar el excel
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=Correspondencia.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    //Definimos los encabezados del archivo de excel
    echo "Nombre del repartidor\tRepartidor que entrego\tNumero de guia\tFecha de llegada\tPaqueteria\tDireccion\tNombre del socio\tOrientacion\tComentarios\tEstatus\tRecibido\n";

    //Ejecutamos la consulta
    $queryEXptc = "SELECT nombre,repartidorEn,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega FROM productos_correspondencia";
    $resultadoEXptc = $conn->query($queryEXptc);

    if ($resultadoEXptc->num_rows > 0) {
        while ($filaEXptc = $resultadoEXptc->fetch_assoc()) {
            echo $filaEXptc['nombre'] . "\t" . $filaEXptc['repartidorEn'] . "\t" . $filaEXptc['numeroguia'] . "\t" . $filaEXptc['fecha'] . "\t" . $filaEXptc['paque'] . "\t" . 
                 $filaEXptc['nombresocio'] . "\t" . $filaEXptc['direccion'] . "\t" . $filaEXptc['orientacion'] . "\t" . $filaEXptc['comentarios'] . "\t" . $filaEXptc['estatus'] . "\t" . 
                 $filaEXptc['fecha_entrega'] . "\t" . "\n";
        }
    } else {
        echo "No hay registros disponibles\n";
    }

    $conn->close();
?>
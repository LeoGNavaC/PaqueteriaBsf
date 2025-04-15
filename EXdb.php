<?php 
    include("conexion.php");

    //Definimos los encabezados necesacios para una descarga de un archivo Excel
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=Paqueteria_Entrega.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    //Definimos os campos que tendra el excel
    echo "Numero de guía\tFecha de llegada\tPaqueteria\tDirección\tNombre del residente\tOrientacion\tRepartidor que entrego\tComentarios\tEstatus\tFecha de entrega\tRecibio\n";

    //Ejecutamos la consulta:
    $queryRdb   = "SELECT numeroguia,fecha,paque,nombresocio,direccion,orientacion,repartidorEn,comentarios,estatus,fecha_entrega,receptor FROM productos";
    $resultadoRdb   = $conn->query($queryRdb);

    if ($resultadoRdb->num_rows > 0){
        while ($fileRdb = $resultadoRdb->fetch_assoc()) {
        echo $fileRdb['numeroguia'] . "\t" . $fileRdb['fecha'] . "\t" . $fileRdb['paque'] . "\t" . $fileRdb['nombresocio'] . "\t" . $fileRdb['direccion'] . "\t" .
             $fileRdb['orientacion'] . "\t" . $fileRdb['repartidorEn'] . "\t" . $fileRdb['comentarios'] . "\t" . $fileRdb['estatus'] . "\t" . $fileRdb['fecha_entrega'] . "\t" . $fileRdb['receptor'] . "\t" . "\n";
        }
    } else {
        echo "No hay registros disponibles\n";
    }

    $conn->close();
?>
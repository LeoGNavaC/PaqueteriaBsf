<?php
    include("conexion.php");

    //Definen encabezados para descargar el excel
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=Correspondencia_entrega.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    //Definimos los campos del excel
    echo "Nombre del repartidor\tNumero de guia\tFecha de llegada\tEmpresa\tNombre del titular\tDireccion\tOrientacion\tComentarios\tEstatus\tFecha de entrega\tRepartidor que entrego\n";

    //Ejecutamos la consulta
    $queryEXdbc = "SELECT nombre,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega,repartidorEn FROM productos_correspondencia";
    $resultadoEXdbc = $conn->query($queryEXdbc);

    if ($resultadoEXdbc->num_rows > 0) {
        while($filaEXdbc = $resultadoEXdbc->fetch_assoc()) {
            echo $filaEXdbc['nombre'] . "\t" . $filaEXdbc['numeroguia'] . "\t" . $filaEXdbc['fecha'] . "\t" . $filaEXdbc['paque'] . "\t" . $filaEXdbc['nombresocio'] . "\t" .
                 $filaEXdbc['direccion'] . "\t" . $filaEXdbc['orientacion'] . "\t" . $filaEXdbc['comentarios'] . "\t" . $filaEXdbc['estatus'] . "\t" . $filaEXdbc['fecha_entrega'] . "\t" . 
                 $filaEXdbc['repartidorEn'] . "\t" . "\n";
        }
    } else {
        echo "No hay registros disponibes\n";
    }

    $conn->close();
?>
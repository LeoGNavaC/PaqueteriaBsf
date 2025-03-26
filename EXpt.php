<?php 
    include("conexion.php");

    //Definimos los encabezados necesacios para una descarga de un archivo Excel
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=Paqueteria.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    //definimos los encabezados que tendra el archivo de excel
    echo "Nombre del repartidor\tRepartidor que entrego\tNumero de guia\tFecha de llegada\tPaqueteria\tDireccion\tNombre del socio\tOrientacion\tComentarios\tEstatus\tFecha de entrega\tRecibio\n";

    //Ejecutamos la consulta SQL
    $queryRP    = "SELECT nombre,repartidorEn,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega,receptor FROM productos";
    $resultadoRP= $conn->query($queryRP);

    if ($resultadoRP->num_rows > 0) {
        while ($filaRP = $resultadoRP->fetch_assoc()) {
        echo $filaRP['nombre'] . "\t" . $filaRP['repartidorEn'] . "\t" . $filaRP['numeroguia'] . "\t" . $filaRP['fecha'] . "\t" . $filaRP['paque'] . "\t" . $filaRP['nombresocio'] . "\t" .
             $filaRP['direccion'] . "\t" . $filaRP['orientacion'] . "\t" . $filaRP['comentarios'] . "\t" . $filaRP['estatus'] . "\t" . $filaRP['fecha_entrega'] . "\t" . $filaRP['receptor'] . "\t" . "\n";
        }
    } else {
        echo "No hay registros disponibles\n";
    }

    $conn->close();
?>
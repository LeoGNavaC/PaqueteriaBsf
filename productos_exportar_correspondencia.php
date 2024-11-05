<?php
    //codigo para exportar datos de una tabla del portal a excel conexión de mi base de datos de paquetería
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "paqueteriabsf";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener los datos de la tabla
    $sql = "SELECT id,nombre,numeroguia,fecha,paque,nombresocio,direccion,orientacion,comentarios,estatus,fecha_entrega FROM productos_correspondencia";
    $result = $conn->query($sql);


    // Crear el archivo Excel
    date_default_timezone_set('America/Mexico_City');
    $filename = "Datos requeridos_" . date('Y-m-d H:i:s') . ".xls";
    echo $filename;
    

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");



    // Imprimir los datos en formato Excel
    echo "ID \t "; 
    echo "Nombre del repartidor \t";
    echo "N. de Gia\t";
    echo "Fecha \t";
    echo "Paqueteria \t ";
    echo "Direccion \t";
    echo "Nombre del titular \t";
    echo "Orientacion \t";
    echo "comentarios \t ";
    echo "estatus \t";
    echo "fecha_entrega \t \n";

    

    // Encabezados de columna
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo $row['id'] .  "\t" . $row['nombre'] . "\t" . $row['numeroguia'] . "\t" . $row['fecha'] . "\t" . $row['paque'] . "\t" . $row['nombresocio'] . "\t" . $row['direccion'] . "\t" . $row['orientacion'] . "\t" . $row['comentarios'] .  "\t" . $row['estatus'] . "\t" . $row['fecha_entrega'] ."\t \n";//$row funciona para retornar el numero de filas de una funcion
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    "<script>window.location= 'productos_tabla_correspondencia.php'</script>";
?>


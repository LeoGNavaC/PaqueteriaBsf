<?php
    //verificar si se recibio el nombre del socio
    if(isset($_GET['name'])){
        //nos conectamos a la base de datos
        include("conexion.php");

        //escapamos el nombre del socio para prevenir inyeccion SQL
        $nombre = mysqli_real_escape_string($conn, $_GET["name"]);

        //realizamos la consulta SQL para obtener las direcciones asociadas al nombre del socio que se eliga
        $query = "SELECT direccion FROM residentes WHERE name = '$nombre'";
        $result = mysqli_query($conn,$query);

        //verificamos si se encontraron resultados
        if(mysqli_num_rows($result)> 0){
            //Inicializamos un array para almacenar las opciones de direccion
            $options = array();

            //Iteramos los resultados y agregamos cada direccion al array de opciones
            while($row = mysqli_fetch_assoc($result)){
                $options[] = $row['direccion'];
            }

            //devolver las opciones de direccion como JSON
            echo json_encode($options);
        } else {
            //No se encontraron opciones de direccion para el nombre del socio
            echo json_encode(array("No hay opciones disponibles"));
        }

        //cerramos la conexion con la base de datos
        mysqli_close($conn);
    } else {
        //No se recibio el nombre del socio
        echo "Error: Falta el parámetro 'name'";
    }
?>


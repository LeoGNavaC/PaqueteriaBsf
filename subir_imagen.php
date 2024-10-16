<?php
// Verificar si se ha enviado el formulario
if(isset($_POST["subir"])) {
    // Conexión a la base de datos (reemplaza con tus propios datos)
    $conexion = new mysqli("localhost", "root", "", "logincrud10");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener detalles de la imagen
    $nombre_imagen = $_FILES['imagen']['name'];
    $tipo_imagen = $_FILES['imagen']['type'];
    $tamaño_imagen = $_FILES['imagen']['size'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];

    // Comprobar si se ha seleccionado una imagen
    if ($nombre_imagen != "") {
        // Mover la imagen a una carpeta en el servidor
        $ruta_destino = "carpeta_destino/" . $nombre_imagen;
        move_uploaded_file($ruta_temporal, $ruta_destino);

        // Guardar la ruta de la imagen en la base de datos
        $sql = "INSERT INTO productos (ruta_imagen) VALUES ('$ruta_destino')";
        if ($conexion->query($sql) === TRUE) {
            echo "Imagen subida y guardada en la base de datos correctamente.";
        } else {
            echo "Error al subir la imagen: " . $conexion->error;
        }
    } else {
        echo "Por favor selecciona una imagen.";
    }

    // Cerrar conexión
    $conexion->close();
}
?>


<!--Esta pagina contiene todo lo relacionado para agregar un nuevo dato (Boton: "Agregar Datos")-->
<?php 
    include("conexion.php");//Sirve para conectar la base de datos
	include("productos_tabla.php");//Sirve para conectarse a la pagina principal(Por asi decirlo)

    //Se colocan en este bloque todas las consultas que se realizan
    // Preparar consulta combinada
    //****************Se realizo modificacion */
    $smt = $conn->prepare("
    SELECT 
        u.nom AS repartidor_nombre,
        cp.id AS categoria_id, 
        cp.nombre AS categoria_nombre,
        r.name AS residente_nombre
    FROM 
        usuarios u
    JOIN 
        categoria_productos cp ON 1=1
    JOIN 
        residentes r ON 1=1
    WHERE 
        u.correo = ?
    ");

    // Ejecutar la consulta
    $smt->bind_param("s", $_SESSION['usuarioingresando']);
    $smt->execute();
    $result = $smt->get_result();

    $repartidor_nombre = [];
    $categorias = [];
    $residentes = [];

    if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['repartidor_nombre'], $repartidor_nombre)) {
            $repartidor_nombre[] = $row['repartidor_nombre']; // Guardar nombres de repartidores únicos
        }
        if (!array_key_exists($row['categoria_id'], $categorias)) {
            $categorias[$row['categoria_id']] = $row['categoria_nombre']; // Guardar categorías únicas
        }
        if (!in_array($row['residente_nombre'], $residentes)) {
            $residentes[] = $row['residente_nombre']; // Guardar nombres de residentes únicos
        }
    }
    } else {
    echo "Error, reportelo con sistemas: Correo; clsoporte3@cgcsf, Numero; 56 4161 0514 ";
    }
    $smt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<title>Agregar Datos</title> Se realizzo modificacion*****************-->
    <!--<link type="text/css" rel="shortcut icon" href="assets/images/favicon.ico"/>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <!--<link rel="stylesheet" href="assets/css/style.css">******Se realizo modificacion-->
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <!--<script defer src="foto.js"></script>*********Se realizo modificacion-->
</head>
<body>
    <div class="caja_popup2">
        <form id="photoForm" class="contenedor_popup1" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <th colspan="2">Agregar datos</th><!--**************se realizo modificacion-->
                </tr>
                
                <tr style="display:none"><!--esta es una modificacion, yo lo deje para ver si me daba el nombre del usuario-->
                    <td><b>Nombre del repartidor: </b></td><!--Se realizo modificacion en todo el bloque-->
                    <td>
                        <select name="txtnom" class="CajaTexto" required readonly><!--**********Se realizo modificacion-->
                            <?php 
                                foreach ($repartidor_nombre as $nombre) {
                                    echo '<option>' . htmlspecialchars($nombre) . '</option>';
                                }
                                /*
                                $smt = $conn->prepare(("SELECT nom FROM usuarios WHERE correo = ?"));//preparamos la consula
                                $smt -> bind_param("s", $_SESSION['usuarioingresando']);//Le asignamos la s debido a que es un string
                                $smt -> execute();//ejecutamos la consulta
                                $result = $smt -> get_result();//optenemos el resultado

                                if ($result -> num_rows > 0){//verificamos si hay algun resultado
                                    while ($qrusuario = $result -> fetch_assoc()){//asociamos el resultado
                                        echo $qrusuario['nom'];//obtenemos el nombre del Array
                                        echo '<option>' . $qrusuario['nom'] . '</option>';
                                    }
                                } else {
                                    echo "Error, reportelo con sistemas: Correo; clsoporte3@cgcsf, Numero; 56 4161 0514 ";
                                }
                                $smt -> close();*/
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><b>N° de Gía: </b></td>
                    <td><input type="text" name="txtdes" autocomplete="off" class="CajaTexto" required></td>
                </tr>

                <tr>
                    <td><b>Empresa:</b></td>
                    <td>
                        <select name="txtcat" class="CajaTexto" required><!--**********Se realizo modificacion-->
                            <?php
                                foreach ($categorias as $id => $nombre) {
                                    echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($nombre) . '</option>';
                                }
                                /*$qrcategoria = mysqli_query($conn, "SELECT nombre, id FROM categoria_productos");
                                while ($mostrarcat = mysqli_fetch_assoc($qrcategoria)) {//se realizo modificacion
                                    echo '<option value="' . $mostrarcat['id'] . '">' . $mostrarcat['nombre'] . '</option>';
                                }*
                                /*
                                se utiliza las dos diferentes  mysqli_fetch_array y mysqli_fetch_assoc
                                el primero me trae el numero y el valor de donde se encuentra el array por ejemplo: 2 Leo, 5 Moy, numero en el array y el valor
                                el segundo solo me trae un solo valor, por ejemplo: Leo, Moy, omite el numero del arrar y solo trae el valor
                                */
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><b>Dirección </b></td>
                    <td>
                        <input list="residentesN" id="inputN" name="txtnomso" class="CajaTexto" required>
                        <datalist id="residentesN"><!--***************Se realizo modificacion-->
                            <?php
                                foreach ($residentes as $nombre_residente) {
                                    echo '<option value="' . htmlspecialchars($nombre_residente) . '">';
                                }
                                /*
                                $qrcategoria = mysqli_query($conn, "SELECT name FROM residentes");
                                while ($mostrarresi = mysqli_fetch_assoc($qrcategoria)) {//se realizo modificacion 
                                    echo '<option value="' . $mostrarresi['name'] . '">';
                                }*/
                            ?>
                        </datalist>
                    </td>
                </tr>

                <tr>
                    <td><b>Nombre del Titular:</b></td>
                    <td>
                        <input list="residentesD" id="inputD" name="txtdirec" class="CajaTexto">
                        <datalist id="residentesD"></datalist>
                        <script>
                            document.getElementById('inputN').addEventListener('change', function() {
                                var seleccionNombre = this.value;
                                var residentesD = document.getElementById('residentesD');
                                residentesD.innerHTML = '';

                                var xhr = new XMLHttpRequest();
                                xhr.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        var opcion = JSON.parse(this.responseText);
                                        opcion.forEach(function(option) {
                                            residentesD.innerHTML += '<option value="' + option + '">';
                                        });
                                    }
                                };
                                xhr.open("GET", "get_options.php?name=" + seleccionNombre, true);
                                xhr.send();
                            });
                        </script>
                    </td>
                </tr>

                <tr>
                    <td><b>Orientacion</b></td>
                    <td>
                        <select name="txtori" class="CajaTexto" required>
                            <option></option>
                            <option>Oriente</option>
                            <option>Poniente</option>
                        </select>
                    </td>
                </tr>
                <tr> 
                    <td><b>Foto</b></td>
                    <td>
                        <div id="Video">
                            <video muted id="video" width="640" height="480" autoplay></video>
                        </div>
                        <div id="Canvas">
                            <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
                        </div>
                        <div id="BotonesVideo">
                            <button type="button" id="capture">
                                <i class="zmdi zmdi-camera-party-mode" style="color: #333;"></i> 
                                Tomar Foto
                            </button>
                            <input type="hidden" id="photoInput" name="photo">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><b>Comentarios</b></td>
                    <td>
                        <select name="txtcom" class="CajaTexto" required>
                            <option></option>
                            <option>El paquete esta en buen estado</option>
                            <option>El empaque se encuentra dañado</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <?php
                        echo "<a class='BotonesTeam' href=\"productos_tabla.php?pag=$pagina\">Cancelar</a>";
                        ?>
                        <input class='BotonesTeam' type="submit" name="btnregistrar" value="Registrar"> <!--onClick="return confirm('¿Deseas registrar estos datos?');" ****modificacion-->
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
        // Accedemos a la cámara
        const video = document.getElementById('video');//Tomamos el elemento del video
        navigator.mediaDevices.getUserMedia({ video: true })//Verificamos si podemos conectarnos
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error al acceder a la cámara: ', err);
            });

        // Capturamos la foto
        const captureButton = document.getElementById('capture');//Tomamos el elemento del boton
        const canvas = document.getElementById('canvas');//Tomamos el elemento del canvas
        const photoInput = document.getElementById('photoInput');//Tomamos el elemento del input

        captureButton.addEventListener('click', () => {//Le agregamos un evento click
            const context = canvas.getContext('2d');//Convertimos la foto en 2D
            context.drawImage(video, 0, 0, canvas.width, canvas.height);//Tomamos las medidas de la imagen
            const photoData = canvas.toDataURL('image/png');//La convertimos
            photoInput.value = photoData;//Tomamos el valor
            canvas.style.display = 'block';//Mostramos la foto tomada por eso el block
        });

        /* //Utiliza para verificar que se suba doble vez el elemento de la foto
        const photoForm = document.getElementById('photoForm');//Tomamos el elemento de la foto
        photoForm.addEventListener('submit', (event) => {//subimos el elemento
            const formData = new FormData(photoForm);
            fetch('save_photo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                alert('Foto guardada exitosamente.');
            })
            .catch(err => {
                console.error('Error al guardar la foto: ', err);
            });
        });*/
    </script>

</body>
</html>

<?php
    if (isset($_POST['btnregistrar'])) {
     
        //ajustamos la hora
        date_default_timezone_set('America/Mexico_City');//*****************se realizo modificacion */
     
        $pronom = $_POST['txtnom'];
        $prodes = $_POST['txtdes'];
        $propre = date("Y-m-d H:i:s");//********************se realizo modificacion */
        $comparar = date("Y-m-d H:i");
        $propaq = $_POST['txtcat'];
        $pronomso = $_POST['txtnomso'];
        $prodir = $_POST['txtdirec'];
        $proori = $_POST['txtori'];
        $procat = $_POST['txtcat'];
        $procom = $_POST['txtcom'];

        //colocarle nombre a la foto tomando la fecha del sistema
        $profotN = 'foto_' . date('d-m-y', time()) . '.png';
        $profot = $_POST['photo'];
        list($type, $data) = explode(';', $profot);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        $stmt = $conn->prepare("INSERT INTO productos (nombre, numeroguia, fecha, paque, nombresocio, direccion, orientacion, foto_nombre, foto, comentarios, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $pronom, $prodes, $propre, $propaq, $pronomso, $prodir, $proori, $profotN, $data, $procom, $procat);

        if ($stmt->execute()) {
            echo "<script> alert('Producto registrado con exito: $pronom'); window.location='productos_tabla.php'  </script>";
        } else {
            echo "<script> alert('Error al registrar el producto'); window.location='productos_tabla.php'  </script>";
        }

        $stmt->close();

        // Considera agregar WHERE si solo deseas actualizar ciertas filas
        //$conn->query("INSERT productos AS p JOIN categoria_productos AS cp ON p.categoria_id = cp.id SET p.paque = cp.nombre WHERE ' $propre ' = ' $comparar '");
        //$conn->query("UPDATE productos AS p JOIN residentes AS r ON p.id_residentes = r.idresidentes SET p.nombresocio = r.name");//***********se realizo modificacion */

    }
?>




<!--Esta pagina contiene todo lo relacionado para agregar un nuevo dato (Boton: "Agregar Datos")-->
<?php 
    include("conexion.php");
	include("productos_tabla.php");

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

    $smt->bind_param("s", $_SESSION['usuarioingresando']);
    $smt->execute();
    $result = $smt->get_result();

    $repartidor_nombre = [];
    $categorias = [];
    $residentes = [];

    if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['repartidor_nombre'], $repartidor_nombre)) {
            $repartidor_nombre[] = $row['repartidor_nombre']; 
        }
        if (!array_key_exists($row['categoria_id'], $categorias)) {
            $categorias[$row['categoria_id']] = $row['categoria_nombre']; 
        }
        if (!in_array($row['residente_nombre'], $residentes)) {
            $residentes[] = $row['residente_nombre']; 
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="caja_popup2">
        <form id="photoForm" class="contenedor_popup1" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <th colspan="2">Agregar datos</th>
                </tr>
                
                <tr style="display:none">
                    <td><b>Nombre del repartidor: </b></td>
                    <td>
                        <select name="txtnom" class="CajaTexto" required readonly>
                            <?php 
                                foreach ($repartidor_nombre as $nombre) {
                                    echo '<option>' . htmlspecialchars($nombre) . '</option>';
                                }
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
                        <select name="txtcat" class="CajaTexto" required>
                            <?php
                                foreach ($categorias as $id => $nombre) {
                                    echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($nombre) . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><b>Dirección </b></td>
                    <td>
                        <input list="residentesN" id="inputN" name="txtnomso" class="CajaTexto" required>
                        <datalist id="residentesN">
                            <?php
                                foreach ($residentes as $nombre_residente) {
                                    echo '<option value="' . htmlspecialchars($nombre_residente) . '">';
                                }
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
                        <input class='BotonesTeam' type="submit" name="btnregistrar" value="Registrar">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
       
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error al acceder a la cámara: ', err);
            });

        const captureButton = document.getElementById('capture');
        const canvas = document.getElementById('canvas');
        const photoInput = document.getElementById('photoInput');

        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const photoData = canvas.toDataURL('image/png');
            photoInput.value = photoData;
            canvas.style.display = 'block';
        });
    </script>

</body>
</html>

<?php
    if (isset($_POST['btnregistrar'])) {
     
        date_default_timezone_set('America/Mexico_City');
     
        $pronom = $_POST['txtnom'];
        $prodes = $_POST['txtdes'];
        $propre = date("Y-m-d H:i:s");
        $propaq = $_POST['txtcat'];
        $pronomso = $_POST['txtnomso'];
        $prodir = $_POST['txtdirec'];
        $proori = $_POST['txtori'];
        $procat = $_POST['txtcat'];
        $procom = $_POST['txtcom'];

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
    }
?>



<!--Esta pagina contiene todo lo relacionado para agregar un nuevo dato (Boton: "Agregar Datos")-->
<?php 
    include("conexion.php");
	include("productos_tabla.php");

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

    $smt->bind_param("s", $_SESSION['usuarioingresando']);
    $smt->execute();
    $result = $smt->get_result();

    $repartidor_nombre = [];
    $categorias = [];
    $residentes = [];

    if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['repartidor_nombre'], $repartidor_nombre)) {
            $repartidor_nombre[] = $row['repartidor_nombre']; 
        }
        if (!array_key_exists($row['categoria_id'], $categorias)) {
            $categorias[$row['categoria_id']] = $row['categoria_nombre']; 
        }
        if (!in_array($row['residente_nombre'], $residentes)) {
            $residentes[] = $row['residente_nombre']; 
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="caja_popup2">
        <form id="photoForm" class="contenedor_popup1" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <th colspan="2">Agregar datos</th>
                </tr>
                
                <tr style="display:none">
                    <td><b>Nombre del repartidor: </b></td>
                    <td>
                        <select name="txtnom" class="CajaTexto" required readonly>
                            <?php 
                                foreach ($repartidor_nombre as $nombre) {
                                    echo '<option>' . htmlspecialchars($nombre) . '</option>';
                                }
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
                        <select name="txtcat" class="CajaTexto" required>
                            <?php
                                foreach ($categorias as $id => $nombre) {
                                    echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($nombre) . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><b>Dirección </b></td>
                    <td>
                        <input list="residentesN" id="inputN" name="txtnomso" class="CajaTexto" required>
                        <datalist id="residentesN">
                            <?php
                                foreach ($residentes as $nombre_residente) {
                                    echo '<option value="' . htmlspecialchars($nombre_residente) . '">';
                                }
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
                        <input class='BotonesTeam' type="submit" name="btnregistrar" value="Registrar">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
       
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error al acceder a la cámara: ', err);
            });

        const captureButton = document.getElementById('capture');
        const canvas = document.getElementById('canvas');
        const photoInput = document.getElementById('photoInput');

        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const photoData = canvas.toDataURL('image/png');
            photoInput.value = photoData;
            canvas.style.display = 'block';
        });
    </script>

</body>
</html>

<?php
    if (isset($_POST['btnregistrar'])) {
     
        date_default_timezone_set('America/Mexico_City');
     
        $pronom = $_POST['txtnom'];
        $prodes = $_POST['txtdes'];
        $propre = date("Y-m-d H:i:s");
        $propaq = $_POST['txtcat'];
        $pronomso = $_POST['txtnomso'];
        $prodir = $_POST['txtdirec'];
        $proori = $_POST['txtori'];
        $procat = $_POST['txtcat'];
        $procom = $_POST['txtcom'];

        $profotN = 'foto_' . date('d-m-y', time()) . '.png';
        $profot = $_POST['photo'];
        list($type, $data) = explode(';', $profot);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        $stmt = $conn->prepare("INSERT INTO productos (nombre, numeroguia, fecha, paque, nombresocio, direccion, orientacion, foto_nombre, foto, comentarios, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $pronom, $prodes, $propre, $propaq, $pronomso, $prodir, $proori, $profotN, $data, $procom, $procat);

        if ($stmt->execute()) {
            echo "<script> window.location='productos_tabla.php'  </script>";
        } else {
            echo "<script> alert('Error al registrar el producto'); window.location='productos_tabla.php'  </script>";
        }

        $stmt->close();
    }
?>




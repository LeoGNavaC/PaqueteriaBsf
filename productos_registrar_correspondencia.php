<!--Esta pagina contiene todo lo relacionado para agregar un nuevo dato (Boton: "Agregar Datos")-->
<?php 
    include("conexion.php");//Sirve para conectar la base de datos
	include("productos_tabla_correspondencia.php");//Sirve para conectarse a la pagina principal(Por asi decirlo)

    //Se colocan en este bloque todas las consultas que se realizan
    // Preparar consulta combinada
    $smt = $conn->prepare("
        SELECT 
            u.nom AS repartidor_nombre,
            cp.id AS categoria_id, 
            cp.nombre AS categoria_nombre,
            r.name AS residente_nombre
        FROM 
            usuarios u
        JOIN 
            categoria_productos_correspondencia cp ON 1=1
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
        echo "Error, reportelo con sistemas: Correo: clsoporte3@cgcsf, Numero: 56 4161 0514 ";
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
                    
                    <tr><!--esta es una modificacion, yo lo deje para ver si me daba el nombre del usuario-->
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
                        <td><input type="text" name="txtdes" autocomplete="off" class="CajaTexto"></td>
                    </tr>

                    <tr>
                        <td><b>Empresa:</b></td>
                        <td>
                            <select name="txtcat" class="CajaTexto" required>
                                <?php
                                    foreach ($categorias as $id => $nombre) {
                                        echo '<option value="' . htmlspecialchars($nombre) . '">' . htmlspecialchars($nombre) . '</option>';
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
                        <td><b>Comentarios</b></td>
                        <td>
                            <textarea class="CajaTexto" type="text" name="txtcom" style="width: 383px; height: 201px;"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            <?php
                            echo "<a class='BotonesTeam' href=\"productos_tabla_correspondencia.php?pag=$pagina\">Cancelar</a>";
                            ?>
                            <input class='BotonesTeam' type="submit" name="btnregistrar" value="Registrar"> 
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['btnregistrar'])) {
     
        //ajustamos la hora
        date_default_timezone_set('America/Mexico_City');
     
        $pronom = $_POST['txtnom'];
        $prodes = !empty($_POST['txtdes']) ? $_POST['txtdes'] : '';
        $propre = date("Y-m-d H:i:s");
        $propaq = $_POST['txtcat'];
        $pronomso = $_POST['txtnomso'];
        $prodir = $_POST['txtdirec'];
        
        $queryOri = $conn->prepare("SELECT orientacion FROM residentes WHERE name = ?");
        $queryOri->bind_param("s",$prodir);
        $queryOri->execute();
        $resultado = $queryOri->get_result()->fetch_assoc();
        if ($resultado['orientacion'] = 'P'){
            $proori = 'Poniente';
            //$proori = $_POST['txtori'] 
        } else if ($resultado['orientacion'] = 'O'){
            $proori = 'Oriente';
        }
        $procom = $_POST['txtcom'];

        $stmt = $conn->prepare("INSERT INTO productos_correspondencia (nombre, numeroguia, fecha, paque, nombresocio, direccion, orientacion, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $pronom, $prodes, $propre, $propaq, $pronomso, $prodir, $proori, $procom);

        if ($stmt->execute()) {
            echo "<script>window.location='productos_tabla_correspondencia.php'  </script>";
        } else {
            echo "<script> alert('Error al registrar el producto: ".$stmt->error."'); window.location='productos_tabla_correspondencia.php'  </script>";
        }

        $stmt->close();
    } 
?>




<?php
    include("conexion.php");
    include("barra_lateral.php");
    $usuarioingresado = $_SESSION['usuarioingresando'];
    $pagina = $_GET['pag'];
    $id = $_GET['id'];

    mysqli_query($conn, "DELETE FROM productos_correspondencia WHERE id='$id'");
    header("Location:productos_tabla_correspondencia.php?pag=$pagina");

?>


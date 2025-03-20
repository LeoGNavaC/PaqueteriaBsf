<?php 
    include("conexion.php");

    header('Content-Type: application/json'); // Importante para JSON

    $sqlcon = "SELECT nom FROM usuarios";
    $resultado = mysqli_query($conn, $sqlcon);

    $repar = array();

    while ($fila = mysqli_fetch_assoc($resultado)) { // Cambia mysqli_fetch_array a fetch_assoc
        $repar[] = $fila;
    }

    echo json_encode($repar);
?>

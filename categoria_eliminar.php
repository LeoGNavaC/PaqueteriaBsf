<?php
//*******************************************************+se realizo modificacion******************************************
    include('conexion.php');
    include("barra_lateral.php");
    
    // Asegúrate de que la sesión esté iniciada
    session_start();
    
    if (!isset($_SESSION['usuarioingresando'])) {
        // Redirigir o manejar el caso donde no hay un usuario ingresado
        header("Location: login.php");
        exit();
    }

    $usuarioingresado = $_SESSION['usuarioingresando'];

    // Validar y escapar las entradas de GET
    if (isset($_GET['pag']) && isset($_GET['categoria'])) {
        $pagina = (int)$_GET['pag'];
        $id = (int)$_GET['categoria'];

        // Asegúrate de que $id es un valor positivo válido antes de proceder
        if ($id > 0) {
            $query = "DELETE FROM categoria_productos WHERE id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                // Redirigir a la página de la tabla después de la eliminación
                header("Location: categoria_tabla.php?pag=$pagina");
                exit();
            } else {
                // Manejar el error en la ejecución de la consulta
                echo "Error al eliminar la categoría: " . mysqli_error($conn);
            }
        } else {
            // Manejar un ID no válido
            echo "ID de categoría no válido.";
        }
    } else {
        // Manejar el caso donde no se proporcionan 'pag' o 'categoria' en GET
        echo "Datos insuficientes para realizar la operación.";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
?>



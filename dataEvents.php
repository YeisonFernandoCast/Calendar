<?php

header('Content-Type: application/json');

require('connection.php');

$connect = returnConnection();

if (!$connect) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            $data = mysqli_query($connect, "SELECT id, name as title, description, start_date as start, end_date, text_color, background_color FROM eventos");

            if (!$data) {
                die("Error en la consulta: " . mysqli_error($connect));
            }

            $result = [];
            while ($row = mysqli_fetch_assoc($data)) {
                $result[] = $row;
            }

            echo json_encode($result);

            break;

        case 'add':
            $result = mysqli_query($connect, "INSERT INTO eventos (name, description, start_date, end_date, text_color, background_color)
                VALUES('$_POST[name]', '$_POST[description]', '$_POST[start_date]', '$_POST[end_date]', '$_POST[text_color]', '$_POST[background_color]')");

            echo json_encode($result);
            break;

        case 'edit':
            $result = mysqli_query($connect, "UPDATE eventos SET name = '$_POST[name]', 
                description = '$_POST[description]', 
                start_date = '$_POST[start_date]', 
                end_date = '$_POST[end_date]', 
                text_color = '$_POST[text_color]', 
                background_color = '$_POST[background_color]' 
                WHERE id = $_POST[id]");
               

            echo json_encode($result);
            break;


        case 'delete':
            $respuesta = mysqli_query($connect, "DELETE FROM eventos WHERE id = $_POST[id]");
            echo json_encode($respuesta);

        /*case 'edit':
            // Utiliza consultas preparadas para prevenir SQL injection
            $stmt = mysqli_prepare($connect, "UPDATE eventos SET titulo = ?, description = ?, start_date = ?, end_date = ?, text_color = ?, background_color = ? WHERE id = ?");

            // Vincula los parámetros
            mysqli_stmt_bind_param($stmt, 'ssssssi', $_POST['name'], $_POST['description'], $_POST['start_date'], $_POST['end_date'], $_POST['text_color'], $_POST['background_color'], $_POST['id']);

            // Ejecuta la consulta preparada
            $result = mysqli_stmt_execute($stmt);

            // Cierra la consulta preparada
            mysqli_stmt_close($stmt);
            echo json_encode($result);

            break;*/



        default:
            echo "Acción no válida.";
            break;
    }
} else {
    echo "No se proporcionó una acción válida.";
}

// Cierra la conexión a la base de datos al final del script
mysqli_close($connect);
?>

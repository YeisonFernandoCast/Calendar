<?php

header('Content-Type: application/json');

require('connection.php');

$connect = returnConnection();

switch ($_GET['action']) {

    case 'list':
        $data =mysqli_query($connect, "SELECT id, name, start_date, end_date, text_color, background_color FROM eventos_predef");
        $result = mysqli_fetch_all($data, MYSQLI_ASSOC);
        echo json_encode($result);
    break;

    case 'add':
        $respu =mysqli_query($connect, "INSERT INTO eventos_predef (name, start_date, end_date, text_color, background_color) 
                                        VALUES ('$_POST[name]', '$_POST[star_date]', '$_POST[end_date]', '$_POST[text_color]', '$_POST[background_color]')");
        echo json_encode($respu);
    break;

    case 'delete':
        $respu =mysqli_query($connect, "DELETE FROM eventos_predef WHERE id = $_POST[id] ");
        echo json_encode($respu);
     break;

}
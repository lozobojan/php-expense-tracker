<?php 

    session_start();
    include '../backend/functions.php';
    include '../backend/connect.php';
    include '../config.php';
    authorize();

    $user_id = $_SESSION['user']['id'];

    if(isset($_GET['type_id'])){
        $type_id = $_GET['type_id'];
    }else{
        echo json_encode(['msg' => 'no type_id supplied...']);
    }

    $sqlExists = "SELECT count(*) as has_type from user_type WHERE user_id = $user_id and type_id = $type_id";
    $resExists = mysqli_query($db_conn, $sqlExists);
    $hasType = mysqli_fetch_assoc($resExists)['has_type'];

    if($hasType > 0){
        $sqlType = "DELETE FROM user_type WHERE user_id = $user_id and type_id = $type_id";
        $msg = "type removed";
    }else{
        $sqlType = "INSERT INTO user_type (user_id, type_id) VALUES ($user_id, $type_id)";
        $msg = "type added";
    }

    $resType = mysqli_query($db_conn, $sqlType);
    echo json_encode(['msg' => $msg]);

?>
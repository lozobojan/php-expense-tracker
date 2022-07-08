<?php 

    session_start();
    include '../backend/functions.php';
    include '../backend/connect.php';
    authorize();

    var_dump($_FILES);
    exit;

    $date = readInput($_POST, 'date')." ".readInput($_POST, 'time');
    $amount = readInput($_POST, 'amount');
    $type_id = readInput($_POST, 'type');
    $subtype_id = readInput($_POST, 'subtype');
    $description = readInput($_POST, 'description');
    $user_id = $_SESSION['user']['id'];

    $sql = generateInsertQuery('expenses', [
        "date" => $date,
        "amount" => $amount,
        "type_id" => $type_id,
        "subtype_id" => $subtype_id,
        "description" => $description,
        "user_id" => $user_id,
    ]);
    $res = mysqli_query($db_conn, $sql);
    
    if($res){
        header('location:../index.php?msg=success');
    }else{
        header('location:create.php?msg=error');
    }

?>
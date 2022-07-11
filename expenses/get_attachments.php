<?php 
    // sleep(3);
    session_start();
    include '../backend/functions.php';
    include '../backend/connect.php';
    authorize();

    if(isset($_GET['expense_id'])){
        $expense_id = $_GET['expense_id'];
    }else{
        echo json_encode([]);
        exit;
    }

    $sql = "SELECT * FROM attachments WHERE expense_id = $expense_id";
    $res = mysqli_query($db_conn, $sql);

    echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
    exit;

?>
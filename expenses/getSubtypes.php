<?php 

    include '../backend/connect.php';

    if(isset($_GET['type_id'])){
        $type_id = $_GET['type_id'];
    }else{
        echo json_encode([]); // "[]"
    }

    $resSubtypes = [];
    $sql = "SELECT * FROM subtypes WHERE type_id = $type_id ";
    $res = mysqli_query($db_conn, $sql);

    while($row = mysqli_fetch_assoc($res)){
        $resSubtypes[] = $row;
    }

    echo json_encode($resSubtypes);
?>
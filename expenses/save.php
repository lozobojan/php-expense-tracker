<?php 

    session_start();
    include '../backend/functions.php';
    include '../backend/connect.php';
    authorize();

    $date = readInput($_POST, 'date')." ".readInput($_POST, 'time');
    $amount = readInput($_POST, 'amount');
    $type_id = readInput($_POST, 'type');
    $subtype_id = readInput($_POST, 'subtype');
    $description = readInput($_POST, 'description');
    $user_id = $_SESSION['user']['id'];

    // BEGIN TRANSACTION
    $resBegin = mysqli_query($db_conn, "BEGIN;");

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

        $new_expense_id = mysqli_insert_id($db_conn);

        $upload_dir = "uploads/";
        $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg'];
        $error_attaching = false;

        if(isset($_FILES) && count($_FILES) > 0){
            
            foreach($_FILES['files']['name'] as $key => $file_name){
                $path = uploadFile($allowed_extensions, $upload_dir, $file_name, $_FILES['files']['tmp_name'][$key], 1);
                $sql_attach = generateInsertQuery('attachments', [
                    "path" => $path,
                    "expense_id" => $new_expense_id
                ]);
                $res = mysqli_query($db_conn, $sql_attach);

                if(!$res){
                    $resBegin = mysqli_query($db_conn, "ROLLBACK;");
                    $error_attaching = true;
                    break;
                }
            }
        }
        if(!$error_attaching){
            $resBegin = mysqli_query($db_conn, "COMMIT;");
        }

        header('location:../index.php?msg=success');
    }else{
        $resBegin = mysqli_query($db_conn, "ROLLBACK;");
        header('location:create.php?msg=error');
    }

?>
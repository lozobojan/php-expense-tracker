<?php 

    include "../connect.php";
    include "../functions.php";
    session_start();

    $first_name = readInput($_POST, 'first_name', ['required' => true]);
    $last_name = readInput($_POST, 'last_name');
    $email = readInput($_POST, 'email');
    $password = readInput($_POST, 'password');
    $confirm_password = readInput($_POST, 'confirm_password');
    $agree_terms = readInput($_POST, 'agree_terms');

    if($password != $confirm_password){
        header('location:../../register.php?err=1');
        exit;
    }
    if($agree_terms != 1){
        header('location:../../register.php?err=2');
        exit;
    }
    
    $newUser = [
        "first_name" => $first_name,
        "last_name" => $last_name,
        "email" => $email,
        "password" => md5($password),
    ];
    $sql = generateInsertQuery('users', $newUser);

    if(mysqli_query($db_conn, $sql)){
        $newUser['id'] = mysqli_insert_id($db_conn);
        $_SESSION['login'] = true;
        $_SESSION['user'] = $newUser;

        header('location:../../index.php');
        exit;
    }
?> 
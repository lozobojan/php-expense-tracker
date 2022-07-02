<?php 

    session_start();
    include '../connect.php';
    include '../functions.php';

    $email = readInput($_POST, 'email', []);
    $password = readInput($_POST, 'password', []);

    $sql = generateSelectQuery('users', ['*'], ['email' => $email, 'password' => md5($password)]);
    $res = mysqli_query($db_conn, $sql);

    if(mysqli_num_rows($res) == 1){
        $_SESSION['login'] = true;
        $_SESSION['user'] = mysqli_fetch_assoc($res);

        header('location:../../index.php');
        exit;
    }else{
        header('location:../../login.php?err=wrongCredentials');
        exit;
    }

?>
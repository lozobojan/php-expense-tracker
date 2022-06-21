<?php 

    include "../connect.php";
    include "../functions.php";

    $first_name = readInput($_POST, 'first_name', ['required' => true]);
    $last_name = readInput($_POST, 'last_name');
    $email = readInput($_POST, 'email');
    $password = readInput($_POST, 'password');
    $confirm_password = readInput($_POST, 'confirm_password');
    $agree_terms = readInput($_POST, 'agree_terms');

    // TODO: confirm password + agree terms

    $sql = generateInsertQuery('users', [
        "first_name" => $first_name,
        "last_name" => $last_name,
        "email" => $email,
        "password" => $password,
    ]);

    if(mysqli_query($db_conn, $sql)){
        // TODO: prijavi korisnika -> idi na index.html
        exit("Registrovan....");
    }
?> 
<?php
    session_start();
    include '../admin/connect.inc.php';
    include 'validate.php';

    // if HTTP Request Not Matched Post Method redirect to main page
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:../index.php?badRequest");
        exit();
    }

    // catch data sent with form
    foreach ($_POST as $k=>$v){
        $$k = sanitize($v);
    }

    // validate username input
    $errors = [];
    if (isEmpty($user)){
        $errors[] = 'Username feild is require';
    }elseif (minChar($user ,5)){
        $errors[] = 'Username must be Greater then 5 characters';
    }elseif (maxChar($user ,30)){
        $errors[] = 'Username must be Less then 30 characters';
    }

    // validate password input
    if (isEmpty($pass)){
        $errors[] = 'Password feild is require';
    }elseif (minChar($pass ,6)){
        $errors[] = 'Password must be Greater then 6 characters';
    }elseif (maxChar($pass ,25)){
        $errors[] = 'Password must be Less then 25 characters';
    }

    // if there is any error redirect to login page and print the error message
    if (!empty($errors)){
        $_SESSION['err'] = $errors;
        header("Location:../login.php?action=login&error");
        exit();
    }
    // if there is no errors
    // check if user exist in Database

        // hashpass to compare with hashed pass in DB
        $hashedPass = sha1($pass);
        $query = "SELECT UserID , UserName , Password
                    FROM users
                    WHERE UserName=? AND Password=? ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user,$hashedPass]);
        // Fetch data
         $row = $stmt->fetch();
        // Count the Result Of The Query
        $count = $stmt->rowCount();
        // if Count == 0 That Mean From data not Matched any info in DB
        if ($count == 0){
            header("Location:../login.php?action=login&error=userNotFound");
             exit();
        }
            // if there is a record than login and start new session  
        // this is this user has record -[tuple]
            $_SESSION['UserName'] = $user ; // Register Session Name
            $_SESSION['UId'] = $row['UserID'] ; // Register Session Name
        // Redirect to main page if
            header("Location:../index.php");
            exit();


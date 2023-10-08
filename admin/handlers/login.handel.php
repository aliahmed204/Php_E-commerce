<?php
session_start();
include '../connect.inc.php';
include 'validate.php';

// check if user coming from HTTP Post Request
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $username = sanitize($_POST['user']);
    $pass = sanitize($_POST['pass']);


    // valiadate inputs
    $errors = [];
    if (isEmpty($username)){
        $errors[] = 'Username feild is require';
    }elseif (minChar($username ,5)){
        $errors[] = 'Username must be Greater then 5 characters';
    }elseif (maxChar($username ,30)){
        $errors[] = 'Username must be Less then 30 characters';
    }

    if (isEmpty($pass)){
        $errors[] = 'Password feild is require';
    }elseif (minChar($pass ,6)){
        $errors[] = 'Password must be Greater then 6 characters';
    }elseif (maxChar($pass ,25)){
        $errors[] = 'Password must be Less then 25 characters';
    }

    if (empty($errors)){
        // check if user exist in Database
        $hashedPass =sha1($pass);
        $qurey= "SELECT UserID , UserName , Password
                  FROM users
                  WHERE UserName=? AND Password=? AND GroupId=1
                  LIMIT 1";
        $stmt = $pdo->prepare($qurey);
        $stmt->execute([$username,$hashedPass]);

        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){
            // this is this user has record -[tuple]
            $_SESSION['logged_in'] = $username;  // Register Session Name
            $_SESSION['ID'] = $row['UserID']; // Register uesrid
            // Redirect to dashboard page if(The User Is An Admin)
            header("Location:../dashboard.php");
            exit();
        }else{
            header("Location:../index.php?error=userNotFound");
            exit();
        }
    }else{
        $_SESSION['err'] = $errors;
        header("Location:../index.php?error");
        exit();
    }
}else{
    header("Location:../index.php?badRequest");
    exit();
}
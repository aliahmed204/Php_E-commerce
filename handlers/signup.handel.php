<?php
    session_start();
    include '../init.php';

    // if HTTP Request Not Matched Post Method redirect to main page
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        echo '<div class="container">';
        $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>Cant Access Insert page Directly</div>";
        redirect($errorMsg,'back');
        echo '</div>';
        die();
    }

    // catch data sent with form
    foreach ($_POST as $key=>$value){
        $$key = sanitize($value);
    }

    // validate the form
    $_SESSION['errors'] = [];
        // validate username
    if (isEmpty($user)){
        $_SESSION['errors'][] = 'Username is require';
    }elseif (minChar($user ,5)){
        $_SESSION['errors'][] = 'Username must be Greater then 5 characters';
    }elseif (maxChar($user ,30)){
        $_SESSION['errors'][] = 'Username must be Less then 30 characters';
    }
        // validate password
    if (isEmpty($pass)){
        $_SESSION['errors'][] = 'Password field is require';
    }elseif (minChar($pass ,6)){
        $_SESSION['errors'][] = 'Password must be Greater then 6 characters';
    }elseif (maxChar($pass ,25)){
        $_SESSION['errors'][] = 'Password must be Less then 25 characters';
    }
         // validate Repeatd pass
    if ($pass !== $repeat_pass){
        $_SESSION['errors'][] = 'password Dose Not matched';
    }
        // validate Email
    if (isEmpty($Email)){
        $_SESSION['errors'][] = 'Email is require';
    }elseif (!filter_var($Email ,FILTER_VALIDATE_EMAIL)){
        $_SESSION['errors'][] = 'This Email Is Not Valid';
    }elseif (minChar($Email ,6)){
        $_SESSION['errors'][] = 'Email must be Greater then 6 characters';
    }elseif (maxChar($Email ,25)){
        $_SESSION['errors'][] = 'Email must be Less then 25 characters';
    }
        // validate fullName
    if (isEmpty($fullName)){
        $_SESSION['errors'][] = 'fullName  is require';
    }elseif (minChar($fullName ,10)){
        $_SESSION['errors'][] = 'fullName must be Greater then 10 characters';
    }elseif (maxChar($fullName ,40)){
        $_SESSION['errors'][] = 'fullName must be Less then 40 characters';
    }


    if (!empty($_SESSION['errors'])) {
        header("Location:../login.php?action=Signup");
        die();
    }
        // Check if user already exist before
    $check = checkExist("UserName","users",$user);
        if($check == 1 ):
            // if user has old record in DB Redirect to singUp Form
            $_SESSION['oldRecord'] = 'This User Is Exist';
            header("Location:../login.php?action=Signup");
            die();
        else:
            // hashPass For security
            $hashedPass = sha1($pass);
            // RegStatus will be Zero (user not active yet)
            $query= "INSERT INTO users (UserName,Password,Email,FullName,RegStatus,Date) 
                                VALUES (:usName,:usPass,:usEmail,:usFull,0,NOW() )";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam('usName',$user);
            $stmt->bindParam('usPass',$hashedPass);
            $stmt->bindParam('usEmail',$Email);
            $stmt->bindParam('usFull',$fullName);
            $stmt->execute();
            $count = $stmt->rowCount();
            echo '<div class="container">';
            // Register username To login directly after signUp
            $_SESSION['UserName'] = $user;
            // Register new user to Show Welcome Message
            $_SESSION['new'] = 'Welcome';
            // Success signUp will redirect to profile page
            header("Location:../profile.php");
            die();
        endif;
        //include '../'.$tpl.'footer.inc.php';

include '../includes/templates/footer.inc.php';

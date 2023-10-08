<?php
    session_start();
    include '../init.php';

        // if HTTP Request Not Matched Post Method redirect to main page
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:../index.php?error=CanNot-Access-newAdInsert");
        die();
    }
        // catch data sent with form
      $newCom = sanitize($_POST['theComment']);
      $itemId = $_POST['item_ID'];
      $userId = $_SESSION['UId']; // user logged in

        // if There Comment Empty Will Redirect To The Form And Show Error messages
    if (empty($newCom)){
        $_SESSION['comErrors'] = 'Comment can\'t be Empty';
        header("Location:../items.php?itemID={$itemId}");
        exit();
    }

   try{
        $query= "INSERT INTO `comments`(`comment`,`item_ID`, `user_ID`,`status`, `c_date`)
                    VALUES(:zcom,:zitem,:zmember,0,NOW())";
            // Prepare Stmt
        $stmt = $pdo->prepare($query);
            // Execute Stmt
        $stmt->execute([
            'zcom'     => $newCom,
            'zitem'    => $itemId,
            'zmember'  => $userId,
        ]);
        $count = $stmt->rowCount();
        // success insert Redirect to newad form page and print success message
        $_SESSION['comment_inserted'] = $stmt->rowCount() . "comment Inserted" ;
        header("Location:../items.php?itemID={$itemId}");
        die();
    }catch (PDOException $e){
        // Catch any error in query - and print error message in newad form page
        $_SESSION['query_error'] = "Query Error".$e->getMessage() ;
        header("Location:../items.php?itemID={$itemId}&insert=error");
        die();
    }


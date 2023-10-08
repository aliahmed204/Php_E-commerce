<?php
    session_start();
    include '../init.php';

        // if HTTP Request Not Matched Post Method redirect to main page
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:../index.php?error=CanNot-Access-newAdInsert");
        die();
    }
        // catch data sent with form
    foreach ($_POST as $key=>$value){
        $$key = sanitize($value);
    }
    $itemPrice = filter_var($itemPrice,FILTER_SANITIZE_NUMBER_INT);
        // to insert image
        $itemImage = $_FILES['itemImage'];
        $itemImageName = $itemImage['name'];
        $itemImageType = $itemImage['type'];
        $itemImageTmp = $itemImage['tmp_name'];
        $itemImageSize = $itemImage['size'];
        $types = ['jpg','jpeg','png'];
        $imgType = explode('.',$itemImageName);
        $imgAllowd = strtolower(end($imgType));


// validate the form And Register Errors in Session var
    $_SESSION['adErrors'] = [];
        // validate the Name
    if (isEmpty($itemName)){
        $_SESSION['adErrors'][] = 'Name is require';
    }elseif (minChar($itemName ,3)){
        $_SESSION['adErrors'][] = 'Name must be Greater then 3 characters';
    }elseif (maxChar($itemName ,30)){
        $_SESSION['adErrors'][] = 'Name must be Less then 30 characters';
    }
        // validate the Description
    if (isEmpty($itemDesc)){
        $_SESSION['adErrors'][] = 'Description can\'t be Empty';
    }
        // validate the Price
    if (isEmpty($itemPrice)){
        $_SESSION['adErrors'][] = 'Price can\'t be Empty';
    }
        // validate the Country
    if (isEmpty($itemCountry)){
        $_SESSION['adErrors'][] = 'Country OF Made Field can\'t be Empty';
    }
        // validate the Status
    if ($itemStatus == 0){
        $_SESSION['adErrors'][] = 'You Must Choose the Status';
    }
        // validate the Category
    if ($itemCategory == 0){
        $_SESSION['adErrors'][] = 'You Must Choose the Category ';
    }

    if (empty($imgAllowd)){
        $_SESSION['adErrors'][] = 'Item Should have An Image';
    }elseif (!empty($imgAllowd) && !in_array($imgAllowd , $types)){
        $_SESSION['adErrors'][] = 'Image Extension You Used Are Not Allowed ';
    }
        // if There Is Any Error Will Redirect To The Form And Show Error messages
    if (!empty($_SESSION['adErrors'])) {
        header("Location:../newad.php");
        die();
    }



    try{
        $imgUnique = rand(0,10000).$itemImageName ;
        move_uploaded_file($itemImageTmp,'../admin\uploads\items\\'.$imgUnique);
        $query= "INSERT INTO items
                    (item_name,item_description,item_price,item_image,country_made,item_status,add_date,Cat_ID,Member_ID,tags) 
                  VALUES (:iName,:iDesc,:iPrice,:itheimg,:iCountry,:iStatus,NOW() ,:iCategory,:iMember,:itags)";
            // Prepare Stmt
        $stmt = $pdo->prepare($query);
            // Execute Stmt
        $stmt->execute([
            'iName'    => $itemName,
            'iDesc'    => $itemDesc,
            'iPrice'   => $itemPrice,
            'itheimg'   => $imgUnique,
            'iCountry' => $itemCountry,
            'iStatus'  => $itemStatus,
            'iMember'  => $itemMember, // $_SESSION['UId'] - Put i send it with form
            'iCategory'=> $itemCategory,
            'itags'=> $itemTags
        ]);
        $count = $stmt->rowCount();
        // success insert Redirect to newad form page and print success message
        $_SESSION['item_inserted'] = $stmt->rowCount() . " Item Inserted" ;
        header("Location:../newad.php?insert=done");
        die();
    }catch (PDOException $e){
        // Catch any error in query - and print error message in newad form page
        $_SESSION['query_error'] = "Query Error".$e->getMessage() ;
        header("Location:../newad.php?insert=done");
        die();
    }


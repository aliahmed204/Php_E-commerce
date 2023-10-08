<?php

/*
** Manage Members Page
** You Cab Read | Add | Edit | Delete Members From Here
*/


// make our pages to manpulating users
session_start();

if(isset($_SESSION['UserName'])){
    $theTitle = 'Edit Profile';
    include 'init.php';
    // split pages
    $do = isset($_GET['do']) ? $_GET['do'] : 'Edit';
    // start Manage Page
    if ($do ==='Edit'){
        //  check if get request userId is numeric & get integer value
        $userId = (isset($_GET['UserID']) && is_numeric($_GET['UserID'])) ? intval($_GET['UserID']) : 0;
        // select all data depend on this id
        $qurey= "SELECT * FROM users WHERE UserID=?  LIMIT 1";
        $stmt = $pdo->prepare($qurey);
        // execute query
        $stmt->execute([$userId]);
        // fetch the data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // the row count
        $count = $stmt->rowCount();
        // if there is such id show the form
        if($count > 0) : ?>
            <!-- this is the form where i well edit in it -->
            <h1 class="text-center text-primary">Welcome <?= $_SESSION['UserName']?> To Edit Page </h1>
            <div class="container">
                <form class="form-horizontal align-content-center" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <!-- Hidden field for ID -->
                    <input  type="hidden" name="userid" value="<?= $userId ?>"  autocomplete="off"  />
                    <!-- end Id field -->
                    <!-- Start Username field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input class="form-control " type="text" name="user" value="<?= $row['UserName']?>"  autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- End Username field -->
                    <!-- Start Password field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input type="hidden" name="oldPass"  value="<?= $row['Password'] ?>"  />
                            <input class="form-control " type="password" name="newPass"  autocomplete="new-password"  />
                        </div>
                    </div>
                    <!-- End Password field -->
                    <!-- Start Email field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input class="form-control " type="Email" name="Email" value="<?= $row['Email']?>" autocomplete="off"  required="required"/>
                        </div>
                    </div>
                    <!-- End Email field -->
                    <!-- Start FullName field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6 ">
                            <input class="form-control " type="text" name="fullName" value="<?= $row['FullName']?>" autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- End FullName field -->

                    <!-- Start img field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Add Image </label>
                        <div class="col-sm-10 col-md-6 ">
                            <input class="form-control " type="file" name="avatar" />
                        </div>
                    </div>
                    <!-- End img field -->

                    <!-- Start submit field -->
                    <div class="form-group form-group-lg ">
                        <div class="col-sm-offset-2 col-sm-10 ">
                            <input class="btn btn-success btn-lg" type="submit" value="Save"   />
                        </div>
                    </div>
                    <!-- End submit field -->
                </form>
            </div>
        <?php
        else:
            // if there is no such id show error message
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>There Is No Such Id.</div>";
            redirect($errorMsg);
            echo '</div>';
            die();
        endif;
    }elseif ($do === 'Update'){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'):
            echo "<h1 class='text-center text-primary'>Welcome To update Page </h1>";
            echo "<div class='container'>";
            // get variables form the form
            foreach ($_POST as $key=>$vlaue){
                $$key = $vlaue;
            }

            $ava = $_FILES['avatar'];
            $avaName = $ava['name'] ;
            $avaSize = $ava['size'] ;     // we should  identify max size that we accept
            $avaTmp  = $ava['tmp_name'] ; // temporary Rote
            // Allows Image types that our website accept
            $types = ['jpg','jpeg','png'];
            // Get Image Extension
            $avaType = explode('.',$avaName);
            $avaAllowType = strtolower(end($avaType));


            // password track
            $pass = (empty($newPass)) ? $oldPass : sha1($newPass);
            // validate the form
            $errors = [];
            if (isEmpty($user)){
                $errors[] = 'Username is require';
            }elseif (minChar($user ,5)){
                $errors[] = 'Username must be Greater then 5 characters';
            }elseif (maxChar($user ,30)){
                $errors[] = 'Username must be Less then 30 characters';
            }

            if (isEmpty($Email)){
                $errors[] = 'Email is require';
            }elseif (minChar($Email ,6)){
                $errors[] = 'Email must be Greater then 6 characters';
            }elseif (maxChar($Email ,25)){
                $errors[] = 'Email must be Less then 25 characters';
            }
            if (isEmpty($fullName)){
                $errors[] = 'fullName  is require';
            }elseif (minChar($fullName ,10)){
                $errors[] = 'fullName must be Greater then 10 characters';
            }elseif (maxChar($fullName ,40)){
                $errors[] = 'fullName must be Less then 40 characters';
            }

            // check exist
            $query = "SELECT UserName FROM users Where UserID!=?  ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$userid]);
            $reslut = $stmt->fetchall(PDO::FETCH_ASSOC);
            foreach ($reslut as $r){
                if($r['UserName'] == $user) {
                    $errors[] = 'This username is already Exist';
                }
            }

                // if user dose not changed the image
            $uploadImage = '';
            if(empty( $ava['error'])){
                $imgUnique = rand(0,10000).'_'.$avaName ;
                $uploadImage = " avatar = '{$imgUnique}' , ";
                move_uploaded_file($avaTmp,'admin\uploads\avatar\\'.$imgUnique);
            }

            // if there is no errors and username not taken
            if (empty($errors)){

                $qurey= "UPDATE users 
                            SET $uploadImage 
                            UserName=?, Password=?, Email=? ,FullName =? 
                            WHERE UserID=?";
                $stmt = $pdo->prepare($qurey);
                // execute query
                $stmt->execute([$user,$pass,$Email,$fullName,$userid]);
                // if success echo success
                echo '<div class="container">';
                $errorMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                    . $stmt->rowCount() . " Record updated </div>";
                redirect($errorMsg ,'back');
                echo '</div>';
            }else{ // if there is error print error throw loop
                foreach ($errors as $er){
                    echo "<div class='alert alert-danger text-center font-weight-bold'>$er</div>" ;
                }
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>Wrong Input</div>";
                redirect($errorMsg ,'back');
            }
            echo "</div>";

        else:
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> You Can't Access update Page Directly </div>";
            redirect($errorMsg);
            echo '</div>';
            die();
        endif;
    }

    include $tpl .'footer.inc.php';
}else{
    header("Location:index.php?error=WrongUser");
    die();
}

<?php

    /*
    ** Manage Members Page
    ** You Cab Read | Add | Edit | Delete Members From Here
    */


    // make our pages to manpulating users
session_start();

if(isset($_SESSION['logged_in'])){
   $theTitle = 'Members';
    include 'init.php';
    // split pages
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        // start Manage Page
    if($do === 'Manage'){
        // select all users except admin == dont need any safity here
        $forPending='';
        if (isset($_GET['member']) && $_GET['member'] == 'pending'){
            $forPending = "AND RegStatus=0 ";
        }
        $forShowMember='';
        if (isset($_GET['ID']) && is_numeric($_GET['ID'])){
            $memberId = intval($_GET['ID']);
            $forShowMember = "AND UserID= $memberId";
        }
        $query= "SELECT * FROM users WHERE GroupID !=1 $forShowMember $forPending ORDER BY UserID DESC "; // not admin
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        // assign to variable
        $rows = $stmt->fetchAll();
        if(empty($rows)){
            echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message">There Is No Record To Show  </div>
                   <a href="members.php?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New Member</a>
                 </div>';
        }else{
        ?>
        <h1 class="text-center text-primary">Manage Members </h1>
            <?php
                if(isset($_SESSION['delete'])){
                    echo "<div class='container'>
                             <div class='alert alert-success text-center font-weight-bold'>
                                         user deleted 
                                </div>
                           </div>";
                }
                unset($_SESSION['delete']);
                if(isset($_SESSION['oldRecord'] )){
                    echo "<div class='container'>
                            <div class='alert alert-success text-center font-weight-bold'>
                                 This UserName Is Used 
                            </div>
                         </div>";
                }
                unset($_SESSION['oldRecord']);
                if(isset($_SESSION['active'] )){
                    echo "<div class='container'> 
                            <div class='alert alert-success text-center font-weight-bold'>
                                the user has been Activated  
                             </div>
                           </div>";
                }
                unset($_SESSION['active']);
            ?>
        <div class="container">
            <div class="table-responsive ">
                <table class="table table-bordered text-center ">
                    <tr class="table-active" >
                        <th>#ID</th>
                        <th>Avatar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Reg Status</th>
                        <th>Registered Date</th>
                        <th>Control</th>
                    </tr>
        <?php

            foreach ($rows as $row):
                echo '<tr>
                        <th>'.$row['UserID'].'</th>';

                        if(!empty($row['avatar'])){ // if user has img in Db get It
                            echo '<th><img src="uploads/avatar/'.$row['avatar'].'" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }else{      // if user Dose Not have img in Db Show Defult Img
                            echo '<th><img src="uploads/avatar/179-Blog-Post-How-to-break-down-big-goals.jpg" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }

                echo   '<td>'.$row['UserName'].'</td>
                        <td>'.$row['Email'].'</td>
                        <td>'.$row['FullName'].'</td>
                        <td>'.$row['RegStatus'].'</td>
                        <td>'.$row['Date'].'</td>
                        <td>
                        <a href="?do=Edit&UserID='.$row['UserID'].'" class="btn btn-success"><i class="fa fa-edit "></i>Edit</a>
                        <a href="?do=Delete&UserID='.$row['UserID'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</a>';
                         if($row['RegStatus'] == 0) echo '<a href="?do=active&UserID='.$row['UserID'].'" class="btn btn-info ml-1"><i class="fa fa-edit"></i>Activate</a>
                    </td>
                </tr>';
            endforeach;
        ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary my-3"><i class="fa fa-plus "></i> Add New Member</a>
        </div>


    <?php }
    } elseif ($do === 'Add') { ?>
        <h1 class="text-center text-primary">Add New Member </h1>
        <div class="container">
            <form class="form-horizontal align-content-center" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Username field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="user" autocomplete="off" required="required" placeholder="Name To login Shop" />
                    </div>
                </div>
                <!-- End Username field -->

                <!-- Start Image field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Upload Img</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="file" name="avatar" autocomplete="off" required="required" placeholder="Name To login Shop" />
                    </div>
                </div>
                <!-- End Image field -->


                <!-- Start Password field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="password" name="Pass"  autocomplete="new-password" required="required" placeholder="Password must be hard and complex" />
                    </div>
                </div>
                <!-- End Password field -->
                <!-- Start Email field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="Email" name="Email" autocomplete="off"  required="required" placeholder="Email must be valied"/>
                    </div>
                </div>
                <!-- End Email field -->
                <!-- Start FullName field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="fullName" autocomplete="off" required="required" placeholder="Enter Your Name"/>
                    </div>
                </div>
                <!-- End FullName field -->
                <!-- Start submit field -->
                <div class="form-group form-group-lg ">
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <input class="btn btn-success btn-lg" type="submit" value="Add"   />
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div>

    <?php }elseif ($do === 'Insert'){
        // $user .$Pass .$Email .$fullName;
        echo "<h1 class='text-center text-primary'>Welcome To Insert Page </h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST'):
            // get variables form the form
            foreach ($_POST as $k=>$v){
                $$k = sanitize($v);
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
            // Check the type
            /*if (in_array($avaAllowType , $types)){
                echo $ava['type'] . $avaAllowType .'<br>';
            }else{
                echo 'Not Allowad' . $avaAllowType . '<br>';
            }*/


            // validate the form

           $errors = [];
            if (isEmpty($user)){
                $errors[] = 'Username is require';
            }elseif (minChar($user ,5)){
                $errors[] = 'Username must be Greater then 5 characters';
            }elseif (maxChar($user ,30)){
                $errors[] = 'Username must be Less then 30 characters';
            }

            if (isEmpty($Pass)){
                $errors[] = 'Password feild is require';
            }elseif (minChar($Pass ,6)){
                $errors[] = 'Password must be Greater then 6 characters';
            }elseif (maxChar($Pass ,25)){
                $errors[] = 'Password must be Less then 25 characters';
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

            if (empty($ava)){
                $errors[] = 'Image Is require';
            }elseif (!empty($ava) && !in_array($avaAllowType , $types)){
                $errors[] = 'This Extension Is Not Allowed';
            }elseif ( $avaSize > 4194304 ){ // 4mg * 1024B * 1024k => 4194304
                $errors[] = 'Image Can Not Be Bigger Than 4-MB';
            }


             if (empty($errors)){ // if there is no errors
                $check = checkExist("UserName","users",$user);
                if($check == 1 ):
                    $_SESSION['oldRecord'] = 'This User Is Exist';
                    header("Location:Members.php?do=Manage");
                    die();
                else:

                    $hashedPass = sha1($Pass);
                    $avaUnique = rand(0,100000).'_'.$avaName ; // random numbers + name => Unique name always
                    move_uploaded_file($avaTmp ,"uploads\avatar\\".$avaUnique);// move tmp-name to destination [my pass].name

                    $qurey= "INSERT INTO users (UserName,Password,Email,FullName,RegStatus,`Date`,avatar)
                                VALUES (:usName,:usPass,:usEmail,:usFull,1,NOW(),:usavatar )";
                    $stmt = $pdo->prepare($qurey);
                    $stmt->execute([
                          'usName'  => $user,
                          'usPass'  => $hashedPass,
                          'usEmail' => $Email,
                          'usFull'  => $fullName,
                          'usavatar'  => $avaUnique
                        ]);
                    $count = $stmt->rowCount();
                    echo '<div class="container">';
                    $theMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                        . $stmt->rowCount() . " User Inserted </div>";
                    redirect ($theMsg , 'back'); // here i want to go manage
                    echo '</div>';
                    die();
                endif;
            }else{ // if there is error print error throw loop
                foreach ($errors as $er){
                    echo "<div class='alert alert-danger text-center font-weight-bold'>$er</div>" ;
                }
                echo '<div class="container">';
                    $errorMsg = '<div class="alert alert-danger">Wrong Input</div>';
                    redirect($errorMsg,'back');
                echo '</div>';
            }
            echo "</div>";

        else:
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>Cant Access Insert page Directly</div>";
            redirect($errorMsg,'back');
            echo '</div>';
            die();
        endif;

    }elseif ($do ==='Edit'){
           //  check if get request userId is numeric & get integer value
        $userId = (isset($_GET['UserID']) && is_numeric($_GET['UserID'])) ? intval($_GET['UserID']) : 0;
           // select all data depend on this id
        $qurey= "SELECT * FROM users WHERE UserID=? /*AND GroupId !=1*/ LIMIT 1";
        $stmt = $pdo->prepare($qurey);
           // execute query
        $stmt->execute([$userId]);
           // fetch the data
        $row = $stmt->fetch();
           // the row count
        $count = $stmt->rowCount();
           // if there is such id show the form
        if($count > 0) : ?>
            <!-- this is the form where i well edit in it -->
            <h1 class="text-center text-primary">Welcome <?=$_SESSION['logged_in']?> To Edit Page </h1>
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

            // elzero query
           /* $query = "SELECT * FROM users Where UserName=? AND UserID !=?  ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$user,$userid]);
                $count = $stmt->rowCount();
                if($count == 1){
                    $errors[] = 'This username is already Exist';
                }*/

            // if user dose not changed the image
            $uploadImage = '';
            if(empty( $ava['error'])){
                $imgUnique = rand(0,10000).'_'.$avaName ;
                $uploadImage = " avatar = '{$imgUnique}' , ";
                move_uploaded_file($avaTmp,'uploads\items\\'.$imgUnique);
            }

                // if there is no errors and username not taken
            if (empty($errors)){ // if there is no errors
                // update data in database with the new data
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

    }elseif ($do === 'Delete'){
            // Check If Get Request userid Is Numeric & Get The Integer Value of it
        $userId = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? $_GET['UserID'] : 0;
            // Select All Data Depend On This ID
        $check = checkExist("UserID","users",$userId);

        if ($check > 0){
            $qurey= "DELETE FROM users WHERE UserID= :userId";
            $stmt = $pdo->prepare($qurey);
            $stmt->bindParam(":userId",$userId);
            $stmt->execute();
            $_SESSION['delete'] = 'member deleted';
            header("Location:Members.php?do=Manage");
            die();
        }else{
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> user not found</div>";
                redirect($errorMsg);
             echo '</div>';
        }
    }elseif ($do === 'active'){
        $userId = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']):0;
        $check = checkExist("UserID","users",$userId);
        if ($check == 1){
            $qurey= "UPDATE users SET RegStatus=1 WHERE UserID=:userId";
            $stmt = $pdo->prepare($qurey);
            $stmt->bindParam(":userId",$userId);
            $stmt->execute();
            $_SESSION['active'] = 'member activated';
            header("Location:Members.php?do=Manage&member=pending");
            die();
        }else{
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> user not found</div>";
            redirect($errorMsg);
            echo '</div>';
        }
    }

    include $tpl .'footer.inc.php';
}else{
   header("Location:index.php?error=WrongUser");
   die();
}

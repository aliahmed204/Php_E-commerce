<?php

    /*
    ** Manage comments Page
    ** You Cab Read | Edit | Delete | Approve comments From Here
    */
session_start();
if(isset($_SESSION['logged_in'])){
    $theTitle = 'Comments';
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    // start Manage Page
    if($do === 'Manage'){
        // select all users except admin == dont need any safity here
        $forPending='';
        if (isset($_GET['comment']) && $_GET['comment'] == 'approve'){
            $forPending = "WHERE status=0 ";
        }
        $forUserComment='';
        if (isset($_GET['comID'])){
            $USERID = $_GET['comID'];
            $forUserComment = "WHERE c_id=$USERID ";
        }

        try{
            $query= "SELECT 
                    comments.* , items.item_name , users.UserName
                FROM comments 
                INNER JOIN items ON items.item_id = comments.item_ID
                INNER JOIN users ON users.UserID = comments.user_ID $forUserComment $forPending ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $coms = $stmt->fetchAll();
        }catch (PDOException $e){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        }
        if(empty($coms)){
            echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message">There Is No Record To Show  </div>
                 </div>';
        }else{
             ?>
        <h1 class="text-center text-primary">Manage Comments </h1>
        <?php
        if(isset($_SESSION['delete'])){
            echo "<div class='container'>
                             <div class='alert alert-success text-center font-weight-bold'>
                                         Comment deleted 
                                </div>
                           </div>";
        }
        unset($_SESSION['delete']);
        if(isset($_SESSION['active'] )){
            echo "<div class='container'> 
                            <div class='alert alert-success text-center font-weight-bold'>
                                the comment has been Activated  
                             </div>
                             <div class='alert alert-success text-center font-weight-bold'>
                                Now Users Can See It   
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
                        <th>Comment</th>
                        <th>Add Date</th>
                        <th>The Item</th>
                        <th>The User</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($coms as $com):
                        echo '<tr>
                        <th>'.$com['c_id'].'</th>
                        <td>'.$com['comment'].'</td>
                        <td>'.$com['c_date'].'</td>
                        <td>'.$com['item_name'].'</td>
                        <td>'.$com['UserName'].'</td>
                        <td>
                        <a href="?do=Edit&comID='.$com['c_id'].'" class="btn btn-success"><i class="fa fa-edit "></i>Edit</a>
                        <a href="?do=Delete&comID='.$com['c_id'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</a>';
                        if($com['status'] == 0) echo '<a href="?do=approve&comID='.$com['c_id'].'" class="btn btn-info ml-1"><i class="fa fa-edit"></i>Approve</a>
                    </td>
                </tr>';
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
    <?php }
    } elseif ($do ==='Edit'){
           try{
               //  check if get request userId is numeric & get integer value
               $comId = (isset($_GET['comID']) && is_numeric($_GET['comID'])) ? intval($_GET['comID']) : 0;
               // select all data depend on this id
               $query= "SELECT * FROM comments WHERE c_id=?";
               $stmt = $pdo->prepare($query);
               // execute query
               $stmt->execute([$comId]);
               // fetch the data
               $com = $stmt->fetch();
               // the row count
               $count = $stmt->rowCount();
           }catch (PDOException $e){
               echo '<div class="container">';
               $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
               redirect($errorMsg,'',2);
               echo '</div>';
           }
            // if there is no such id show error message
            if($count == 0):
                echo '<div class="container">';
                    $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>
                                    There Is No Such Id
                                  </div>";
                    redirect($errorMsg);
                echo '</div>';
                die();
            endif; ?>
            <h1 class="text-center text-primary">Welcome <?=$_SESSION['logged_in']?> To Edit Page </h1>
            <?php
                // to show error message if there is any thing wrong when make udate
                if(isset($_SESSION['EditErr'] )){
                    echo "<div class='container'> 
                                    <div class='alert alert-success text-center font-weight-bold'>
                                       ". $_SESSION['EditErr']." 
                                     </div>
                                   </div>";
                }
                unset($_SESSION['EditErr']);
            ?>
                <!--  if the id has record in DB show the form -->
                <!-- this is the form where i well edit in it -->
            <div class="container">
                <form class="form-horizontal align-content-center" action="?do=Update" method="POST">
                    <!-- Hidden field for ID -->
                    <input  type="hidden" name="comId" value="<?= $comId ?>"  autocomplete="off"  />
                    <!-- end Id field -->
                    <!-- Start Comment field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6 ">
                            <textarea class="form-control " name="comCom" autocomplete="off"  >
                                <?= $com['comment']?>
                            </textarea>
                        </div>
                    </div>
                    <!-- End Comment field -->
                    <!-- Start Status field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Status </label>
                        <div class="col-sm-10 col-md-6 ">
                            <select class="form-control" name="comStatus">
                                <option disabled>Choose Status </option>;
                                <option value="0" <?php if ( $com['status'] == 0){ echo "selected";} ?>>NOT Approved</option>
                                <option value="1" <?php if ( $com['status'] == 1){ echo "selected";} ?>>Approved</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status field -->
                    <!-- Start User field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> User </label>
                        <div class="col-sm-10 col-md-6 ">
                            <select class="form-control" name="comUser">
                                <option disabled>Choose USER </option>;
                                <?php  $rows = getAllDate('*','users');
                                   foreach ($rows as $row): ?>
                                    <option value="<?=$row['UserID']?>"<?php if ( $com['c_id'] == $row['UserID'])echo "selected" ?>>
                                        <?=$row['UserName']?>
                                    </option>
                                <?php  endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- End User field -->

                    <!-- Start item field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> Item </label>
                        <div class="col-sm-10 col-md-6 ">
                            <select class="form-control" name="comItem">
                                <option disabled>Choose ITEM </option>;
                                <?php $items = getAllDate('*','items');
                                   foreach ($items as $item): ?>
                                    <option value="<?=$item['item_id']?>" <?php if($com['c_id'] == $item['item_id'])echo "selected" ?>>
                                        <?=$item['item_name']?>
                                    </option>
                                <?php  endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- End item field -->
                    <!-- Start submit field -->
                    <div class="form-group form-group-lg ">
                        <div class="col-sm-offset-2 col-sm-10 ">
                            <input class="btn btn-success btn-lg" type="submit" value="Update"   />
                        </div>
                    </div>
                    <!-- End submit field -->
                </form>
            </div>
 <?php  } elseif ($do === 'Update'){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'):
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> You Can't Access update Page Directly </div>";
            redirect($errorMsg);
            echo '</div>';
            die();
        endif;
            echo "<h1 class='text-center text-primary'>Welcome To update Page </h1>";
            echo "<div class='container'>";
                // get variables form the form
                foreach ($_POST as $key=>$value){
                    $$key = $value;
                }
                    // there is no empty comment
                if (isEmpty($comCom)){
                    $error = 'comment can not be empty';
                }
                    // if comment empty close connect and print error in edit form
                if (!empty($error)) {
                    // i want to show error in edit form
                    $_SESSION['EditErr'] = "$error";
                        header("Location:?do=Edit&comID=$comId");
                    die();
                }
                // if there is no error well come here and do the update
                    // update data in database with the new data
                   try{
                       $qurey= "UPDATE comments SET comment=?, status=?, item_ID=? ,user_ID =? WHERE c_id=?";
                       $stmt = $pdo->prepare($qurey);
                       // execute query
                       $stmt->execute([$comCom,$comStatus,$comItem,$comUser,$comId]);
                       // if success echo success
                       echo '<div class="container">';
                       $errorMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                           . $stmt->rowCount() . " Record updated </div>";
                       redirect($errorMsg ,'back');
                       echo '</div>';
                   }catch (PDOException $e){
                       echo '<div class="container">';
                       $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
                       redirect($errorMsg,'',2);
                       echo '</div>';
                   }
            echo "</div>";
    }elseif ($do === 'Delete'){
        // Check If Get Request userid Is Numeric & Get The Integer Value of it
        $comId = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;
        // Select All Data Depend On This ID
        $check = checkExist("c_id","comments",$comId);
            if($check == 0):
                echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This category </div>";
                redirect($errorMsg,'',2);
                echo '</div>';
            endif;
        try{
            toDelete('comments' , 'c_id',$comId );
            $_SESSION['delete'] = 'one item deleted';
            header("Location:?do=Manage");
            die();
        }catch (PDOException $e){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        }

    }elseif ($do === 'approve'){
        $comId = isset($_GET['comID']) && is_numeric($_GET['comID']) ? intval($_GET['comID']) : 0;
        $check = checkExist("c_id","comments",$comId);
            if ($check != 1){
                echo '<div class="container">';
                    $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> user not found</div>";
                    redirect($errorMsg);
                echo '</div>';
                die();
            }
        try{
            $qurey= "UPDATE comments SET status=1 WHERE c_id=:comId";
            $stmt = $pdo->prepare($qurey);
            $stmt->bindParam(":comId",$comId);
            $stmt->execute();
            $_SESSION['active'] = 'comment activated';
            header("Location:?do=Manage&comment=active");
            die();
        }catch (PDOException $e){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        }


    }

    include $tpl .'footer.inc.php';
}else{
    header("Location:index.php?error=WrongUser");
    die();
}


<?php

session_start();
$theTitle = 'Items Page';
if (isset($_SESSION['logged_in'])){
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do === 'Manage'){

        $forApprove = '';
        if (isset($_GET['item']) && $_GET['item'] == 'approve'){
            $forApprove = "WHERE Approve=0";
        }

        $showOne = '';
        if (isset($_GET['itemID']) && is_numeric( $_GET['itemID'])){
            $item_id = intval($_GET['itemID']);
            $showOne = "WHERE items.item_id = {$item_id}";
        }

        $items = getAllDate(
            "items.* , 
                    categories.Name As cat_name , 
                    users.UserName AS user_name",
            "items INNER JOIN categories ON categories.ID = items.Cat_ID 
                         INNER JOIN users ON users.UserID = items.Member_ID 
             $forApprove $showOne ");
      /*$query =  "SELECT items.* ,
                        categories.Name As cat_name ,
                        users.UserName AS user_name
                 FROM items
                 INNER JOIN categories ON categories.ID = items.Cat_ID
                 INNER JOIN users ON users.UserID = items.Member_ID";
        $stmt = $pdo->prepare($query);
        $stmt ->execute();
        $items = $stmt->fetchAll();*/

        ?>
        <h1 class="text-center text-primary"> Manage Items </h1>
        <?php
            if(isset($_SESSION['delete'])){
                echo "<div class='container'>
                                 <div class='alert alert-success text-center font-weight-bold'>
                                             item deleted
                                    </div>
                               </div>";
            }
            unset($_SESSION['delete']);
        if(isset($_SESSION['active'] )){
            echo "<div class='container'> 
                            <div class='alert alert-success text-center font-weight-bold'>
                                The Item has been Approved  
                             </div>
                           </div>";
        }
        unset($_SESSION['active']);
        if(empty($items)){
            echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message">There Is No Record To Show  </div>
                   <a href="?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New Item</a>
                 </div>';
        }else{
        ?>
        <div class="container">
            <div class="table-responsive ">
                <table class="table table-bordered text-center ">
                    <tr class="table-active" >
                        <th>#ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Country OF Made</th>
                        <th>Add date</th>
                        <th>category</th>
                        <th>Username</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($items as $item):
                        echo '<tr>
                        <th>'.$item['item_id'].'</th>';
                            if(!empty($item['item_image'])){ // if user has img in Db get It
                                echo '<th><img src="uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                            }else{      // if user Dose Not have img in Db Show Defult Img
                                echo '<th><img src="uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                            }
                       echo '<td>'.$item['item_name'].'</td>
                        <td>'.$item['item_description'].'</td>
                        <td>'.$item['item_price'].'</td>
                        <td>'.$item['country_made'].'</td>
                        <td>'.$item['add_date'].'</td>
                        <td>'.$item['cat_name'].'</td>
                        <td>'.$item['user_name'].'</td>
                        <td>
                        <a href="?do=Edit&itemID='.$item['item_id'].'" class="btn btn-success"><i class="fa fa-edit "></i>Edit</a>
                        <a href="?do=Delete&itemID='.$item['item_id'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</a>';
                        if($item['Approve'] == 0)
                            echo '<a href="?do=Approve&itemID='.$item['item_id'].'"class="btn btn-info ml-1"><i class="fa fa-check"></i>Approve</a>
                    </td>
                </tr>';
                    endforeach;
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="btn btn-primary my-3"><i class="fa fa-plus "></i> Add New Item</a>
        </div>



     <?php  } }elseif ($do === 'Add'){ ?>
          <h1 class="text-center text-primary"> Add New Item </h1>
        <div class="container">
            <form class="form-horizontal align-content-center" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemName" autocomplete="off" required="required" placeholder="Name Of The Item" />
                    </div>
                </div>
                <!-- End Name field -->

                <!-- Start Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Description </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemDesc" autocomplete="off" required="required" placeholder="Item Description" />
                    </div>
                </div>
                <!-- End Description field -->

                <!-- Start Price field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Price </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemPrice" autocomplete="off" required="required" placeholder="Item Price" />
                    </div>
                </div>
                <!-- End Price field -->

                <!-- Start Country field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Country OF Made </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemCountry" autocomplete="off" required="required" placeholder="Country OF Made" />
                    </div>
                </div>
                <!-- End Country field -->

                <!-- Start Status field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Status </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemStatus">
                            <option value="0" selected hidden>Choose Status </option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status field -->

                <!-- Start Member field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Member </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemMember">
                            <option value="0" selected hidden>Choose member</option>
                            <?php
                                 $rows = getAllDate('*','users');
                                foreach ($rows as $row){
                                   echo '<option value="'.$row['UserID'].'">'.$row['UserName'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Member field -->

                <!-- Start categories field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">  Category </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemCategory">
                            <option value="0" selected hidden>Choose Category </option>
                            <?php
                            $cats = getAllDate('*','categories  WHERE Parent=0');
                            foreach ($cats as $cat){
                                echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
                                $childCats = getAllDate("*","categories  WHERE Parent={$cat['ID']}");
                                        // for first Child
                                foreach ($childCats as $childCat){
                                    echo '<option value="'.$childCat['ID'].'"> ->  '.$childCat['Name'].'</option>';
                                        // for Sub Child
                                    $subChildCats = getAllDate("*","categories  WHERE Parent={$childCat['ID']}");
                                    foreach ($subChildCats as $subChildCat){
                                        echo '<option value="'.$subChildCat['ID'].'"> -> '.$childCat['Name'].$subChildCat['Name'].'</option>';
                                    }
                                }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End categories field -->

                <!-- Start Tags field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Tags </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemTags" placeholder="Add Tag and Separate with Comma (,)" />
                    </div>
                </div>
                <!-- End Tags field -->

                <!-- Start img field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Add Image </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="file" name="itemImage" required="required" />
                    </div>
                </div>
                <!-- End img field -->


                <!-- Start submit field -->
                <div class="form-group form-group-lg ">
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <input class="btn btn-primary" type="submit" value="Add"   />
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div>
<?php
    }elseif ($do === 'Insert'){
        echo "<h1 class='text-center text-primary'>Insert Item </h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] === 'POST'):
            foreach ($_POST as $k=>$v):
                $$k = $v;
            endforeach;
            // $itemName , $itemDesc, $itemPrice ,$itemCountry , $itemStatus
            $itemImage = $_FILES['itemImage'];
            $itemImageName = $itemImage['name'];
            $itemImageType = $itemImage['type'];
            $itemImageTmp = $itemImage['tmp_name'];
            $itemImageSize = $itemImage['size'];


            $types = ['jpg','jpeg','png'];
            $imgType = explode('.',$itemImageName);
            $imgAllowd = strtolower(end($imgType));

           
            // validate the form
            $errors = [];
            if (empty($itemName)){
                $errors[] = 'Name is require';
            }elseif (minChar($itemName ,3)){
                $errors[] = 'Name must be Greater then 3 characters';
            }elseif (maxChar($itemName ,30)){
                $errors[] = 'Name must be Less then 30 characters';
            }

            if (empty($itemDesc)){
                $errors[] = 'Description can\'t be Empty';
            }
            if (empty($itemPrice)){
                $errors[] = 'Price can\'t be Empty';
            }
            if (empty($itemCountry)){
                $errors[] = 'Country OF Made Field can\'t be Empty';
            }
            if ($itemStatus == 0){
                $errors[] = 'You Must Choose the Status';
            }
            if ($itemMember == 0){
                $errors[] = 'You Must Choose the User';
            }
            if ($itemCategory == 0){
                $errors[] = 'You Must Choose the Category ';
            }
            if (empty($imgAllowd)){
                $errors[] = 'Item Should have An Image';
            }elseif (!empty($imgAllowd) && !in_array($imgAllowd , $types)){
                $errors[] = 'Image Extension You Used Are Not Allowed ';
            }

            if (!empty($errors)) { // if there is no errors
                foreach ($errors as $er){
                    echo "<div class='alert alert-danger text-center font-weight-bold'>$er</div>" ;
                }
                echo '<div class="container">';
                $errorMsg = '<div class="alert alert-danger">Wrong Input</div>';
                redirect($errorMsg,'back');
                echo '</div>';
            }else{ // if there is error print error throw loop
                 try{
                     //ALTER TABLE items ADD CONSTRAINT Number_1 FOREIGN KEY(Member_ID) REFERENCES users(UserID) ON DELETE CASCADE ON UPDATE CASCADE
                     //     item_name,item_description,item_price,country_made,item_status,add_date,Cat_ID,Member_ID
                     // $itemName , $itemDesc, $itemPrice ,$itemCountry , $itemStatus , $itemMember , $itemCategory
                     // (`shop`.`items`, CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE)
                       $imgUnique = rand(0,10000).$itemImageName ;
                       move_uploaded_file($itemImageTmp,'uploads\items\\'.$imgUnique);
                        $query= "INSERT INTO
                                    items (item_name,item_description,item_price,item_image,country_made,item_status,add_date,Cat_ID,Member_ID,tags) 
                                VALUES (:iName,:iDesc,:iPrice,:itheimg,:iCountry,:iStatus,NOW() ,:iCategory,:iMember,:itags )";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([
                            'iName'    => $itemName,
                            'iDesc'    => $itemDesc,
                            'iPrice'   => $itemPrice,
                            'itheimg'   => $imgUnique,
                            'iCountry' => $itemCountry,
                            'iStatus'  => $itemStatus,
                            'iMember'  => $itemMember,
                            'iCategory'=> $itemCategory,
                            'itags'    => $itemTags

                        ]);
                        $count = $stmt->rowCount();
                        echo '<div class="container">';
                        $theMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                            . $stmt->rowCount() . " Item Inserted </div>";
                        redirect ($theMsg , 'back'); // here i want to go manage
                        echo '</div>';
                        die();
                    }catch (PDOException $e){
                        echo '<div class="container">';
                            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
                            redirect($errorMsg,'',2);
                        echo '</div>';
                    }
            }
            echo "</div>";

        else:
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>Cant Access Insert page Directly</div>";
            redirect($errorMsg,'back');
            echo '</div>';
            die();
        endif;
        echo "</div>";
    }elseif ($do === 'Edit'){
        $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? $_GET['itemID'] : 0 ;
        $Query ="SELECT * FROM items WHERE item_id=?";
        // Prepare Statement for Execute
        $stmt = $pdo->prepare($Query);
        // Execute Query
        $stmt->execute([$itemID]);
        // Fetch The Data
        $item = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        if ($count != 1 ){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>There Is No Such Id.</div>";
            redirect($errorMsg);
            echo '</div>';
            die();
        }
        ?>
        <h1 class="text-center text-primary"> Edit Item </h1>
        <div class="container">
            <form class="form-horizontal align-content-center" action="?do=Update" method="POST" enctype="multipart/form-data">
                <!-- Hidden field for ID -->
                        <input   value="<?= $itemID ?>" type="hidden" name="itemId"  autocomplete="off"  />
                <!-- end Id field -->

                <!-- Start Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " value="<?= $item['item_name']?>" type="text" name="itemName" autocomplete="off" required="required" placeholder="Name Of The Item" />
                    </div>
                </div>
                <!-- End Name field -->

                <!-- Start Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Description </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control "   value="<?= $item['item_description']?>" type="text" name="itemDesc" autocomplete="off" required="required" placeholder="Item Description" />
                    </div>
                </div>
                <!-- End Description field -->

                <!-- Start Price field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Price </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control "  value="<?= $item['item_price']?>"  type="text" name="itemPrice" autocomplete="off" required="required" placeholder="Item Price" />
                    </div>
                </div>
                <!-- End Price field -->

                <!-- Start Country field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Country OF Made </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control "  value="<?= $item['country_made']?>" type="text" name="itemCountry" autocomplete="off" required="required" placeholder="Country OF Made" />
                    </div>
                </div>
                <!-- End Country field -->

                <!-- Start Status field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Status </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemStatus">
                            <option value="0"  disabled>Choose Status </option>;
                            <option value="1" <?php if ( $item['item_status'] == 1){ echo "selected";} ?>>New</option>
                            <option value="2" <?php if ( $item['item_status'] == 2){ echo "selected";} ?>>Like New</option>
                            <option value="3" <?php if ( $item['item_status'] == 3){ echo "selected";} ?>>Used</option>
                            <option value="4" <?php if ( $item['item_status'] == 4){ echo "selected";} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status field -->

                <!-- Start Member field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Member </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemMember">
                            <option value="0"  disabled>Choose member</option>
                            <?php
                            $rows = getAllDate('*','users');
                            foreach ($rows as $row){
                                echo '<option value="'.$row['UserID'].'" ';
                                if( $item['Member_ID'] == $row['UserID']){ echo "selected"; }
                                echo '>'.$row['UserName'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Member field -->

                <!-- Start categories field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">  Category </label>
                    <div class="col-sm-10 col-md-6 ">
                        <select class="form-control" name="itemCategory">
                            <option value="0" disabled >Choose Category </option>
                            <?php
                            $cats = getAllDate('*','categories');
                            foreach ($cats as $cat){
                                echo '<option value="'.$cat['ID'].'" '; if( $item['Cat_ID'] == $cat['ID']){ echo "selected"; } echo '>'.$cat['Name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End categories field -->

                <!-- Start Tags field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Tags </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="itemTags" value="<?= $item['tags'] ?>" placeholder="Add Tag and Separate with Comma (,)" />
                    </div>
                </div>
                <!-- End Tags field -->

                <!-- Start img field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Add Image </label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="file" name="itemImage"  />
                    </div>
                </div>
                <!-- End img field -->

                <!-- Start submit field -->
                <div class="form-group form-group-lg ">
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <input class="btn btn-primary" type="submit" value="Update"   />
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        <?php
            try{
                $query= "SELECT comments.* , users.UserName
                         FROM comments INNER JOIN users ON users.UserID = comments.user_ID
                         WHERE item_ID=?";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$itemID]);
                    $coms = $stmt->fetchAll();
                }catch (PDOException $e){
                     echo '<div class="container">';
                        $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
                        redirect($errorMsg,'',2);
                     echo '</div>';
                }
                // if this item not has any comment will show this message and will not show the table
                if (empty($coms)){
                    echo "<div class='alert alert-info text-center font-weight-bold'>
                           This item dose not have any comments
                         </div>";
                    exit();
                }
            ?>
            <!-- if item id has any comments will show them in table  -->
            <h1 class="text-center text-primary">Manage [ <?= $item['item_name']?> ] comments </h1>
                <div class="table-responsive ">
                    <table class="table table-bordered text-center ">
                        <tr class="table-active" >
                            <th>Comment</th>
                            <th>Add Date</th>
                            <th>The User</th>
                            <th>Control</th>
                        </tr>
                        <?php
                        foreach ($coms as $com):
                            echo '<tr>
                        <td>'.$com['comment'].'</td>
                        <td>'.$com['c_date'].'</td>
                        <td>'.$com['UserName'].'</td>
                        <td>
                        <a href="comments.php?do=Edit&comID='.$com['c_id'].'" class="btn btn-success"><i class="fa fa-edit "></i>Edit</a>
                        <a href="comments.php?do=Delete&comID='.$com['c_id'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</a>';
                            if($com['status'] == 0) echo '<a href="comments.php?do=approve&comID='.$com['c_id'].'" class="btn btn-info ml-1"><i class="fa fa-edit"></i>Approve</a>
                    </td>
                </tr>';
                        endforeach;
                        ?>
                    </table>
                </div>
        </div>
   <?php }elseif ($do === 'Update'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center text-primary'>UPDATE Item </h1>";
                echo "<div class='container'>";
                foreach ($_POST as $key => $value):
                    $$key = $value;
                endforeach;
                $errors = [];
                if (isEmpty($itemName)){
                    $errors[] = 'Name is require';
                }elseif (minChar($itemName ,3)){
                    $errors[] = 'Name must be Greater then 3 characters';
                }elseif (maxChar($itemName ,30)){
                    $errors[] = 'Name must be Less then 30 characters';
                }

                if (isEmpty($itemDesc)){
                    $errors[] = 'Description can\'t be Empty';
                }
                if (isEmpty($itemPrice)){
                    $errors[] = 'Price can\'t be Empty';
                }
                if (isEmpty($itemCountry)){
                    $errors[] = 'Country OF Made Field can\'t be Empty';
                }
                if ($itemStatus == 0){
                    $errors[] = 'You Must Choose the Status';
                }
                if ($itemMember == 0){
                    $errors[] = 'You Must Choose the User';
                }
                if ($itemCategory == 0){
                    $errors[] = 'You Must Choose the Category ';
                }

                if (!empty($errors)) { // if there is no errors
                    foreach ($errors as $er) {
                        echo "<div class='alert alert-danger text-center font-weight-bold'>$er</div>";
                    }
                    echo '<div class="container">';
                    $errorMsg = '<div class="alert alert-danger">Wrong Input</div>';
                    redirect($errorMsg, 'back');
                    echo '</div>';
                }
                $itemImage = $_FILES['itemImage'];
                $itemImageName = $itemImage['name'];
                $itemImageType = $itemImage['type'];
                $itemImageTmp = $itemImage['tmp_name'];
                $itemImageSize = $itemImage['size'];
                $types = ['jpg','jpeg','png'];
                $imgType = explode('.',$itemImageName);
                $imgAllowd = strtolower(end($imgType));

               /* Query ErrorSQLSTATE[42000]: Syntax error or access violation:
                1064 You have an error in your SQL syntax;
                check the manual that corresponds to your MariaDB server version
                for the right syntax to use near 'jpg WHERE item_id' at line 6*/
                $uploadImage = '';
                    if(empty( $itemImage['error'])){
                        $imgUnique = rand(0,10000).$itemImageName ;
                        $uploadImage = " item_image = '{$imgUnique}' , ";
                        move_uploaded_file($itemImageTmp,'uploads\items\\'.$imgUnique);
                    }else{
                        $errors[] = $itemImage['error'] . ' Error In File ' ;
                    }

                try{

                    $query= "UPDATE 
                                items 
                             SET 
                                 $uploadImage
                                 item_name =?, item_description =?,item_price =?,
                                 country_made =?,item_status =?,Cat_ID =?,Member_ID =?, 
                                 tags =? 
                             WHERE 
                                item_id =? ";
                    //Cannot add or update a child row: a foreign key constraint fails
                    // (`shop`.`items`, CONSTRAINT `cat_1`
                    // FOREIGN KEY (`Cat_ID`) REFERENCES `categories`
                    // (`ID`) ON DELETE CASCADE ON UPDATE CASCADE)
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$itemName,$itemDesc,$itemPrice,$itemCountry,$itemStatus,$itemCategory,$itemMember,$itemTags,$itemId]);
                    $count = $stmt->rowCount();
                    echo '<div class="container">';
                    $theMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                        . $stmt->rowCount() . " Item Updated </div>";
                    redirect ($theMsg , 'back'); // here i want to go manage
                    echo '</div>';
                    die();
                }catch (PDOException $e){
                    echo '<div class="container">';
                    $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
                    redirect($errorMsg,'',10);
                    echo '</div>';
                }


            }
    }elseif ($do === 'Delete'){
        $itemID = $_GET['itemID']&&is_numeric($_GET['itemID'])?$_GET['itemID'] : 0 ;
        $check = checkExist("item_id","items",$itemID);
        if($check != 1):
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This category </div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        endif;
        try{
            toDelete('items' , 'item_id',$itemID );
            $_SESSION['delete'] = 'one item deleted';
            header("Location:?do=Manage");
            die();
        }catch (PDOException $e){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        }

    }elseif ($do === 'Approve'){
        $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID'])?intval($_GET['itemID']):0;
        $check = checkExist('item_id','items',$itemID);
        if ($check != 1){
            echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> item not found</div>";
                redirect($errorMsg);
            echo '</div>';
        }
        try{
            $qurey= "UPDATE items SET Approve=1 WHERE item_id=:itemId";
            $stmt = $pdo->prepare($qurey);
            $stmt->bindParam(":itemId",$itemID);
            $stmt->execute();
            $_SESSION['active'] = 'member activated';
            header("Location:?do=Manage");
            die();
        }catch (PDOException $e){
            echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>
                                Query Error".$e->getMessage().
                            "</div>";
                redirect($errorMsg,'',2);
            echo '</div>';
        }



    }


    include $tpl.'footer.inc.php';
}else{
    header("Location:index.php?error=WrongUser");
    die();
}

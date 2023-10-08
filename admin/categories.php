<?php
session_start();
if(isset($_SESSION['logged_in'])) {
    $theTitle = 'Categories';
    include 'init.php';
    // split pages
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage'){
        $order='ASC';
        $sort = ['ASC','DESC'];
        if(isset($_GET['order']) && in_array($_GET['order'], $sort)){
            $order = $_GET['order'] ;
        }
        $byWhat = 'Ordering';
        $meanOrder = ['Ordering','ID'];
        if(isset($_GET['by']) && in_array($_GET['by'], $meanOrder)){
            $byWhat = $_GET['by'] ;
        }

        // Show This Message when delete category
        if(isset($_SESSION['delete'])){
            echo "<div class='container'>
                             <div class='alert alert-success text-center font-weight-bold'>
                                        category deleted 
                                </div>
                           </div>";
        }
        unset($_SESSION['delete']);


       $cats = getAllDate("*","categories WHERE Parent=0  order by $byWhat $order");
       if (empty($cats)){
           echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message">There Is No Record To Show  </div>
                   <a href="?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New Category</a>
                 </div>';
       }else{
       ?>
        <h1 class="text-center text-primary"> Manage Categories </h1>
        <div class="container categories latest">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"> <i class="fa fa-dashboard"></i> Manage Categories
                        <div class="ordering pull-right">
                            Order by: [
                            <a class="<?php if($byWhat == 'Ordering') echo 'active' ?>" href="?by=Ordering">Ordering</a> |
                            <a class="<?php if($byWhat == 'ID') echo 'active' ?>" href="?by=ID">ID</a> ]
                        </div><br />
                        <div class="ordering pull-right">
                            Ordering: [
                            <a class="<?php if($order == 'ASC') echo 'active' ?>" href="?by=<?=$byWhat?>&order=ASC">ASC</a> |
                            <a class="<?php if($order == 'DESC') echo 'active' ?>" href="?by=<?=$byWhat?>&order=DESC">DESC</a> ]
                        </div>

                    </div>
                    <div class="card-body">
                    <?php
                            // show categories in DB
                        foreach ($cats as $cat):
                            echo '<div class="cat">';
                                echo '<div class="hidden-buttons">';
                                    echo '<a href="?do=Edit&id='.$cat['ID'].'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=Delete&id='.$cat['ID'].'" class="confirm btn btn-xs btn-danger"><i class="fa fa-remove"></i> Delete</a>';
                                echo '</div>';
                                echo '<h3>' . $cat['Name'] . '</h3>' ;
                                echo '<p class="ml-2">'; if (empty( $cat['Description']))echo "There Is No Description For This Category" ;else{echo  $cat['Description'];} echo '</p>';
                                if($cat['Visibility'] == 1)echo '<span class="globalSpan visibility"><i class="fa fa-eye"></i>Hidden</span>'; // i want print it for hidden category only
                                if($cat['Allow_Comment'] == 1)echo '<span class="globalSpan comment"></i>Comment Disabled</span>';  // i want print it for hidden category only
                                if($cat['Allow_Ads'] == 1) echo '<span class="globalSpan advertising"></i>Ads Not Allowed</span>';  // i want print it for hidden category only

                            // get subCategories
                            $subCats = getAllDate("*","categories WHERE Parent ={$cat['ID']}");
                            if (!empty($subCats)){
                                echo "<h4 class='text-secondary ml-2 mt-2'>Child Category </h4>";
                                foreach ($subCats as $subCat):
                                    echo "<ul class='list-unstyled'>
                                            <li style='margin: 0 0 -9px 20px'> ";
                                        echo '<a href="?do=Edit&id='.$subCat['ID'].'">';
                                    echo  " - {$subCat['Name']}
                                             </a>
                                             </li>
                                        </ul>" ;
                                    // if there is  sub child
                                    $subCat2 = getAllDate("*","categories WHERE Parent ={$subCat['ID']}");
                                    echo "<h4 class='text-secondary ml-2 mt-2'>Sub-Child Category </h4>";
                                    foreach ($subCat2 as $sub2){
                                        if ($subCat['ID'] == $sub2['Parent'] ){
                                            echo "<ul class='list-unstyled'>
                                            <li style='margin: 0 0 -9px 20px'> ";
                                            echo '<a href="?do=Edit&id='.$sub2['ID'].'">';
                                            echo  " - {$sub2['Name']}
                                             </a>
                                             </li>
                                        </ul>" ;
                                        }
                                    }

                                endforeach;
                            }

                            echo '</div>';
                            echo '<hr>';
                       endforeach;
                    ?>
                    </div>
                </div>
                <a href="?do=Add" class="add-cat btn btn-primary"><i class="fa fa-plus"></i> Add New Categroy</a>
            </div>
        </div>



    <?php } } elseif ($do == 'Add'){?>
        <h1 class="text-center text-primary"> Add New Category </h1>
        <div class="container">
            <?php
                if(isset($_SESSION['oldRecord'] )){
                    echo "<div class='container'>
                                <div class='alert alert-info text-center font-weight-bold'>
                                     This UserName Is Used 
                                </div>
                             </div>";
                }
                unset($_SESSION['oldRecord']);
                if(isset($_SESSION['INSERTED'] )){
                    echo "<div class='container'>
                                <div class='alert alert-success text-center font-weight-bold'>
                                     Category Inserted
                                </div>
                             </div>";
                }
                unset($_SESSION['INSERTED']);
            ?>
            <form class="form-horizontal align-content-center" action="?do=Insert" method="POST">
                <!-- Start Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Category Name</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="theName" autocomplete="off" required="required" placeholder="Name Of The Category" />
                    </div>
                </div>
                <!-- End Name field -->
                <!-- Start Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="Desc"  placeholder="Describe The Category" />
                    </div>
                </div>
                <!-- End Description field -->
                <!-- Start Ordering field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="Order" autocomplete="off"  placeholder="Number To Arrange The Categories"/>
                    </div>
                </div>
                <!-- End Ordering field -->

                <!-- Start Category type -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent ?</label>
                    <div class="col-sm-10 col-md-6 ">
                        <select name="Parent" class="custom-select text-secondary" >
                            <option value="0" > Main category </option>
                            <?php
                            $parents = getAllDate('*','categories','WHERE Parent = 0'); // i want to get parents
                            foreach ($parents as $parent){
                                echo '<option value="'.$parent['ID'].'">'.$parent['Name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category type -->

                <!-- Start Visibility field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="vis-yes" type="radio" name="Visibility" value="0" checked />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="Visibility" value="1" />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility field -->
                <!-- Start Commenting field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Comment</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="com-yes" type="radio" name="Comment" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="Comment" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting field -->
                <!-- Start Ads field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads </label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="Ads-yes" type="radio" name="Ads" value="0" checked />
                            <label for="Ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="Ads-no" type="radio" name="Ads" value="1" />
                            <label for="Ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Ads field -->
                <!-- Start submit field -->
                <div class="form-group form-group-lg ">
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <input class="btn btn-success btn-lg" type="submit" value="Add"   />
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div>

    <?php }elseif ($do == 'Insert'){
        echo "<h1 class='text-center text-primary'>Insert Category </h1>";
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] == 'POST'):
            foreach ($_POST as $k => $v):
                $$k = $v;
            endforeach;
            // $theName $Desc $Order $Visibility $Comment $Ads

            // check if Category Exist in Database
            if(!empty($theName)):
                $check = checkExist("Name","categories",$theName);
                if ($check == 1):
                    $_SESSION['oldRecord'] = 'This Category Is Already Exist';
                    header("Location:categories.php?do=Add");
                    die();
                else:
                    $qury = "INSERT INTO categories (Name,Description,Parent,Ordering,Visibility,Allow_Comment,Allow_Ads)
                                VALUES (:ZsName,:Zdesc,:Zparent,:Zorder,:ZVisibil,:Zcomm,:Zads)";
                    $stmt = $pdo->prepare($qury);
                    $stmt->execute([
                        ":ZsName"  => $theName,
                        ":Zdesc"   => $Desc,
                        ":Zparent"   => $Parent,
                        ":Zorder"  => $Order,
                        ":ZVisibil"=> $Visibility,
                        ":Zcomm"   => $Comment,
                        ":Zads"    => $Ads
                    ]);
                    $_SESSION['INSERTED'] = 'Insert Done';
                    header("Location:categories.php?do=Add");
        echo '</div>';
                    die();
                endif;
            else:
                echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>Cant Access Insert page Directly</div>";
                redirect($errorMsg,'back');
                echo '</div>';
                die();
            endif;
        endif;
    }elseif ($do == 'Edit'){
        // Check IF Get Request catId Is Numeric & Get Its Integer Value
        $catId = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id'] : 0;
        // Select All Data Depend On This ID
        $Query ="SELECT * FROM categories WHERE ID=? LIMIT 1";
        // Prepare Statement for Execute
        $stmt = $pdo->prepare($Query);
        // Execute Query
        $stmt->execute([$catId]);
        // Fetch The Data
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If There Is Record Show The From to make Updata
        if ($count == 1): ?>
        <div class="container ml-auto mr-auto w-75">
            <h1 class="text-center text-primary"> Edite Category </h1>
            <form class="form-horizontal align-content-center" action="?do=Update" method="POST">
                <!-- This field To Send id With Post request -->
                <input type="hidden" name="catID" value="<?=$row['ID']?>" />
                <!-- Start Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Category Name</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="theName" required="required" placeholder="Name Of The Category" value="<?=$row['Name']?>" />
                    </div>
                </div>
                <!-- End Name field -->
                <!-- Start Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="Desc"  placeholder="Describe The Category"  value="<?=$row['Description']?>" />
                    </div>
                </div>
                <!-- End Description field -->
                <!-- Start Ordering field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6 ">
                        <input class="form-control " type="text" name="Order" autocomplete="off"  placeholder="Number To Arrange The Categories" value="<?=$row['Ordering']?>"/>
                    </div>
                </div>
                <!-- End Ordering field -->

                <!-- Start Category type -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent ?</label>
                    <div class="col-sm-10 col-md-6 ">
                       <select name="Parent" class="custom-select text-secondary" >
                          <option value="0" > Main category </option>
                          <?php
                                $parents = getAllDate('*','categories','WHERE Parent = 0'); // i want to get parents
                                    foreach ($parents as $parent){
                                        echo '<option value="'.$parent['ID'].'"';
                                        if ( $row['Parent'] == $parent['ID'] ) echo 'selected=selected';
                                        echo '>'.$parent['Name'].'</option>';
                                    }
                          ?>
                       </select>
                    </div>
                </div>
                <!-- End Category type -->

                <!-- Start Visibility field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="vis-yes" type="radio" name="Visibility" value="0" <?php if ($row['Visibility'] == 0) echo 'checked';?> />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="Visibility" value="1" <?php if ($row['Visibility'] == 1) echo 'checked';?> />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility field -->
                <!-- Start Commenting field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Comment</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="com-yes" type="radio" name="Comment" value="0" <?php if ($row['Allow_Comment'] == 0) echo 'checked';?> />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="Comment" value="1" <?php if ($row['Allow_Comment'] == 1) echo 'checked';?> />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting field -->
                <!-- Start Ads field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads </label>
                    <div class="col-sm-10 col-md-6 ">
                        <div>
                            <input id="Ads-yes" type="radio" name="Ads" value="0" <?php if ($row['Allow_Ads'] == 0) echo 'checked';?> />
                            <label for="Ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="Ads-no" type="radio" name="Ads" value="1" <?php if ($row['Allow_Ads'] == 1) echo 'checked';?> />
                            <label for="Ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Ads field -->
                <!-- Start submit field -->
                <div class="form-group form-group-lg ">
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <input class="btn btn-success btn-lg  my-1" type="submit" value="Update"   />
                    </div>
                </div>
                <!-- End submit field -->
            </form>
         </div>

        <?php else:
            echo '<div class="container">';
            $errMsg = "<div class='alert alert-danger text-center font-weight-bold'>There Is No Such Id.</div>";
            redirect($errMsg , 'Previous Page');
            echo '</div>';
            die();
        endif;
    }elseif ($do == 'Update'){
         if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             echo "<h1 class='text-center text-primary'>Update Category </h1>";
             echo "<div class='container'>";
                 foreach ($_POST as $k => $v):
                     $$k = $v;

                 endforeach;
                 if (!empty($theName)):
                         $query = "UPDATE categories 
                                   SET `Name`=? , Description =? , Parent =? ,Ordering =?,Visibility=?,Allow_Comment=?,Allow_Ads=?
                                   WHERE ID=$catID  ";
                         $stmt=$pdo->prepare($query);
                         $stmt->execute([ $theName, $Desc, $Parent, $Order, $Visibility, $Comment, $Ads ]);

                         echo '<div class="container">';
                         $errorMsg = "<div class='alert alert-success text-center font-weight-bold'>"
                             . $stmt->rowCount() . " Record updated </div>";
                         redirect($errorMsg ,'back');
                         echo '</div>';
                 else:
                    echo 'can not be empty';
                 endif;
             echo "</div>";

         }else{
             echo '<div class="container">';
             $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> You Can't Access update Page Directly </div>";
             redirect($errorMsg);
             echo '</div>';
             die();
         }
    }elseif ($do == 'Delete'){
            $ID = $_GET['id']&&is_numeric($_GET['id'])? intval($_GET['id']) : 0 ;
            $check = checkExist("ID","categories",$ID);
            if($check > 0):
                try{
                    toDelete('categories','ID',$ID);
                    $_SESSION['delete'] = 'deleted';
                    header("Location:categories.php?do=Manage&deleted");
                    die();
                }catch (PDOException $e){
                    echo '<div class="container">';
                        $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
                        redirect($errorMsg,'',2);
                    echo '</div>';
                }
            else:
                echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This category </div>";
                redirect($errorMsg,'',2);
                echo '</div>';
            endif;
    }

    include $tpl .'footer.inc.php';
}else{
    header("Location:index.php?error=WrongUser");
    die();
}


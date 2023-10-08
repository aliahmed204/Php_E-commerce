<!-- login page -->
<?php
// session because we Depend on username that register in session var
// this page not publc for all users it for only one user
session_start();
$theTitle = 'Create New Item';
include 'init.php';

// NO user logged in SO Will Go To Main page of Store
if(!isset($_SESSION['UserName'])){
    header("Location:login.php");
    die();
}
// that's mean user logged in and will show this page for particular uesr
?>
<h1 class="text-center"><?=$theTitle?></h1>
<?php
// to get user data From Database
$value = UserDate($sessionUser);
// if user new and not active yet
if ($value['RegStatus'] == 0) {
    echo "<div class='container'>
              <div class='alert alert-primary text-center font-weight-bold'>
                 Hello " . $sessionUser . " You will Be Approved soon               
               </div>
             </div>";
}

?>
<div class="information block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                <?=$theTitle?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="container">
                            <?php
                                if(isset($_SESSION['adErrors'] )){
                                    foreach ($_SESSION['adErrors'] as $er){
                                        echo "<div class='container'>
                                        <div class='alert alert-danger text-center font-weight-bold'>
                                            $er
                                        </div>
                                     </div>";
                                    }
                                }
                                unset($_SESSION['adErrors']);
                                if(isset($_SESSION['item_inserted'] )){
                                    echo '<div class="container">';
                                    echo    "<div class='alert alert-success text-center font-weight-bold'>"
                                                .$_SESSION['item_inserted'].
                                             "</div>";
                                    echo '</div>';
                                }
                                unset($_SESSION['item_inserted']);

                                if(isset($_SESSION['query_error'] )){
                                    echo '<div class="container">
                                            <div class="alert alert-danger text-center font-weight-bold">
                                                '.$_SESSION['query_error'].'
                                            </div>
                                         </div>';
                                }
                                unset($_SESSION['query_error']);
                            ?>
                                 <!-- Add New Ad Form -->
                            <form class="form-horizontal ml-5" action="handlers/newad.handel.php" method="POST" enctype="multipart/form-data">
                                <!-- Start Name field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-4 control-label"> Name </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <input class="form-control " type="text" name="itemName" autocomplete="off" required="required" placeholder="Name Of The Item" />
                                    </div>
                                </div>
                                <!-- End Name field -->

                                <!-- Start Description field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-6 control-label"> Description </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <input class="form-control " type="text" name="itemDesc" autocomplete="off" required="required" placeholder="Item Description" />
                                    </div>
                                </div>
                                <!-- End Description field -->

                                <!-- Start Price field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-4 control-label"> Price </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <input class="form-control " type="text" name="itemPrice" autocomplete="off" required="required" placeholder="Item Price" />
                                    </div>
                                </div>
                                <!-- End Price field -->

                                <!-- Start Country field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-6 control-label"> Country OF Made </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <input class="form-control " type="text" name="itemCountry" autocomplete="off" required="required" placeholder="Country OF Made" />
                                    </div>
                                </div>
                                <!-- End Country field -->

                                <!-- Start Status field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-4 control-label"> Status </label>
                                    <div class="col-sm-10 col-md-12 ">
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
                                    <label class="col-sm-4 control-label"> Member </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <select class="form-control" name="itemMember">
                                            <?= '<option value="'.$value['UserID'].'" selected >'.$value['UserName'].'</option>'; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Member field -->

                                <!-- Start categories field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-4 control-label">  Category </label>
                                    <div class="col-sm-10 col-md-12 ">
                                        <select class="form-control" name="itemCategory">
                                            <option value="0" selected hidden>Choose Category </option>
                                            <?php
                                            $cats = getcatsDate("*","categories");
                                            foreach ($cats as $cat){
                                                echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
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
                                        <input class="form-control " type="text" name="itemTags"  placeholder="Add Tag and Separate with Comma (,)" />
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
                                <div class="form-group form-group-lg pull-right">
                                    <div class="col-sm-12 col-md-12">
                                        <input class="btn btn-primary btn-lg" type="submit" value="Add Item"   />
                                    </div>
                                </div>
                                <!-- End submit field -->

                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php include $tpl.'footer.inc.php'; ?>



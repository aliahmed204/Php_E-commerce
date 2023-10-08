<!-- login page -->
<?php
// session because we Depend on username that register in session var
// this page not publc for all users it for only one user
session_start();
$theTitle = 'Profile';
include 'init.php';

    // NO user logged in SO Will Go To Main page of Store
if(!isset($_SESSION['UserName'])){
    header("Location:login.php");
    die();
}
    // that's mean user logged in and will show this page for particular uesr
?>
<h1 class="text-center"> Profile </h1>
<?php

    // to get user data From Database
$userID = $_GET['userID']&&is_numeric($_GET['userID'])?intval( $_GET['userID'] ) : 0 ;
$check = checkExist("UserID","users",$userID);
if($check != 1):
    echo '<div class="container">';
    $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This user </div>";
    redirect($errorMsg,'',2);
    echo '</div>';
endif;

     $query = "SELECT `Email`, `FullName`, `GroupID`, `Date`, `avatar`
                    FROM `users` WHERE UserID= $userID AND GroupID = 0 AND RegStatus > 0 ";
     $stmt = $pdo->prepare($query);
     $stmt->execute();
     $value = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<div class="information block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                User info
            </div>
            <div class="card-body">
                <?php
                echo '<ul class="list-unstyled">';
                if(!empty($value['avatar'])){ // if user has img in Db get It
                    echo '<a href="profile.php"> <img src="admin/uploads/avatar/'.$row['avatar'].'" alt="img" class="img-circle" width="150px" height="150px" /></a>';
                }else{      // if user Dose Not have img in Db Show Defult Img
                    echo '<a href="profile.php"> <img src="admin/uploads/avatar/179-Blog-Post-How-to-break-down-big-goals.jpg" alt="img" class="img-circle" width="150px" height="150px" /></a>';
                }
                      echo'<li>
                             <i class="fa fa-envelope-o fa-fw"></i>
                             <span> Email  </span> : '.$value['Email'].'
                         </li>
                         <li>
                             <i class="fa fa-user fa-fw"></i>
                             <span> FullName </span> : '.$value['FullName'].'
                         </li>
                         <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Register Date </span> : '.$value['Date'].'
                         </li>';

                ///////////////////////////////////////////////////
                ///        GET favourite Categories of User     ///
                ///////////////////////////////////////////////////
                            $favCat = favCat($userID);
                 echo '<li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>favourite Categories </span> ';
                                if (!empty($favCat)){
                                    foreach( $favCat as $v){

                                        echo ' - '.$v['favCat'];
                                    }
                                }else{
                                    echo ' : NO FAV Cat';
                                }
                 echo   '</li>';
            echo ' </ul>';
                ?>
            </div>
        </div>
    </div>
</div>
<div id ="my-ads" class="my-ads block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                Show ads
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                        // will show only Approved Items
                    $ads = getItems('member_ID',$userID);
                    if (empty($ads)){
                        echo '<div class="container">
                                 <div class="alert text-center font-weight-bold">
                                    There Is No Items To Show 
                                 </div>
                                 <a href="newad.php" class="pull-right">Create New Ad</a>
                              </div>';
                    }else{
                        foreach ($ads as $ad){
                            echo '<div class="col-sm-6 col-md-4">
                                    <div class="thumbnail item-box">
                                     <span class="price-tag">'.$ad['item_price'].'</span>
                                        <img src="img.jpg" alt="" />
                                        <div class="caption">
                                            <h3><a href="items.php?itemID='.$ad['item_id'].'"> '.$ad['item_name'].'</a></h3>
                                            <p>'.$ad['item_description'].'</p>
                                            <div>'.$ad['add_date'].'</div>
                                        </div>
                                    </div>
                                  </div>' ;
                        }
                    } ?>
                 </div>
            </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                latest comments
            </div>
            <div class="card-body">
                <?php
                  $comments = userComment($userID);
                    if (empty($comments)){
                        echo '<div class="container">
                                 <div class="alert text-center font-weight-bold">
                                    There Is No Comments To Show 
                                 </div>
                              </div>';
                    }else{
                        foreach ($comments as $com){
                            echo '<div class="container my-2" style="background-color: #D2E0E6;padding: 12px;">';
                            echo '<h6> item : <a href="items.php?itemID='.$com['item_ID'].'">'.$com['item_name'].'</a></h6>';
                            echo '<h6> comment : '.$com['comment'].'</h6>';
                                echo '<h6> date : '.$com['c_date'].'</h6>';
                            echo '</div>';
                        }
                    }

                ?>
            </div>
        </div>
    </div>
</div>


<?php
include $tpl.'footer.inc.php';
?>
<!--<a href="logout.php">looogout</a>-->


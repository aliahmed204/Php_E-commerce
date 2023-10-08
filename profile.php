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
<h1 class="text-center">My Profile</h1>
<?php
    // Welcom Message For New Member
    if(isset($_SESSION['new'])){
        echo "<div class='container'>
              <div class='alert alert-primary text-center font-weight-bold'>
                 Welcom ".$sessionUser." This IS Your Profile Page               
               </div>
             </div>";
    }
    unset($_SESSION['new']);
    // to get user data From Database
$value = UserDate($sessionUser);
    // if user new and not active yet
if ($value['RegStatus'] == 0){
    echo "<div class='container'>
              <div class='alert alert-primary text-center font-weight-bold'>
                 Hello ".$sessionUser." You will Be Approved soon               
               </div>
             </div>";
}
/////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////
?>
<div class="information block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                my info
            </div>
            <div class="card-body">
                <?php
                echo '<ul class="list-unstyled"> 
                         <li> 
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login name  </span> : '.$value['UserName'].'</span>
                         </li>
                         <li>
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
                        foreach(favCat($sessionUser) as $v){
                            echo '<span>'.$v['favCat'].'</span>';
                        }
                        $favCat = favCat($value['UserID']);
                            echo '<li><i class="fa fa-calendar fa-fw"></i>
                                        <span>favourite Categories </span> ';
                            if (!empty($favCat)){
                                foreach( $favCat as $v){

                                    echo ' - '.$v['favCat'];
                                }
                            }else{
                                echo ' : NO FAV Cat';
                            }
                echo '</ul>';
               echo  '<div class="btn btn-primary mt-3 pull-right">
                        <a href="editmyprofile.php?do=Edit&UserID='.$value['UserID'].'" style="color:#EEE;text-decoration: none">Edit Information</a>
                     </div>
                     ';


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
                    $Uid = $value['UserID'];
                    $ads = getItems('member_ID',$Uid,1);
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
                                    <div class="thumbnail item-box">';?>
                            <?php  if($ad['Approve'] == 0) echo '<span class="approve-status">  not Approved yet</span>'; ?>
                                  <?php echo '<span class="price-tag">'.$ad['item_price'].'</span>
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
               <?php //if (!empty($ads)) {echo '<a href="#" class="pull-right">Show All Ads</a>';}?>
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
                  $comments = userComment($Uid);
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


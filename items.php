<!-- login page -->
<?php
// session because we Depend on username that register in session var
// this page not publc for all users it for only one user
session_start();
$theTitle = 'Show item';
include 'init.php';

// NO user logged in SO Will Go To Main page of Store
if(!isset($_SESSION['UserName'])){
    header("Location:login.php");
    die();
}
// that's mean user logged in and will show this page for particular uesr
?>

<?php
// to get user data From Database
//$value = UserDate($sessionUser);
// if user new and not active yet
//if ($value['RegStatus'] == 0){
//    echo "<div class='container'>
//              <div class='alert alert-primary text-center font-weight-bold'>
//                 Hello ".$sessionUser." You will Be Approved soon
//               </div>
//             </div>";
//}
$itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0 ;
$Query ="SELECT items.* , categories.Name , users.`UserName` , users.UserID FROM items 
            INNER JOIN categories ON categories.ID =items.Cat_ID
            INNER JOIN users ON users.UserID = items.Member_ID
            WHERE item_id=? AND Approve =1
            order by item_id desc ";
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
    $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'>There Is No Such Id Or This Item IS waiting Aprroved</div>";
    redirect($errorMsg,50);
    echo '</div>';
    die();
}
?>
<h1 class="text-center"> <?= $item['item_name']?> </h1>
<div class="my-ads block">
    <div class="container block">
        <div class="card bg-primary">
            <div class="card-header">
                Show ads
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    if (empty($item)){
                        echo '<div class="container">
                                 <div class="alert text-center font-weight-bold">
                                    There Is No Items To Show 
                                 </div>
                                 <a href="newad.php" class="pull-right">Create New Ad</a>
                              </div>';
                    }else{
                        echo ' <div class="col-md-3">';
                        // Show Image
                        if(!empty($item['item_image'])){ // if user has img in Db get It
                            echo '<th><img src="admin/uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                        }else{      // if user Dose Not have img in Db Show Defult Img
                            echo '<th><img src="admin/uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                        }

                        echo   '</div>
                                <div class="col-sm-8 col-md-8">
                                    <h2>'. $item['item_name'].'</h2>
                                    <p>'.$item['item_description'].'</p>
                                    <span>Add Date: '.$item['add_date'].'</span>
                                    <div> Price: '.$item['item_price'].'</div>
                                    <div>Made In: '.$item['country_made'].'</div>
                                   <div>categories Name: <a href="categories.php?pageId='.$item['Cat_ID'].'&pageName='.$item['Name'].'"> '.$item['Name'].'</a> </div>
                                   <div>Add by : <a href="usersprofile.php?userID='.$item['UserID'].'">'.$item['UserName'].'</a></div>';
                                    if (!empty($item['tags'])){
                                   echo '<div>tags : ';
                                            $allTags = explode(',' , $item['tags']);
                                                foreach ($allTags as $tag ){
                                                    $tag = strtolower($tag);
                                                    if ($allTags[count($allTags)-1] == $tag){
                                                        echo "<span class='tag-items'><a href='tags.php?name={$tag}'>". $tag."</a></span>" ;
                                                    }else{
                                                        echo "<span class='tag-items'><a href='tags.php?name={$tag}'>". $tag . "</a></span>" . '- ';
                                                    }
                                                }
                        echo         '</div>'; }
                        if(!empty($sessionUser)){
                            echo  ' <a href="index.php?add_to_cart='.$item['item_id'].'" class="text-primary"><div class="btn btn-primary mt-2 pull-right" > Add To cart</div></a>';
                        }
                        echo   '</div>
                              </div>' ;
                    } ?>
                </div>
                </div>

            <hr>
                </div>
                        <?php
                        try{
                            $query= "SELECT comments.* , users.`UserName`, users.avatar , users.UserID FROM comments 
                                        INNER JOIN users ON users.UserID = comments.user_ID
                                        WHERE item_id=? AND status != 0
                                        ORDER BY `c_id` DESC ";

                            // Prepare Stmt
                            $stmt = $pdo->prepare($query);
                            // Execute Stmt
                            $stmt->execute([$itemID]);
                            $coms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $count = $stmt->rowCount();
                            // success insert Redirect to newad form page and print success message
                            echo '<pre>';
                            //print_r($coms);
                            echo '</pre>';
                            foreach ($coms as $com){
                                if ($com['status'] != 0 && $count != 0):
                                    echo  '<div class="comment_box"> 
                                                <div class="row ml-5">
                                                  <div class="col-sm-2">';
                                                    if(!empty($com['avatar'])){
                                                        echo '<img src="admin/uploads/avatar/'.$com['avatar'].'" class="img-responsive img-thumbnail img-circle center-block my-2" alt="" style="max-width: 120px "/>';
                                                    }else{
                                                        echo '<img src="admin/uploads/avatar/179-Blog-Post-How-to-break-down-big-goals.jpg" class="img-responsive img-thumbnail img-circle center-block my-2" alt="" style="max-width: 120px "/>';
                                                    }
                                               echo  '<a href="usersprofile.php?userID='.$com['UserID'].'">'.$com['UserName']. '</a><br>' .$com['c_date'].
                                                    '</div> 
                                                  <div class="col-sm-9 mt-2 ">
                                                    <p class="lead" style="background-color: #EEE;"><i class="fa fa-comment-o"></i> '
                                                            . $com['comment'] .
                                                     '</p>
                                                </div> 
                                            </div><hr>';
                                endif;
                            }
                        }catch (PDOException $e){
                            // Catch any error in query - and print error message in newad form page
                           echo "Query Error".$e->getMessage() ;

                        }
                        ?>
                    </div>
                </div>
<!--  start Add Comment    -->
<?php  if (isset($_SESSION['UserName'] )):?>
    <?php  if (isset( $_SESSION['comErrors'] )) {
        echo  '<div class="alert alert-danger mt-1">'.$_SESSION['comErrors'].'</div>';
    }
    unset( $_SESSION['comErrors']); ?>
    <div class="row">
        <div class="col-md-10 col-sm-8">
            <div class="add-comment">
                <h3> Add Your Comment </h3>
                <form action="handlers/newcomment.handel.php?" method="POST">
                    <input type="hidden" name="item_ID" value="<?=$item['item_id'] ?>">
                    <input type="hidden" name="user_ID" value="<?=$item['Member_ID'] ?>">
                    <textarea class="form-control comment" name="theComment" required ></textarea>
                    <input type="submit"  value="Add Comment"  class="btn btn-primary mt-2 pull-right" >
                </form>
            </div>
        </div>
    </div>
    <?php
    if(isset($_SESSION['comment_inserted'])){
        echo  '<div class="alert alert-info mt-1">'.$_SESSION['comment_inserted'].'</div>';
    }
    unset( $_SESSION['comment_inserted']);
    ?>
<?php  else:?>
    <div class="alert alert-info text-center" style="color: #004085; "><i class="fa fa-info-circle"></i> <a href="login.php" >login</a> or Register To Add Comment </div>
<?php  endif;?>
<!--  End Add Comment    -->

<?php
include $tpl.'footer.inc.php';
?>




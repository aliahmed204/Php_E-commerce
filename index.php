<!-- login page -->
<?php
session_start();
$theTitle = 'Homepage';
include 'init.php';
if(empty($sessionUser)){
   header("Location:login.php");
   exit();
}
$value = UserDate($sessionUser);
$Uid = $value['UserID'];


    if(isset($_SESSION['order_success'])){
    echo $_SESSION['order_success'] ;
    }unset( $_SESSION['order_success']);
?>
<div class="container mt-4">
    <div class="row">
        <?php

        $items = getAllDate('*','items');
        if (empty($items)){
            echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message font-weight-bold">
                        There Is No Items To Show 
                     </div>
                  </div>';
        }else{
            foreach ($items as $item){
                echo '<div class="col-sm-6 col-md-3 mt-2">
                        <div class="thumbnail item-box">
                        <span class="price-tag"> $'.$item['item_price'].'</span>';

                if(!empty($item['item_image'])){ // if user has img in Db get It
                    echo '<th><img src="admin/uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                }else{      // if user Dose Not have img in Db Show Defult Img
                    echo '<th><img src="admin/uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                }

                echo            '<div class="caption">
                                <h3><a href="items.php?itemID='.$item['item_id'].'"> '.$item['item_name'].'</a></h3>
                                <div class="font-weight-bold">Info : '.$item['item_description'].'</div>';
                if (!empty($item['tags'])){
                    $tags = explode(',' , $item['tags']);
                    echo '<div>tags : ';
                    // to show tags
                    foreach ($tags as $tag){
                        $tag = strtolower($tag);
                        if ($tags[count($tags)-1] == $tag){
                            echo "<span class='tag-items'><a href='tags.php?name={$tag}'>".$tag."</a></span>";
                        }
                        else{
                            echo "<span class='tag-items'><a href='tags.php?name={$tag}'>".$tag."</a></span> | ";
                        }
                    }
                    echo '</div>';
                }
                if(!empty($sessionUser)){
                    echo  ' <a href="index.php?add_to_cart='.$item['item_id'].'" class="text-primary"><div class="btn btn-light" > Add To cart</div></a>';
                }
               echo '<div class="pull-right">'.$item['add_date'].'</div>
                            </div>
                                 </div>
                          </div>' ;
                // add to cart funcion
                if(isset($_GET['add_to_cart'])){
                     addCart($Uid);
                    header("Location:index.php");
                    exit();
                }// then i need to make cart page

            }
        }
        ?>
    </div>
</div>



<?php include $tpl.'footer.inc.php';  ?>
<!--<a href="logout.php">looogout</a>-->


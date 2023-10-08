<?php
session_start();
include 'init.php'; // to get name i sent with - //str_replace('-',' ',$_GET['pageName']);
?>

<div class="container">
    <?php
    if (!isset($_GET['name']) || empty($_GET['name'])) {
        echo '<h1 class="text-center">Show Category</h1>' ;
        echo '<div class="container my-5">
                     <div class="alert alert-info text-center nice-message font-weight-bold">
                        You Did Not specify Category To Show 
                     </div>
                  </div>';
        exit();
    }
    $name = trim($_GET['name']);
    $items = getAllDate('*',"items WHERE tags LIKE '%{$name}%' AND Approve =1",1);  // to get Items in This Cat

    echo '<h1 class="text-center text-secondary">'.$name.' </h1>' ;
    ?>
    <div class="row">
        <?php
        foreach ($items as $item){
            echo '<div class="col-sm-6 col-md-4">
                        <div class="thumbnail item-box">
                        <span class="price-tag"> $'.$item['item_price'].'</span>';
                                // Show Image
                            if(!empty($item['item_image'])){ // if user has img in Db get It
                                echo '<th><img src="admin/uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                            }else{      // if user Dose Not have img in Db Show Defult Img
                                echo '<th><img src="admin/uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="200px" height="250px" /></th>';
                            }
            echo             '<div class="caption">
                                <h3><a href="items.php?itemID='.$item['item_id'].'"> '.$item['item_name'].'</a></h3>
                                <div>Description : '.$item['item_description'].'</div>
                                <div>Add Date: '.$item['add_date'].'</div>';

            // to print tags if item have tags in Db
            if (!empty($item['tags'])){
                $tags = explode(',' , $item['tags']);
                echo '<div>tags : ';
                foreach ($tags as $tag){
                    $tag = strtolower($tag);
                    if ($tags[count($tags)-1] == $tag){
                        echo "<a href='tags.php?name={$tag}'>".$tag."</a>";
                    }
                    else{
                        echo "<a href='tags.php?name={$tag}'>".$tag."</a> | ";
                    }
                }
                echo '</div>';
            }
            if(!empty($sessionUser)){
                echo  ' <a href="index.php?add_to_cart='.$item['item_id'].'" class="text-primary"><div class="btn btn-light" > Add To cart</div></a>';
            }
            echo '</div>
                        </div>
                      </div>' ;

        }
        ?>
    </div>
</div>

<?php include $tpl.'footer.inc.php';?>


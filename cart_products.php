<!-- login page -->
<?php
session_start();
$theTitle = 'Homepage';
include 'init.php';
if(!isset($sessionUser)){
    header("Location:login.php");
    exit();
}
$value = UserDate($sessionUser); // to get user id
$Uid = $value['UserID']; // Current User Id

// to delete one item form cart
    if(isset($_GET['do']) && $_GET['do']== 'delete' && isset($_GET['CartItemId']) && is_numeric($_GET['CartItemId']) ):
            // item id that sent with request
        $deleteId = intval($_GET['CartItemId']);
            // check if this item is exist in cart
        $isExist = checkExist('id','cart_items',$deleteId);
        if ($isExist > 0){
           if(toDelete('cart_items','id',$deleteId)){
               // Show deleted Message
               echo "<div class='container my-5'>
                                 <div class='alert alert-success text-center font-weight-bold'>
                                             item deleted From Cart
                                    </div>
                               </div>";
                   //  Refresh to cart_products Page After 1 second from Display the Message
               header("Refresh:1; url=cart_products.php");
               exit();
           }else{
               echo "<div class='container my-5'>
                                 <div class='alert alert-danger text-center font-weight-bold'>
                                            This Item Not Exist IN Cart
                                    </div>
                               </div>";
               header("Refresh:1; url=cart_products.php");
               exit();
           }
        }
    endif;


// to delete All items form cart removeCart&CartId
if(isset($_GET['do']) && $_GET['do']== 'removeCart' && isset($_GET['CartId']) && is_numeric($_GET['CartId']) ):
        // item id that sent with request
    $deleteCartId = intval($_GET['CartId']);
        // check if this item is exist in cart
    $isExist = checkExist('cart_id','cart_items',$deleteCartId);
    if ($isExist > 0){
        if(toDelete('cart_items','cart_id',$deleteCartId)){
            // Show deleted Message
            echo "<div class='container my-5'>
                                 <div class='alert alert-success text-center font-weight-bold'>
                                            All items are deleted From Cart
                                    </div>
                               </div>";
            //  Refresh to cart_products Page After 1 second from Display the Message
            header("Refresh:1; url=index.php");
            exit();
        }else{
            echo "<div class='container my-5'>
                                 <div class='alert alert-danger text-center font-weight-bold'>
                                            This Item Not Exist IN Cart
                                    </div>
                               </div>";
            header("Refresh:1; url=cart_products.php");
            exit();
        }
    }
endif;

           ////////////////////////////////////
    /// to increase quantity Update totalPrice  //////
            ////////////////////////////////////

    if(isset($_GET['do']) && $_GET['do'] == 'increase' && isset($_GET['CartItemId']) && is_numeric($_GET['CartItemId']) ):
             // item id that sent with request
        $increaseId = intval($_GET['CartItemId']);
            // check exist And get quantity&price to update the quantity
        $query ="SELECT quantity , price FROM cart_items WHERE id=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$increaseId]);
        $isExist = $stmt->rowCount();
        if ($isExist > 0){
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $oldQuantity    = $rows['quantity']; // old Quantity
                $newQuantity    = $oldQuantity + 1 ; // new Quantity = oldQuantity + 1
                $totalNewPrice  = $newQuantity * $rows['price']; // total = newQuantity*itemPrice
                // When Change quantity WE Also Need To Change The Total Price In DB
            $Query = "UPDATE cart_items SET quantity = ? , total =? WHERE cart_items.id =?";
            $stmt =$pdo->prepare($Query);
            $stmt->execute([$newQuantity,$totalNewPrice,$increaseId]);

                echo "<div class='container my-5'>
                           <div class='alert alert-success text-center font-weight-bold'>
                                Change quantity Done it IncreaseId One
                           </div>
                       </div>";
                //  Refresh to cart_products Page After 1 second from Display the Message
                header("Refresh:1; url=cart_products.php");
                exit();

            }else{
                echo "<div class='container my-5'>
                                     <div class='alert alert-danger text-center font-weight-bold'>
                                                This Item Not Exist IN Cart
                                        </div>
                                   </div>";
                header("Refresh:1; url=cart_products.php");
                exit();
            }
        endif;


        ////////////////////////////////////
/// to decrease quantity And Update totalPrice //////
        /// ////////////////////////////////

if(isset($_GET['do']) && $_GET['do'] == 'decrease' && isset($_GET['CartItemId']) && is_numeric($_GET['CartItemId']) ):

    $decreaseId = intval($_GET['CartItemId']);
    $query ="SELECT quantity , price FROM cart_items WHERE id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$decreaseId]);
    $isExist = $stmt->rowCount();

    if ($isExist > 0){
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        $oldQuantity = $rows['quantity'];
        $newQuantity = $oldQuantity - 1 ;
        $totalNewPrice  = $newQuantity * $rows['price'];

        // Cant Decrease Quantity Less Then One
        // Soo if User Try To decrease To zero
        // That will Not be able
        // and if want to delete from cart should use Delete Button
        if($newQuantity == 0){
            echo "<div class='container my-5'>
                           <div class='alert alert-danger text-center font-weight-bold'>
                                This is Last Item Of This Type 
                                If you to Remove This Product From Cart Please Use [ Delete From cart ] Button 
                           </div>
                       </div>";
            //  Refresh to cart_products Page After 1 second from Display the Message
            header("Refresh:3; url=cart_products.php");
            exit();
        }

        // When Change quantity WE Also Need To Change The Total Price In DB
        $Query = "UPDATE cart_items SET quantity = ? , total=? WHERE cart_items.id =?";
        $stmt =$pdo->prepare($Query);
        $stmt->execute([$newQuantity,$totalNewPrice,$decreaseId]);

        echo "<div class='container my-5'>
                           <div class='alert alert-success text-center font-weight-bold'>
                                Change quantity Done it DecreaseId One
                           </div>
                       </div>";
        //  Refresh to cart_products Page After 1 second from Display the Message
        header("Refresh:1; url=cart_products.php");
        exit();

    }else{
        echo "<div class='container my-5'>
                                     <div class='alert alert-danger text-center font-weight-bold'>
                                                This Item Not Exist IN Cart
                                        </div>
                                   </div>";
        header("Refresh:1; url=cart_products.php");
        exit();
    }
endif;

?>
<div class="container mt-4">
    <div class="row">
        <?php

        $cart = ShowCart($Uid);
        if (!empty($cart)): // if user deleted all items from cart than will redirect to index page
        ?>
        <div class="container">
            <h1 class="text-center text-primary"> Cart Items </h1>
            <div class="table-responsive ">
                <table class="table table-bordered text-center ">
                    <tr class="table-active" >
                        <th>items</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>quantity</th>
                        <th>Total</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($cart as $item):
                        echo '<tr>
                        <th>'.$i++.'</th>';
                        if(!empty($item['item_image'])){ // if user has img in Db get It
                            echo '<th><img src="admin/uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }else{      // if user Dose Not have img in Db Show Defult Img
                            echo '<th><img src="admin/uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }
                        echo '<td><a href="items.php?itemID='.$item['product_id'].'"> '.$item['item_name'].'</a></td>
                        <td>'.$item['item_description'].'</td>
                        <td>'.$item['item_price'].'</td>
                            <td>'
                                .$item['quantity'].
                                '<a href="?do=increase&CartItemId='.$item['id'].'"><div class="btn btn-danger small ml-1 pull-right">+</div></a>
                                 <a href="?do=decrease&CartItemId='.$item['id'].'"><div class=" btn btn-danger small pull-right "> - </div></a>
                            </td>
                            
                            <td>'.$item['total'].'</td>
                        <td>
                        <a href="cart_products.php?do=delete&CartItemId='.$item['id'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Remove From Cart</a>
                    </td>
                </tr>';
                    endforeach;

                        // get Total Price of all Items in The cart & Total Quantity
                $Query = "SELECT sum(cart_items.total) AS totalPrice , sum(cart_items.quantity) AS totalQuantity , cart_items.cart_id
                                FROM cart_items 
                                INNER JOIN  carts ON carts.id = cart_items.cart_id
                                WHERE carts.user_id = ? AND status=0";
                    $stmt =$pdo->prepare($Query);
                    $stmt->execute([$Uid]);
                    $total = $stmt->fetch(PDO::FETCH_ASSOC);

                    ?>
                    <tr class="table-active" >
                        <td colspan="2" class="text-center font-weight-bold ">TOTAL Quantity</td>
                        <td colspan="1" class="text-center font-weight-bold "><?=$total['totalQuantity'] ?> Item</td>

                        <td colspan="2" class="text-center font-weight-bold ">TOTAL PRICE</td>
                        <td colspan="2" class="text-center font-weight-bold "><?=$total['totalPrice'] ?>$</td>

                        <td colspan="2" ><a href="cart_products.php?do=removeCart&CartId=<?= $total['cart_id'] ?>" class="btn btn-dark"><i class="fa fa-remove"></i> Remove All Cart </a></td>

                    </tr>

                </table>
            </div>
            <a href="index.php" class="btn btn-primary my-2"><i class="fa fa-plus "></i> Add Items To Cart</a>
            <a href="order.php" class="btn btn-success my-1 pull-right" ><i class="fa fa-plus "></i> Complete Order </a>
        </div>
     <?php

        else:
            echo "<div class='container my-5'>
                           <div class='alert alert-danger text-center font-weight-bold'>
                                There Is No Items IN Cart
                           </div>
                       </div>";
            //  Refresh to cart_products Page After 1 second from Display the Message
            header("Refresh:2; url=index.php");
            exit();

        endif;

        ?>
    </div>
</div>

<?php include $tpl.'footer.inc.php';  ?>


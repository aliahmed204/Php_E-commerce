<?php

session_start();
$theTitle = 'orders Page';
if (isset($_SESSION['logged_in'])){
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do === 'Manage'){

        $showOne = '';
        if (isset($_GET['orderID']) && is_numeric($_GET['orderID'])){
            $orderID =intval($_GET['orderID']);
            $showOne = " WHERE id = {$orderID} ";
        }

        $orderActive = '';
        if (isset($_GET['order']) && $_GET['order'] == 'active'){
            $orderActive = " WHERE status = 1 ";
        }

        $orderShip = '';
        if (isset($_GET['order']) && $_GET['order'] == 'ship'){
            $orderShip = " WHERE status = 2 ";
        }


        $orders = getAllDate( "*", "orders $showOne $orderActive $orderShip ");
        ?>
        <h1 class="text-center text-primary"> Active Orders </h1>
        <?php
            if(isset($_SESSION['delete'])){
                echo "<div class='container'>
                                 <div class='alert alert-success text-center font-weight-bold'>
                                             item deleted
                                    </div>
                               </div>";
            }
            unset($_SESSION['delete']);

            if(isset($_SESSION['active'])){
                echo "<div class='container'>
                                 <div class='alert alert-success text-center font-weight-bold'>
                                             ".$_SESSION['active']."
                                    </div>
                               </div>";
            }
            unset($_SESSION['active']);

        if(empty($orders)){
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
                        <th>Order Code</th>
                        <th>Receiver Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>status</th>
                        <th>Total Price</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($orders as $order):
                        echo '<tr>
                        <th><a href="orders.php?do=detail&orderID='.$order['id'].'">'.$order['id'].'</a></th>
                        <td><a href="?do=Manage&orderID='.$order['id'].'">'.$order['order_code'].'</a></td>
                        <td>'.$order['fullName'].'</td>
                        <td>'.$order['address'].'</td>
                        <td>'.$order['phone'].'</td>
                        <td>'.$order['email'].'</td>
                        <td>'.$order['status'].'</td>
                        <td>'.$order['total'].'$</td>
                        <td>';
                    if($order['status'] == 1){
                     echo '<a href="?do=Active&orderID='.$order['id'].'" class="btn btn-success"><i class="fa fa-edit "></i>Ship</a>
                            <a href="?do=Delete&orderID='.$order['id'].'" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</a>';
                    }elseif ($order['status'] == 2){
                        echo '<div class="alert-info font-weight-bold"> order Sent</div>';
                    }
                echo '</td>
                </tr>';
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
<?php }
    }elseif ($do === 'detail'){

        $orderID = $_GET['orderID']&&is_numeric($_GET['orderID'])?$_GET['orderID'] : 0 ;
        $check = checkExist("id","orders",$orderID);

        if($check != 1):
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This order </div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        endif;


        ?>

<div class="container mt-4">
    <div class="row">
        <?php

        $query="SELECT order_items.quntity ,order_items.total,items.item_id,  orders.status ,
                    items.item_name , items.item_description , items.item_price , items.item_image
                    FROM order_items
                    INNER JOIN items ON order_items.prodeuct_id = items.item_id
                    INNER JOIN orders ON orders.id  = order_items.order_id
                    WHERE order_items.order_id =? ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$orderID]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($cart)): // if user deleted all items from cart than will redirect to index page
        ?>
        <div class="container">
            <h1 class="text-center text-primary"> Order <?php echo $_GET['orderID'] ;?> Detailes </h1>
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
                        <th>Order Status</th>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($cart as $item):
                        echo '<tr>
                        <th>'.$i++.'</th>';
                        if(!empty($item['item_image'])){ // if user has img in Db get It
                            echo '<th><img src="uploads/items/'.$item['item_image'].'" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }else{      // if user Dose Not have img in Db Show Defult Img
                            echo '<th><img src="uploads/items/download.jpeg-1.jpg" alt="img" class="img-circle" width="50px" height="50px" /></th>';
                        }

                        echo '<td><a href="items.php?itemID='.$item['item_id'].'"> '.$item['item_name'].'</a></td>
                        
                        <td>'.$item['item_description'].'</td>
                        <td>'.$item['item_price'].'</td>
                            <td>'.$item['quntity'].'</td>
                            
                            <td>'.$item['total'].'</td>
                            <td>';
                            if ($item['status'] == 1){
                                echo '<div class="alert-info font-weight-bold"> order Active </div>';
                            }elseif ($item['status'] == 2){
                                echo '<div class="alert-success font-weight-bold"> order Sent</div>';
                            }
                          echo  '</td>
                        
                </tr>';
                    endforeach;

                $Query = "SELECT sum(order_items.total) AS totalPrice , sum(order_items.`quntity`) AS totalQuantity ,  order_items.order_id
                            FROM order_items 
                            WHERE order_items.order_id = ? ";
                    $stmt =$pdo->prepare($Query);
                    $stmt->execute([$orderID]);
                    $total = $stmt->fetch(PDO::FETCH_ASSOC);

                    ?>
                    <tr class="table-active" >
                        <td colspan="2" class="text-center font-weight-bold ">TOTAL Quantity</td>
                        <td colspan="1" class="text-center font-weight-bold "><?=$total['totalQuantity'] ?> Item</td>

                        <td colspan="3" class="text-center font-weight-bold ">TOTAL PRICE</td>
                        <td colspan="2" class="text-center font-weight-bold "><?=$total['totalPrice'] ?>$</td>
                    </tr>

                </table>
            </div>
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

 <?php
    }elseif ($do === 'Delete'){
        $orderID = $_GET['orderID']&&is_numeric($_GET['orderID'])?$_GET['orderID'] : 0 ;
        $check = checkExist("id","orders",$orderID);

        if($check != 1):
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Cannot found This order </div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        endif;
        try{
            toDelete('orders' ,'id',$orderID );
            $_SESSION['delete'] = 'Order Deleted deleted';
            header("Location:?do=Manage");
            die();
        }catch (PDOException $e){
            echo '<div class="container">';
            $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> Query Error".$e->getMessage()."</div>";
            redirect($errorMsg,'',2);
            echo '</div>';
        }

    }elseif ($do === 'Active'){
        $orderID = $_GET['orderID']&&is_numeric($_GET['orderID'])?$_GET['orderID'] : 0 ;
        $check = checkExist("id","orders",$orderID);
        if ($check != 1){
            echo '<div class="container">';
                $errorMsg = "<div class='alert alert-danger text-center font-weight-bold'> order not found</div>";
                redirect($errorMsg);
            echo '</div>';
        }
        try{
            $qurey= "UPDATE orders SET status=2 WHERE id=:ZorderId";
            $stmt = $pdo->prepare($qurey);
            $stmt->bindParam(":ZorderId",$orderID);
            $stmt->execute();
            $_SESSION['active'] = 'order are shipped';
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

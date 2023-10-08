<?php
session_start();
$theTitle = 'Dashboard';
// لو فتح وسجل الدخول هيجى هنا لو هو ادمن
if (isset($_SESSION['logged_in'])){
    include'init.php';
  //  echo lang('MESSAGE') .' ' . $_SESSION['logged_in'] . $_SESSION['ID'] ;
    /* Start Dashboard Page */
        // for members and pending members
    $MembersCount = getCount('UserID' , 'users');
    $RegStatusCount = checkExist("RegStatus","users",0);
        // for items
    $ItemsCount = getCount('item_id' , 'items');
    $ApproveCount = checkExist("Approve","items",0);
        // for comments
    $CommentsCount = getCount('c_id' , 'comments');
    $approveCount = checkExist("status","comments",0);

        // For orders
    $ActiveOrdersCount = getCount('id' , 'orders WHERE status= 1');
    $ShipedOrdersCount = getCount('id' , 'orders WHERE status= 2');

    $limitValue = 5;
    $LatestUsers = getLatest("UserName , UserID ,RegStatus","users WHERE GroupID !=1",'UserID',$limitValue);
    $LatestItems = getLatest("item_name , item_id , item_description , Approve","items",'item_id',$limitValue);
    $LatestComments = getLatest("comments.* , users.UserName","comments INNER JOIN users ON users.UserID = comments.user_ID",'c_id',$limitValue);
    $LatestOrders = getLatest("orders.order_code,orders.id,orders.user_id,users.UserName","orders INNER JOIN users ON users.UserID = orders.user_id WHERE status >= 1",'id',$limitValue);


    ///////////////////////////////////

    ///////////////////////////////////

?>
    <div class="container home-stats text-center">
        <h1 class="text-primary"> Dashboard </h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"> <?= $MembersCount?> </a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Users
                        <span> <a href="members.php?do=Manage&member=pending"> <?= $RegStatusCount?> </a> </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tags"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"> <?= $ItemsCount?> </a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span><a href="comments.php"><?= $CommentsCount?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comment"></i>
                    <div class="info">
                        Pending Comments
                        <span><a href="comments.php?comment=approve"><?= $approveCount?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Pending Items
                        <span><a href="items.php?item=approve"> <?= $ApproveCount?> </a></span>
                    </div>
                </div>
            </div>
                    <!--  orders  -->
            <div class="col-md-3">
                <div class="stat st-orders">
                    <i class="fa fa-shopping-cart"></i>
                    <div class="info">
                        Active Orders
                        <span><a href="orders.php?order=active"> <?= $ActiveOrdersCount?> </a></span>
                    </div>
                </div>
            </div>
                    <!--  orders  -->

            <!--  orders  -->
            <div class="col-md-3">
                <div class="stat st-orders">
                    <i class="fa fa-shopping-bag"></i>
                    <div class="info">
                        Shiped Orders
                        <span><a href="orders.php?order=ship"> <?= $ShipedOrdersCount?> </a></span>
                    </div>
                </div>
            </div>
                    <!--  orders  -->
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="card my-2">
                    <div class="card-header">
                        <i class="fa fa-users"></i>
                        Latest <?= $limitValue?> Registered Users
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users ">
                            <?php
                            if(empty($LatestUsers)){
                                echo '<li>There Is No Users To Show | 
                        <a href="members.php?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New Member</a></li>';
                            }else{
                                $i=1;
                                foreach ($LatestUsers as $user):
                                    echo '<li>'.$i++.' - '.'<a href="members.php?do=Manage&ID='.$user['UserID'].'" style="color:blue; hover: text-decoration:dashed">'
                                                    .$user['UserName'].'</a>'.
                                        '<span class="btn btn-success pull-right ">
                                            <i class="fa fa-edit"></i>
                                            <a href="members.php?do=Edit&UserID='.$user['UserID'].'">Edit</a>
                                        </span>';
                                    if ($user['RegStatus'] == 0) {
                                        echo    '<span class="btn btn-info pull-right ">
                                            <i class="fa fa-check-square"></i>
                                            <a href="members.php?do=active&UserID='.$user['UserID'].'">Activate</a>
                                        </span>';
                                    };
                                    echo '</li>';
                                endforeach;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card my-2 ">
                    <div class="card-header">
                        <i class="fa fa-tag"></i>
                        Latest <?= count($LatestItems) ?> Items
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users ">
                            <?php
                            if (empty($LatestItems)) {
                                echo '<li>There Is No items To Show | 
                        <a href="items.php?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New items</a></li>';
                            }else{
                                $i=1;
                                foreach ($LatestItems as $item):
                                    echo '<li>' . $i++ . ' - <a href="items.php?do=Manage&itemID='.$item['item_id'].'" style="color:blue; hover: text-decoration:dashed">  '
                                        . $item['item_name'] .
                                        '</a> : ' . $item['item_description'];
                                    echo '<span class="btn btn-success pull-right ">
                                            <i class="fa fa-edit"></i>
                                            <a href="items.php?do=Edit&itemID=' . $item['item_id'] . '">Edit</a>
                                        </span>';
                                    if ($item['Approve'] == 0) {
                                        echo '<span class="btn btn-info pull-right ">
                                            <i class="fa fa-check-square"></i>
                                            <a href="items.php?do=Approve&itemID=' . $item['item_id'] . '">Activate</a>
                                        </span>';
                                    };
                                    echo '</li>';
                                endforeach;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Comments -->
            <div class="col-sm-6">
                <div class="card my-2 ">
                    <div class="card-header">
                        <i class="fa fa-comments"></i>
                        Latest <?php  if (!empty($LatestComments)) echo count($LatestComments) ?> comments
                        of <?= $limitValue?> Latest comments
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users ">
                            <?php
                            if (empty($LatestComments)) {
                                echo '<li>No comments To Show | 
                        <a href="comments.php?do=Add" class="btn btn-primary my-3">
                        <i class="fa fa-plus "></i> Add New comments</a></li>';
                            }else{
                                $i=1;
                                foreach ($LatestComments as $com):
                                    echo '<li>'.$i++.' - '.'<a href="comments.php?do=Manage&comID='.$com['c_id'].'" style="color:blue; hover: text-decoration:dashed">'
                                            .$com['UserName'].'</a> : '.$com['comment'];
                                    echo  '<span class="btn btn-success pull-right ">
                                            <i class="fa fa-edit"></i>
                                            <a href="comments.php?do=Edit&comID='.$com['c_id'].'">Edit</a>
                                        </span>';
                                    if ($com['status'] == 0) {
                                        echo    '<span class="btn btn-info pull-right ">
                                            <i class="fa fa-check-square"></i>
                                            <a href="comments.php?do=approve&comID='.$com['c_id'].'">Activate</a>
                                        </span>';
                                    };
                                    echo '</li>';
                                endforeach;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Comments -->

            <!-- orders -->
            <div class="col-sm-6">
                <div class="card my-2 ">
                    <div class="card-header">
                        <i class="fa fa-shopping-basket"></i>
                        Latest <?php /* if (!empty($LatestComments)) echo count($LatestComments) */?><!-- comments
                        of --><?/*= $limitValue*/?> orders
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users ">
                            <?php
                            if (empty($LatestOrders)) {
                                echo '<li>No comments To Show | 
                        <i class="fa fa-plus "></i> There is No Orders Recently</li>';
                            }else{
                                $i=1;
                                foreach ($LatestOrders as $order):
                                    // print code and user name
                                    echo '<li>'.$i++.' - '.'<a href="orders.php?do=Manage&orderID='.$order['id'].'" style="color:blue; hover: text-decoration:dashed">'
                                            .$order['order_code'].
                                        '</a> :ordered By 
                                            <a style="color:blue; hover: text-decoration:dashed" href="members.php?do=Manage&ID='.$order['user_id'].'">
                                             '.$order['UserName'].
                                        '</a>';

                                    echo  '<span class="btn btn-success pull-right ">
                                            <a href="orders.php?do=detail&orderID='.$order['id'].'">Order Details</a>
                                                <i class="fa fa-info-circle"></i>
                                         </span>';
                                    echo '</li>';
                                endforeach;
                                echo '<span class="btn btn-primary pull-right mt-2">
                                            <i class="fa fa-shopping-cart"></i>
                                            <a href="orders.php?">Show All Orders</a>
                                        </span>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- orders -->

            <!-- Trending Products -->
            <div class="col-sm-6">
                <div class="card my-2 ">
                    <div class="card-header">
                        <i class="fa fa-product-hunt"></i>
                        Trending Products
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users ">
                            <?php

                            $query = "SELECT items.item_name ,sum(order_items.quntity) AS Trending , items.item_id
                                        FROM order_items 
                                        INNER JOIN items ON items.item_id = order_items.prodeuct_id
                                        GROUP BY prodeuct_id 
                                        ORDER BY Trending  DESC LIMIT 5";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $TrendingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($TrendingOrders)) {
                                echo '<li>No comments To Show | 
                        <i class="fa fa-plus "></i> There is No Orders Recently</li>';
                            }else{
                                $i=1;
                                foreach ($TrendingOrders as $order):
                                    // print code and user name
                                    echo '<li>'.$i++.' - '.'<span style="color: #856404; hover: text-decoration:dashed">'
                                            .$order['item_name'].
                                        '</span> : Bought  
                                             <span class="font-weight-bold">'.$order['Trending']. '</span> Peaces'.
                                        '</a>';

                                    echo  '<span class="btn btn-success pull-right ">
                                            <a href="items.php?do=Manage&itemID='.$order['item_id'].'">Item Details</a>
                                                <i class="fa fa-info-circle"></i>
                                         </span>';
                                    echo '</li>';
                                endforeach;

                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Trending -->



        </div>
    </div>
<?php
    /* Start Dashboard Page */

    // because bootstrap js is here and i want it for dropdown and resposive
    include $tpl.'footer.inc.php';

}else{
    header("Location:../index.php?error=badRequest");
    die();
}


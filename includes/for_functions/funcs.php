<?php

/* * front page functions * */

/*
** Get categories Records function v1.0
** Function to  Get categories Records From Database by defult will show the main categories
** i used it to show only main cat in navbar &&
 */
function getCats($AND = null){
    global $pdo;
    $sql = ($AND == null)? 'AND Parent=0' : $AND ;
    $query ="SELECT * FROM categories WHERE Visibility =0 $sql ORDER BY ID ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $cats;
}

/*
** Get Items Records function v1.0
** Function to  Get AD Items Records From Database
 * and get username who put this comment
 */
function getItems($where,$catID,$Approve=null){
    global $pdo;
    $approved = ($Approve == null) ? " AND Approve = 1" : '';
    $query ="SELECT items.*,users.UserName 
            FROM items
             INNER JOIN users ON users.UserID = items.Member_ID
             WHERE $where=? {$approved} ORDER BY item_id DESC ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$catID]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

/*
** Check RegStatus function v1.0
** Function Check if The User Activated Or Not
** [ RegStatus==0 (Not Activated),  RegStatus==1(Activated) ]
**      ====== This Function Will Used With SESSION ======
** ==That's Mean The user will passed to func always exist in DB==
 */


function checkActivated($username){
    global $pdo;
    $query ="SELECT UserName , RegStatus 
                FROM users 
                WHERE UserName=?
                ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $status = $stmt->rowCount();
    return $status;
}

/*
** Get User Records From DB function v1.0
** Function to Get User Record ,  [ex. username , email , fullName ] From Database
** $Value = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
function UserDate($username){
    global $pdo;
    $query ="SELECT * FROM users WHERE UserName=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $info = $stmt->fetch();
    return $info;
}

/*
** Get User Records From DB function v1.0
** Function to Get User Record ,  [ex. username , email , fullName ] From Database
** $Value = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
function favCat($userid){
    global $pdo;
                // will get only DISTINCT values
    $query ="SELECT DISTINCT  users.UserName , categories.Name AS favCat
                FROM items INNER JOIN users ON users.UserID = items.Member_ID
                INNER JOIN categories ON categories.ID = items.Cat_ID
                WHERE users.UserId=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userid]);
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $cats;
}


 /*
** Get userComments Records From DB function v1.0
** Function to Get userComments From Database
** $username = Name of user to get it's comments
  */

 // using session username
function userComments($username){
    global $pdo;
    $query ="SELECT comments.* , items.item_name
                FROM comments 
                INNER JOIN users ON users.UserID = comments.user_ID
                INNER JOIN items ON items.item_id = comments.item_ID 
                WHERE users.UserName=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $comments;
}
// using user id
function userComment($ID){
    global $pdo;
    $query ="SELECT comments.* , items.item_name
              FROM comments
              INNER JOIN items ON items.item_id = comments.item_ID 
              WHERE comments.user_ID =?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$ID]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $comments;
}













    /*
     ** Redirect Function to Spicific place
     ** Home Redirect Function [This Func Accept Parameters]
     ** $theMsg = Print The Error Message
     ** $url = The Link I Want to Redirect to
     ** $seconds  = Seconds Before Redirect
     */

    function redirect($theMsg,$url = null,$seconds=3){
        if ($url === null){
            $url ="index.php";
            $link = 'Homepage';
        }else{
             if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])):
                 $url  = $_SERVER['HTTP_REFERER'];
                 $link = 'Previous Page';
             else:
                 $url ="index.php";
                 $link = 'Homepage';
             endif;
        }
        echo $theMsg;
        echo '<div class="alert alert-info">You Will Be Redirected To '.$link.' After '.$seconds.' seconds </div>';
        header("refresh:$seconds;url=$url");
    }

    /*
    ** Check item function v1.0
    ** Function to Check item in Database
    ** $selectWhat  = The Attribute To Select  [ex. user , item , category]
    ** $fromWhere   = The Table To Select from [ex. users , items , categories]
    ** $value       = The Value of Select      [ex. ali , phone , devices]
    */

    function checkExist($selectWhat,$fromWhere,$value){
        global $pdo;
        $Query = "SELECT $selectWhat FROM $fromWhere WHERE $selectWhat=?";
        $statement =$pdo->prepare($Query);
        $statement->execute([$value]);
        $rowsCount = $statement->rowCount();
        return $rowsCount;
    }

    // Dashboard function

/*
 **  Count number of Items
 **  function to Count number of Rows , items , users
 **  $item  = The Item To count
 **  $table = The Table to choose From
  */

    function getCount($countWhat , $fromWhere ){ // item - table
        global $pdo;
        $query ="SELECT COUNT($countWhat) FROM $fromWhere";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

/*
** Get Latest Records function v1.0
** Function to  Get Latest Records , items From Database [ex. user , item , Comments]
** $select     = Field To Select          [ex. user , item , category]
** $table      = The Table To Choose from [ex. users , items , categories]
** $order      = The DESC ordering
** $limitValue = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
    function getLatest($select,$table,$order,$limitValue=5){
        global $pdo;
        $query ="SELECT $select FROM $table ORDER BY $order DESC LIMIT $limitValue ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /*
** Get All Records in DB function v1.0
** Function to  Get  All Records ,  [ex. users , items ] From Database
** $select     = Field To Select          [ex. user , item , category]
** $table      = The Table To Choose from [ex. users , items , categories]
** $Value = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
    function getAllDate($select,$table , $Approve = null){
        global $pdo;
    $approved = ($Approve == null) ? " WHERE Approve = 1" : '';
        $query ="SELECT $select FROM $table $approved ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


/*
** Get All Records in DB function v1.0
** Function to  Get  All Records ,  [ex. users , items ] From Database
** $select     = Field To Select          [ex. user , item , category]
** $table      = The Table To Choose from [ex. users , items , categories]
** $Value = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
function getCatsDate($select,$table,$where=null){
    global $pdo;
    $sql = ($where == null) ?'' : $where;
    $query ="SELECT $select FROM $table $sql ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}





/*
** Delete Record in DB function v1.0
** Function to  Delete Record ,  [ex. user , item ] From Database
** $table     = The Table To Delete from [ex. users , items , categories]
** $Where     = The field To Delete from [ex. user_id , item_id , category_id]
** $Value     = The Value of  field Selected to delete
*/
function toDelete($table , $Where , $value){
    global $pdo;
    $query="DELETE FROM $table WHERE $Where=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$value]);
    return 1;
}


// open cart - and if there is cart already opened will add item to it
function addCart($userloged,$quntity=1){
    global $pdo;
    if (isset($_GET['add_to_cart'])):
        $productID = $_GET['add_to_cart'];

        // Check if user alerady opend a cart and dose not finish the order yet
        $query="SELECT * FROM `carts` WHERE user_id=$userloged AND status=0 ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rowsCount = $stmt->rowCount();
        // to open new cart if not user dose not open one before
        if ($rowsCount == 0){
            $query="INSERT INTO `carts` (`user_id`,`total`,`status`) VALUES ('$userloged','0','0') ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        }

            // if there is a cart will add items to it

            // get cart id that is already opened when user used add to cart button
        $query="SELECT id FROM `carts` WHERE user_id =?  AND status=0 ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userloged]);

                // to get cart id
            $getCartId = $stmt->fetch();
            $cart_id = $getCartId['id'];

          //  get item detieles form items tabal where item_id = item_id that i want to add to cart
            $query=" SELECT item_id, item_price FROM `items` WHERE item_id =?  ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$productID]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
                $itemID = $item['item_id'];
                $itemPrice = trim($item['item_price'], '$');
                // to set total in DB
                $totalPrice = $quntity * $itemPrice; // => total

                // if the product is already in cart then will increase the Quantity
                $query1 = " SELECT * FROM `cart_items` WHERE product_id =? AND cart_id=? ";
                $stmt2 = $pdo->prepare($query1);
                $stmt2->execute([$productID,$cart_id]);
                $rowitem = $stmt2->fetch(PDO::FETCH_ASSOC);
                $count2 = $stmt2->rowCount();
                if ($count2 > 0) {
                    $newQuantity = $rowitem['quantity'] + 1;
                    $totalNewPrice = $itemPrice * $newQuantity;
                    try {
                        $query2 = "UPDATE cart_items SET quantity =?, total =?
                                    WHERE product_id =? AND cart_id=?";
                        $stmt3 = $pdo->prepare($query2);
                        $stmt3->execute([$newQuantity,$totalNewPrice,$itemID,$cart_id]);
                    } catch (PDOException $e) {
                        die('error ' . $e->getMessage());
                    }

                        // insert New item details to cart_items
                }else{
                    $query = "INSERT INTO `cart_items` (`cart_id`,`product_id`,`quantity`,`price`,`total`) 
                                VALUES ($cart_id,$itemID,$quntity,$itemPrice,$totalPrice) ";
                    $stmt = $pdo->prepare($query);
                    if ($stmt->execute()) {
                        echo "<script>alert('items added to carts')<script>";
                    }

                }
    endif;
}

// my cart page to show what is in Logged-in user cart
function ShowCart ($Uid){
    global $pdo;
    $query="SELECT cart_items.quantity ,cart_items.total, cart_items.id ,`carts`.`status`,
                items.item_id AS product_id,items.item_name ,items.item_description , items.item_price , items.item_image
              FROM `cart_items`
              INNER JOIN `items` ON cart_items.product_id = items.item_id
              INNER JOIN carts ON `carts`.`status` = 0 AND carts.user_id = ?  
             WHERE `cart_items`.`cart_id` = carts.id ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$Uid]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}

        // make order when user enterd needed information
    function makeOrder($userloged,$fullName,$address,$phone,$email){
        global $pdo;
        if (isset($_GET['complete_order'])):
            $orderCode = '#'.rand(100,10000).'@#$'; // make unique code
            $query = "SELECT * FROM `orders` WHERE user_id = $userloged AND status=0 ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $rowsCount = $stmt->rowCount();
            // to open new cart if not user dose not open one before
            if ($rowsCount == 0) {
                $query = "INSERT INTO `orders` (`order_code`,`user_id`,`fullName`,`address`,`phone`,`email`,`status`) VALUES (?,?,?,?,?,?,'0') ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$orderCode,$userloged,$fullName,$address,$phone,$email]);
                return 1;
            }elseif ($rowsCount > 0){
                return 1;
            }
            endif;
        return 0;
    }

    // put order details in order_items table in DB
function submitOrder($userloged){
    global $pdo;
    if (isset($_GET['complete_order'])):
        // to Get product_id , quantity , cart_items.total to insert in order items
        $query="SELECT cart_items.product_id , cart_items.quantity , cart_items.total , cart_items.cart_id
                FROM cart_items 
                INNER JOIN carts On carts.id = cart_items.cart_id
                And carts.status=0 And carts.user_id = $userloged";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rowsCount = $stmt->rowCount();
        if ($rowsCount == 0){
            echo "<div class='container my-5'>
                           <div class='alert alert-danger text-center font-weight-bold'>
                                There Is No Items IN Cart To Make Order
                           </div>
                       </div>";
            //  Refresh to cart_products Page After 1 second from Display the Message
            header("Refresh:2 ;url=index.php");
            exit();
        }  // fetch  product_id , quantity , cart_items.total
        $rows = $stmt->fetchAll();

            // get cart_id from carts table  where cart still open [status=0] for user how want to make order
        $query="SELECT id FROM carts WHERE  carts.status=0 And carts.user_id =?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userloged]);
        $theID = $stmt->fetch();
        $cart_id = $theID['id'];

           // get oder_id from orders table  where cart still open [status=0] for user how want to make order
        $query="SELECT id FROM orders WHERE user_id = $userloged AND status= 0";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $getOderId = $stmt->fetch(PDO::FETCH_ASSOC);
        $oder_id = $getOderId['id'];

                // after get all information that needed -- will insert values to order_items table
        $query="INSERT INTO `order_items` (order_id,prodeuct_id,quntity,total) VALUES (?,?,?,?) ";
        $stmt = $pdo->prepare($query);

            // insert more then one value throw loop because order has many products that we get from [carts_items] and we want to insert it in [order_items]
        foreach ($rows as $row){
            $product_id  = $row['product_id'];
            $quantity    = $row['quantity'];
            $total_price = $row['total'];
            $stmt->execute( [$oder_id,$product_id , $quantity, $total_price]);
        }
            // if items inserted to order_items
        if ($stmt->rowCount() > 0){
            //  get totalPrice from cart_items for same order
            $query=" SELECT sum(total) AS total FROM cart_items WHERE cart_id = $cart_id ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $thetotale = $row['total'];

                    // Update totalPrice in carts
                $query="UPDATE carts 
                        SET total = ( SELECT sum(total) FROM cart_items WHERE cart_items.cart_id = $cart_id AND status=0)
                        WHERE id =$cart_id AND  status =0";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                    // Update status in carts will be one [this cart is closed and if user want to make new order need to open new one ]
                $query="UPDATE carts
                            SET status =1 
                            WHERE user_id =? AND id=? ";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$userloged,$cart_id]);

                if($stmt->rowCount() > 0){
                        // that mean order has been activated
                    $query="UPDATE orders 
                                SET status =1 , total =? 
                                WHERE id =? AND user_id =? ";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$thetotale,$oder_id,$userloged]);
                }
        }
    endif;
}


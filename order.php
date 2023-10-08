<!-- login page -->
<?php
session_start();
$theTitle = 'Homepage';
include 'init.php';
if(!isset($sessionUser)){
    header("Location:login.php");
    exit();
}
$value = UserDate($sessionUser);
$Uid = $value['UserID']; // Current User Id
$UName = $value['FullName']; // Current User Id

// ====================================================================================== //
if($_SERVER['REQUEST_METHOD'] === "POST" ):
    // catch data sent with form
    foreach ($_POST as $k=>$v){
        $$k = sanitize($v);
    }
    $errors = [];
    if (empty($user) || empty($address) || empty($phone) || empty($Email) ){
        $errors[] = 'You Have To fill ALL Fields';
    }elseif (minChar($user , 8)){
        $errors[] = 'FullName must be Greater then 8 characters';
    }elseif (maxChar($user ,45)){
        $errors[] = 'FullName must be Less then 45 characters';
    }

    if (minChar($address ,15)){
        $errors[] = 'address must be Greater then 15 characters';
    }elseif (maxChar($address ,45)){
        $errors[] = 'address must be Less then 45 characters';
    }

    if (minChar($phone ,13)){
        $errors[] = 'Make sure You Entered Your Country Code for Egypt (02)';
    }elseif (maxChar($phone ,14)){
        $errors[] = 'Phone Number Can Not be Greater then 14 number';
    }

    if(!filter_var($Email,FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Email Must Be valied';
    }

    if(empty($errors)){
        makeOrder($Uid,$user,$address,$phone,$Email); // insert values into orders table
        submitOrder($Uid); // will insert values into order_items - then Update totalPrice in carts And Update status in carts And order will be activated
        $_SESSION['order_success'] = "<div class='container my-5'>
                           <div class='alert alert-success text-center font-weight-bold'>
                                Order Is Activated 
                           </div>
                       </div>";
        //  Refresh to cart_products Page After 1 second from Display the Message
        header("Location:index.php");
        exit();
    }else{
        foreach ($errors as $error){
            echo "<div class='container my-5'>
                           <div class='alert alert-danger text-center font-weight-bold'>
                           ".$error."
                           </div>
                       </div>";
        }
    }
endif;
// ====================================================================================== //


// check if cart not empty
$cart = ShowCart($Uid);
if (!empty($cart)):
?>
<h1 class="text-center text-primary"> make order </h1>
<div class="container">
    <form class="form-horizontal align-content-center" action="?complete_order&userId=<?= $Uid?>" method="POST" >
        <!-- Start Username field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-6 ">
                <input class="form-control " type="text" value="<?= $UName?>" name="user" autocomplete="off" required="required" placeholder="Your Full Name" />
            </div>
        </div>
        <!-- End Username field -->

        <!-- Start Address field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Your Address</label>
            <div class="col-sm-10 col-md-6 ">
                <input class="form-control " type="text" name="address"  autocomplete="new-password" required="required" placeholder="Please Enter The Address You Want To Recevie Order In It  " />
            </div>
        </div>
        <!-- End Address field -->

        <!-- Start Phone field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Phone Number</label>
            <div class="col-sm-10 col-md-6 ">
                <input class="form-control " type="text" name="phone" autocomplete="off" required="required" placeholder="Enter Your Phone Number With Country Code"/>
            </div>
        </div>
        <!-- End Phone field -->

        <!-- Start Email field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-6 ">
                <input class="form-control " type="Email" name="Email" autocomplete="off"  required="required" placeholder="Email must be valied"/>
            </div>
        </div>
        <!-- End Email field -->

        <!-- Start submit field -->
        <div class="form-group form-group-lg ">
            <div class="col-sm-offset-2 col-sm-10 ">
                <input class="btn btn-success btn-lg" type="submit" value="Complete The Order"   />
            </div>
        </div>
        <!-- End submit field -->
    </form>
</div>
<?php
else:    // if cart is empty will redirect to home page
    echo "<div class='container my-5'>
                           <div class='alert alert-danger text-center font-weight-bold'>
                                There Is No Items IN Cart To Make Order
                           </div>
                       </div>";
    //  Refresh to cart_products Page After 1 second from Display the Message
    header("Location:index.php");
    exit();
endif;

?>


<?php include $tpl.'footer.inc.php';  ?>
<!--<a href="logout.php">looogout</a>-->


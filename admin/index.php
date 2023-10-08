<!-- login page -->
<?php
session_start();
$noNavBar='';
include 'init.php';
$theTitle = 'Login';

?>
<?php
// لو فتح وسجل الدخول وقفل البروسور هيرجع المفروض يلاقى نفسه لسه مسجل
if (isset($_SESSION['logged_in'])){
// Redirect to dashboard page if(There Is a session)
    header("Location:dashboard.php");
    die();
}

?>
    <form class="login" action="handlers/login.handel.php" method="POST">
        <h4 class="text-center"> Admin Login</h4>
        <?php
        if (isset($_SESSION['err'])):
                 foreach( $_SESSION['err'] as $error ):
                   echo '<h5 class="alert alert-danger text-center">'. $error. '</h5>';
                 endforeach;
             unset($_SESSION['err']);
        endif;
         ?>
        <input class="form-control " type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
        <input class="btn btn-primary btn-primary btn-block" type="submit" name="submit" value="login" />
    </form>
<?php


      include $tpl.'footer.inc.php';


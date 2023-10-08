<?php
// session to register username in session var
session_start();
// to show login / signup from depend on action
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
// to show title depend on action
$theTitle = 'Log-In';
if($action== 'Signup'){
    $theTitle = 'SignUp';
}
// if user has cookies go to index.php directly == user already logged in
if(isset($_SESSION['UserName'])){
    header("Location:index.php");
    exit();
}
include 'init.php';
?>
<div class="container login-page">
    <h1 class="text-center">
        <span class="login"> <a class="<?php if($action == 'login') echo 'active' ?>" href="?action=login" >Login</a> </span> |
        <span class="signup"><a class="<?php if($action == 'Signup') echo 'active' ?>" href="?action=Signup" > Signup </a></span>
    </h1>
        <?php
        if ($action == 'login'){
            echo '<form class="login" action="handlers/login.handel.php" method="POST">';
           // to print errors
                    if (isset($_SESSION['err'])):
                        foreach ($_SESSION['err'] as $error):
                            echo '<h5 class="alert alert-danger text-center">' . $error . '</h5>';
                        endforeach;
                        unset($_SESSION['err']);
                     endif;
             echo  '<input class="form-control " type="text" name="user" placeholder="Username" autocomplete="off" />
                    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
                    <input class="btn btn-primary btn-primary btn-block" type="submit" name="submit" value="login" />
                 </form>';

            ;}elseif ($action == 'Signup'){
            echo '<form class="signup" action="handlers/signup.handel.php" method="POST">';
            if(isset($_SESSION['oldRecord'] )){
                echo "<div class='container'>
                            <div class='alert alert-success text-center font-weight-bold'>
                                 This UserName Is Used ,Please Try Another One 
                            </div>
                         </div>";
            }
            unset($_SESSION['oldRecord']);

            if(isset($_SESSION['errors'] )){
                foreach ($_SESSION['errors'] as $er){
                    echo "<div class='container'>
                            <div class='alert alert-danger text-center font-weight-bold'>
                                $er
                            </div>
                         </div>";
                }
            }
            unset($_SESSION['errors']);
            echo   '<label class="col-sm-6 control-label">User Name :</label>
                    <input class="form-control" type="text" name="user" placeholder=" Please Enter Username For Login" autocomplete="off" />
                    <label class="col-sm-6 control-label">Password : </label>
                    <input class="form-control" type="password" name="pass" placeholder=" Please Enter Password" autocomplete="new-password" />
                    <label class="col-sm-6 control-label">Repeat Password : </label>
                    <input class="form-control" type="password" name="repeat_pass" placeholder=" Enter Password Again" autocomplete="new-password" />
                    <label class="col-sm-6 control-label">Email :</label>
                    <input class="form-control " type="Email" name="Email"  required="required" placeholder="Please Enter valid Email "/>
                    <label class="col-sm-6 control-label">Full Name :</label>
                    <input class="form-control " type="text" name="fullName" autocomplete="off" required="required" placeholder="Please Enter Your Full Name"/>
                    <input class="btn btn-success btn-primary btn-block" type="submit" name="submit" value="SignUp" />
                 </form>';
        }?>

</div>
<?php include $tpl.'footer.inc.php'?>
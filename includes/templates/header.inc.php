<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php getTitle(); ?></title>
                                <!-- ' layout/css/bootstrap.min.css' -->
    <link rel="stylesheet" href="layout/css/bootstrap.min.css" />
    <link rel="stylesheet" href="layout/css/font-awesome.min.css" />
    <link rel="stylesheet" href="layout/css/backend.css" />
</head>
<body>
<div class="upper-bar">
    <div class="container" >
        <?php
        // will not show login/signUp Links if user already logged in
            if(isset($_SESSION['UserName'])): ?>

    <div class="container" >
        <?php
            $row = UserDate($sessionUser);
        if(!empty($row['avatar'])){ // if user has img in Db get It
            echo '<a href="profile.php"> <img src="admin/uploads/avatar/'.$row['avatar'].'" alt="img" class="img-circle" width="50px" height="50px" /></a>';
        }else{      // if user Dose Not have img in Db Show Defult Img
            echo '<a href="profile.php"> <img src="admin/uploads/avatar/179-Blog-Post-How-to-break-down-big-goals.jpg" alt="img" class="img-circle" width="50px" height="50px" /></a>';
        }
        ?>
        <button  class="dropdown-toggle " type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border: 1px #EEE solid">
            <?= lang('MESSAGE').' '.$sessionUser; ?>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav navbar-links me-auto mb-2 mb-lg-0">
                <li class="nav-item active">
                    <a  class="nav-link active" href="profile.php"> My Profile  </a>
                    <a class="nav-link active" href="newad.php">   New Item</a>
                    <a class="nav-link active" href="profile.php#my-ads">  My Items</a>
                    <a class="nav-link active" href="cart_products.php">  My Cart</a>
                    <?php
                        if ($row['GroupID'] == 1){
                            $_SESSION['logged_in'] = $sessionUser;
                            echo '<a class="nav-link active" href="admin/dashboard.php"> Dashboard </a>';
                        }
                    ?>
                    <a class="nav-link active" href="logout.php">  LogOut</a>
                </li>
            </ul>
        </div>
    </div>

        <?php
                $status = checkActivated($sessionUser);
                if ($status == 0){
                   echo ' Yur Membership Need To Activite By Admin ';
                }

        ?>
        <?php else: ?> ...
            <a href="login.php?action=login">
                <span class="pull-right"> | Login  </span>
            </a>
            <a href="login.php?action=Signup">
                <span class="pull-right">Signup | </span>
            </a>
        <?php endif; ?>
    </div>

</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Home Page</a>
        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav navbar-links " >
                        <?php foreach (getCats() as $cat){ // get main categories only
                            echo'<li class="nav-item active "><a class="nav-link active" aria-current="page" href="categories.php?pageId='.$cat['ID'].'&pageName='.str_replace(' ','-',$cat['Name']).'">'
                                . $cat['Name'] . '</a> </li>';
                        }

                        //  to go to cart page in main navbar
                        if(isset($_SESSION['UserName'])){
                            echo'<li class="nav-item active ">
                                    <a class="nav-link active" aria-current="page" href="cart_products.php"> My Cart </a>
                                  </li>';
                        }

                        ?>
            </ul>
        </div>
    </div>
</nav>



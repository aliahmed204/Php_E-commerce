<?php
/*
 * ============================
 *    === Template Page ===
 * =============================
  */
session_start();
if(isset($_SESSION['logged_in'])) {
    $theTitle = '';
    include 'init.php';
    // split pages
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage'){

    }elseif ($do == 'Add'){

    }elseif ($do == 'Insert'){

    }elseif ($do == 'Edit'){

    }elseif ($do == 'Update'){

    }elseif ($do == 'Delete'){

    }elseif ($do == 'Activate'){

    }

    include $tpl .'footer.inc.php';
}else{
    header("Location:index.php?error=WrongUser");
    die();
}


///////////////////////////////////////////////////////////////////
   // any page must have this
    session_start();
    if(isset($_SESSION['logged_in'])) {
        $theTitle = '';
        include 'init.php';


        include $tpl .'footer.inc.php';
    }else{
        header("Location:index.php?error=WrongUser");
        die();
    }


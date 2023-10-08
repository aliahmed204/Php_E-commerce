<?php
include 'admin/connect.inc.php';

//==== Show Errors ====//
ini_set('display_errors','On');
error_reporting(E_ALL);

//==== SESSION ====//
$sessionUser = '';
if(isset($_SESSION['UserName'])){
    $sessionUser = $_SESSION['UserName'];
}

//==== Routs ====//

    // Template Directory
        $tpl = 'includes/templates/';
    // language Directory
        $lang = 'includes/language/';
    //function Directory
        $fun = 'includes/for_functions/';
    //css Directory
        $css = 'layout/css/';
    //js Directory
        $js = 'layout/js/';
        // i made this insted of inside diretory for title function
        include 'func.php';
//==== include the important files ====//
        include $lang .'en.php'; // always lang at first
// include functions files
        include  $fun .'funcs.php';
// include header only without footer
        include $tpl.'header.inc.php';

// validate input
        include 'handlers/validate.php';




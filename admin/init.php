<?php
include 'connect.inc.php';

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
// include header only without footer
        include $tpl.'header.inc.php';
// include functions files
        include  $fun .'funcs.php';
// validate input
        include 'handlers/validate.php';

      // include_once $func .'functions.php';


        // Include Navbar On All Page Expect The One With $noNavBar Variable
    if (!isset($noNavBar)) include $tpl.'navbar.inc.php';


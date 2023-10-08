<?php
/*
** Title function That print The Page Title if The Page
** Has The Variable PageTitle And Print Default Title
*/

    function getTitle(){
        global $theTitle;
        if (isset($theTitle)){
        echo $theTitle;
        } else{
        // well put it in lang
        echo 'Default';
        }
    }


/*
** Home Redirect Function [This Func Accept Parameters]
** $errorMsg = Print The Error Message
** $seconds  = Seconds Before Redirect

    function redirectHome($errorMsg,$seconds){
        echo '<div class="alert alert-danger">'.$errorMsg.'</div>';
        echo '<div class="alert alert-info">You Will Be Redirected To HomePage After '.$seconds.' seconds </div>';
        header("refresh:$seconds;url=index.php");
    }
    */

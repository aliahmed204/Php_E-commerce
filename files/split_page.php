<?php

/*
 *  Categories => [ Manage | Edit | Update | Add | Insert | Stats ]
 *
*/

// split pages

// do  || action || page

// $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;
$do ='';
    if(isset($_GET['do'])){
        // the page in request ex. Update , Delete
        $do = $_GET['do'];
    }else{
        // main page
        $do = 'Manage';
    }

    // based in $do value
// if the page is the main page $do = 'Manage'

if ($do == 'Manage'){
    // show all links - paths for actions could be done
    echo '<a href="split_page.php?do=Insert">Add new categroy</a>';
    echo '<a href="?do=Update">Update categroy</a>';
    echo '<a href="?do=Delete">Delete categroy</a>';

    // if user want to make some action add or delete or whatever
    // and then well go the page that resosble for this action
}elseif ($do == 'Insert'){
    echo 'welcome to Add new categroy';
}elseif ($do == 'Update'){
    echo 'welcome to Update categroy';
}elseif ($do == 'Delete'){
    echo 'welcome to Delete categroy';
}else{
    echo 'there is no page such this name';
}

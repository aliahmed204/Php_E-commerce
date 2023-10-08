<?php
// language file for words that well translate to arabic
// left to right  and aribac well br rtl


function lang($phrase){
    static $lang = array(

        // Homepage

        'MESSAGE'=> 'Welcome',
        'ADMIN'=> 'Administrator',

        //Dashboard Page
        'Home_Admin'=> 'Ali\'s Store',
        'Categories'=> 'Sections',
        'ITEMS'=> 'Items',
        'MEMBERS'=> 'Members',
        'COMMENTS'=> 'Comments',
        'STATISTICS'=> 'Statistics',
        'LOGS'=> 'Logs',
                //== drop menu ==//
        'Edit_Profile'=> 'Edit Profile',
        'Settings'=> 'Settings',
        'Logout'=> 'Logout',


    );
    return $lang[$phrase];
}



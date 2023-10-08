<?php

function sanitize($var){
    return htmlspecialchars(htmlentities(stripslashes(trim($var))));
}

function isEmpty($str){
    if (empty($str)){
        return true;
    }else{
        return false;
    }
}
function minChar($str ,$lenght){
    if (strlen($str) < $lenght){
        return true;
    }else{
        return false;
    }
}
function maxChar($str ,$lenght){
    if (strlen($str) > $lenght){
        return true;
    }else{
        return false;
    }
}
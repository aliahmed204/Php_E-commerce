<?php

    /*
     ** Redirect Function to Spicific place
     ** Home Redirect Function [This Func Accept Parameters]
     ** $theMsg = Print The Error Message
     ** $url = The Link I Want to Redirect to
     ** $seconds  = Seconds Before Redirect
     */

    function redirect($theMsg,$url = null,$seconds=3){
        if ($url === null){
            $url ="index.php";
            $link = 'Homepage';
        }else{
             if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])):
                 $url  = $_SERVER['HTTP_REFERER'];
                 $link = 'Previous Page';
             else:
                 $url ="index.php";
                 $link = 'Homepage';
             endif;
        }
        echo $theMsg;
        echo '<div class="alert alert-info">You Will Be Redirected To '.$link.' After '.$seconds.' seconds </div>';
        header("refresh:$seconds;url=$url");
    }

    /*
    ** Check item function v1.0
    ** Function to Check item in Database
    ** $selectWhat  = The Attribute To Select  [ex. user , item , category]
    ** $fromWhere   = The Table To Select from [ex. users , items , categories]
    ** $value       = The Value of Select      [ex. ali , phone , devices]
    */

    function checkExist($selectWhat,$fromWhere,$value){
        global $pdo;
        $Query = "SELECT $selectWhat FROM $fromWhere WHERE $selectWhat=?";
        $statement =$pdo->prepare($Query);
        $statement->execute([$value]);
        $rowsCount = $statement->rowCount();
        return $rowsCount;
    }

    // Dashboard function

/*
 **  Count number of Items
 **  function to Count number of Rows , items , users
 **  $item  = The Item To count
 **  $table = The Table to choose From
  */

    function getCount($countWhat , $fromWhere ){ // item - table
        global $pdo;
        $query ="SELECT COUNT($countWhat) FROM $fromWhere";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

/*
** Get Latest Records function v1.0
** Function to  Get Latest Records , items From Database [ex. user , item , Comments]
** $select     = Field To Select          [ex. user , item , category]
** $table      = The Table To Choose from [ex. users , items , categories]
** $order      = The DESC ordering
** $limitValue = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
    function getLatest($select,$table,$order,$limitValue=5){
        global $pdo;
        $query ="SELECT $select FROM $table ORDER BY $order DESC LIMIT $limitValue ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /*
** Get All Records in DB function v1.0
** Function to  Get  All Records ,  [ex. users , items ] From Database
** $select     = Field To Select          [ex. user , item , category]
** $table      = The Table To Choose from [ex. users , items , categories]
** $Value = Number of records to get [ LIMIT = $limitValue(any number of records that i want)]
*/
    function getAllDate($select,$table,$where=null){
        global $pdo;
        $sql = ($where == null) ?'' : $where;
        $query ="SELECT $select FROM $table $sql ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }




/*
** Delete Record in DB function v1.0
** Function to  Delete Record ,  [ex. user , item ] From Database
** $table     = The Table To Delete from [ex. users , items , categories]
** $Where     = The field To Delete from [ex. user_id , item_id , category_id]
** $Value     = The Value of  field Selected to delete
*/
function toDelete($table , $Where , $value){
    global $pdo;
    $query="DELETE FROM $table WHERE $Where=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$value]);
    return 1;
}




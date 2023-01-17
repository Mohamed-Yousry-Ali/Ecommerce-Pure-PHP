<?php

function getAllFrom($field, $table , $where =NULL , $and =NULL ,$orderField , $ordering ="DESC"){
    global $con;
    $getAll = $con->prepare("SELECT $field from $table  $where $and ORDER BY $orderField $ordering");
    $getAll->execute();
    $all = $getAll->fetchALL();
    return $all;
}


/* Function For Get All
function getAllFrom2($tableName ,$order ,$where= NULL){
    global $con;
    $sql =$where == NULL ? '' : $where;
    // if($where == NULL){
    //     $sql ='';
    // }else{
    //     $sql = $where;
    // }
    $getAll = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $order DESC");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all ;
}
*/


// Function For Get Categories
function getCat(){
    global $con;
    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC ");
    $getCat->execute();
    $cats = $getCat->fetchAll();
    return $cats ;
}

// Function For Get Items
function getItem($where , $value , $approve = NULL){
    global $con;
    if($approve == NULL){
        $sql ='AND Approve = 1';
    }else{
        $sql = NULL;
    }
    $getItems = $con->prepare("SELECT * FROM items Where $where = ? $sql ORDER BY Item_ID DESC ");
    $getItems->execute(array($value));
    $items = $getItems->fetchAll();
    return $items ;
}

// function check user is not active...
function checkUserStatus($user){
    global $con;
    $stmtx = $con->prepare("SELECT
    Username , RegStatus
    Password FROM 
        users 
    where Username = ?
    AND RegStatus = 0 ");

    $stmtx->execute(array($user));
    $status=$stmtx->rowCount();
    return $status;
}
    // Function Check Items In Database

    function checkItem($select , $from , $values){
        global $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
        $statement->execute(array($values));
        $count = $statement->rowCount();
        return $count;
    }







    function getTitle() {

        global $pagetitle;

        if(isset($pagetitle)){
            
            echo $pagetitle;
        }
        else{
            echo 'Default';
        }
    }

    /* function redirect v.0

    function redirectHome ($errorMsg , $secounds = 3){
        echo '<div class ="alert alert-danger">' . $errorMsg . '</div>' ;
        echo '<div class ="alert alert-info"> You Will Be Redirected After ' . $secounds. ' Secounds </div>';
        header("refresh: $secounds ; url=index.php");
        exit(); 
    }*/

    //function redirect v.2

    function redirectHome ($Msg ,$url=null, $secounds = 3){
        if($url === null){
            $url ='index.php';
            $link ="Home Page";
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
            }else{
                $url ='index.php';
                $link ="Home Page";
            }
        }
        echo  $Msg ;
        echo '<div class ="alert alert-info"> You Will Be Redirected To ' .$link. ' After ' . $secounds. ' Secounds </div>';
        header("refresh: $secounds ;url=$url");
        exit(); 
    }




    // Function calculte count of Items
    function countItems($item ,$table) {
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();

}

// another function get count
/*
function countItems2($item ,$table ,$condition) {
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table WHERE $item = ? ");
    $stmt2->execute(array($condition));
    $count = $stmt2->fetchColumn();
    return $count;
}
*/

// Fuction get last 5 

function getLatest($select , $table , $order , $limit = 5){
    global $con;
    $stmt3 = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt3->execute();
    $row = $stmt3->fetchAll();
    return $row ;
}
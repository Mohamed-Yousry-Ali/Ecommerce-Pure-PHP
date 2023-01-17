<?php
//Error Reporting
ini_set('display_errors','On');
error_reporting(E_ALL);


//Database Connect 
include 'admin/connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

//Routes
$tpl = 'includes/templats/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
$css ='layout/css/';
$js ='layout/js/';

// importent Files
include $func . 'functions.php';
include $lang.'english.php';
include $tpl.'header.php'; 
?>
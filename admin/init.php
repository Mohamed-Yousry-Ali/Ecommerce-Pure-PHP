<?php

//Database Connect
include 'connect.php';

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



if(!isset($no_navbar)){
   include $tpl.'navbar.php'; 
}

?>
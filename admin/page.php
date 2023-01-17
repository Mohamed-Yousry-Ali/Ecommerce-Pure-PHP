<?php

$do ='';

//$do = isset($_GET['do']) ?   $do =$_GET['do'] :  $do='Manage';

    if(isset($_GET['do'])){
        $do =$_GET['do'];
    }else{
        $do='Manage';
    }

    if($do == 'Manage'){

    }
    elseif($do == 'Add'){

    }
    elseif($do == 'Insert'){

    }
    else{
        echo 'Error';
    }
?>
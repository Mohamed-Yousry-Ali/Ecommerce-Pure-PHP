<?php include 'init.php'; ?>
<div class="container">
    
    <div class="row">
   <?php
    if(isset($_GET['name'])){
        $tag = $_GET['name'];
        echo "<h2 class='text-center'>". $tag ."</h2>";
      
     $tagItems = getAllFrom("*", "items" , "WHERE tags LIKE '%$tag%'" ,"And Approve =1" ,"Item_ID");
    foreach($tagItems as $item) {
      echo '<div class="col-sm6 col-md-3">';
      echo '<div class="thumbnail item-box">';
      echo '<span class="price-tag">'.  $item['Price'] .'</span>';
      echo '<img class="img-responsive" src="Sample_User_Icon.png" alt=""/>';
      echo '<div class="caption">';
      echo '<h3> <a href="items.php?itemid='.$item['Item_ID'].'">'.  $item['Name'] .'</a></h3>';
      echo '<p>'.  $item['Description'] .'</p>';
      echo '<div class="date">'. $item['Add_Date'].'</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    }else{
      echo '<div class="alert alert-danger">Write Tag Name</div>';
    }
   ?>
   </div>
</div>
<?php include $tpl.'footer.php'; ?>
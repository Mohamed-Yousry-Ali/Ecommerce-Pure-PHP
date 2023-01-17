<?php
session_start();
$pagetitle='Items';
if(isset($_SESSION['Username'])){

    include 'init.php';
   
    $do = isset($_GET['do']) ?   $do =$_GET['do'] :  'Manage';

    if($do == 'Manage'){ 
        $stmt =$con->prepare("SELECT
                                items.*, 
                                categories.Name AS cat_name ,
                                users.Username

                                from items
                                INNER JOIN 
                                categories
                                 ON categories.ID = items.Cat_ID
                                INNER JOIN 
                                users ON users.UserID = items.Member_ID
                                ORDER BY Item_ID DESC");

        $stmt->execute();
        $items = $stmt->fetchAll();
        if(! empty($items)){
        ?>

        <h2 class="text-center">Manage Items</h2>
        
        <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>#ID</td>
                <td>Name</td>
                <td>Description</td>
                <td>Price</td>
                <td>Adding Date</td>
                
                <td>Category</td>
                <td>Member</td>
                <td>Control</td>
                </tr>
                <?php 
                    foreach($items as $item){
                        echo '<tr>';
                            echo '<td>' . $item['Item_ID']   . '</td>';
                            echo '<td>' . $item['Name'] . '</td>';
                            echo '<td>' . $item['Description']    . '</td>';
                            echo '<td>' . $item['Price'] . '</td>';
                            echo '<td>' . $item['Add_Date']    . '</td>';
                            echo '<td>' . $item['cat_name']    . '</td>';
                            echo '<td>' . $item['Username']    . '</td>';
                            echo '<td>
                            <a href="items.php?do=Edit&itemid='. $item['Item_ID'] .'" class="btn btn-success"><i class = "fa fa-edit"></i> Edit</a>
                            <a href="items.php?do=Delete&itemid='. $item['Item_ID'] .'" class="btn btn-danger confirm"><i class = "fa fa-close"></i>Delete</a>';
                           
                            if($item['Approve']==0){
                                echo '<a href="items.php?do=Approve&itemid='. $item['Item_ID'] .'"
                                 class="btn btn-info activate">
                                 <i class = "fa fa-check"></i>Approve</a>';
                            }

                            echo  '</td>';
                        echo '</tr>';
                    }
                ?>
            

            </table>
        </div>
        <a href = "items.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Item</a>
        </div>
        <?php }else{
            echo '<div class="container">';
            echo '<div class="nice-message">No Recored To Show</div>'; 
            echo '<a href = "items.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Item</a>';
            echo'</div>';
        } ?>
   <?php 

    }

    elseif($do == 'Add'){  ?>

<h2 class="text-center">Add Item</h2>

<div class="container">

    <form class="form-horizontal" action="?do=Insert" method="POST">

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-5">
                <input
                  type="text"
                  name="name"
                  class="form-control"
                  required
                  placeholder="Name Of Item">
            </div>
        </div>

          <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-5">
                        <input
                         type="text"
                         name="description"
                         class="form-control"
                         required
                         placeholder="Add Description">
                    </div>
                </div>
        
            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Price</label>
                <div class="col-sm-10 col-md-5">
                    <input
                        type="text"
                        name="price"
                        class="form-control"
                        required
                        placeholder="Add Price">
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Country</label>
                <div class="col-sm-10 col-md-5">
                    <input
                        type="text"
                        name="country"
                        class="form-control"
                        required
                        placeholder="Country Of Made">
                </div>
            </div>


            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10 col-md-5">
                  <select  name="status">
                    <option value="0">...</option>
                    <option value="1">New</option>
                    <option value="2">Hight Copy</option>
                    <option value="3">Used</option>
                    <option value="4">Old</option>
                  </select>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Member</label>
                <div class="col-sm-10 col-md-5">
                  <select  name="member">
                    <option value="0">...</option>
                    <?php
                         $allMembers = getAllFrom("*", "users" , "" ,"" ,"UserID");
                         foreach($allMembers as $user){
                             echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
                            
                         }
                    ?>
                  </select>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">categorey</label>
                <div class="col-sm-10 col-md-5">
                  <select  name="categorey">
                    <option value="0">...</option>
                    <?php
                         $allcats = getAllFrom("*", "categories" , "WHERE parent = 0" ,"" ,"ID");
                         foreach($allcats as $cat){
                             echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                             $childcats = getAllFrom("*", "categories" , "WHERE parent = {$cat['ID']}" ,"" ,"ID");
                             foreach($childcats as $child){
                             echo "<option value='".$child['ID']."'>--- ".$child['Name']." From ".$cat['Name']." Section"."</option>";  
                             }                                    
                         }
                    ?>
                  </select>
                </div>
            </div>


            <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Tags</label>
                <div class="col-sm-10 col-md-5">
                    <input
                        type="text"
                        name="tags"
                        class="form-control"
                        placeholder="Tag For Descripe Your Item">
                </div>
            </div>

            <!-- <div class="form-group form-group-lg">
                <label  class="col-sm-2 control-label">Rating</label>
                <div class="col-sm-10 col-md-5">
                  <select class="form-control" name="rating">
                    <option value="0">...</option>
                    <option value="1">*</option>
                    <option value="2">**</option>
                    <option value="3">***</option>
                    <option value="4">****</option>
                    <option value="5">*****</option>
                  </select>
                </div>
            </div> -->

        <div class="form-group form-group-lg">
            
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Item" class="btn btn-primary btn-lg">
            </div>
        </div>
    </form>
</div>

    <?php

      }

    elseif($do == 'Insert'){

        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo "<h2 class='text-center'>Add Item</h2>";
            echo  "<div class='container'>" ;
            // send data from add form
            $name    =$_POST['name'];
            $desc    =$_POST['description'];
            $price   =$_POST['price'];
            $country =$_POST['country'];
            $status  =$_POST['status'];
            $member  =$_POST['member'];
            $cat     =$_POST['categorey'];
            $tags     =$_POST['tags'];
                // Validate Edit Form
                $formerror = array();
                if(empty($name)){
                    $formerror[] = 'Name can\'t be Empty';
                }
                if(empty($desc)){
                    $formerror[] = 'Description can\'t be Empty';
                }
                if(empty($price)){
                    $formerror[] = 'Price can\'t be Empty';
                }
                if(empty($country)){
                    $formerror[] = 'Country can\'t be Empty';
                }
                if($status==0){
                    $formerror[] = 'You Must Choise The Status';
                }
                if($member==0){
                    $formerror[] = 'You Must Choise The Member';
                }
                if($cat==0){
                    $formerror[] = 'You Must Choise The Category';
                }
                foreach($formerror as $error){
                    $Msg ='<div class ="alert alert-danger">'  . $error . '</div>';
                    redirectHome($Msg,'back',5);
                }

                //Check if no Error Make Update
                if(empty($formerror)){
                 
             
                //Insert Info to database 
                    $stmt =$con->prepare("INSERT INTO items(Name , Description , Price , Country_Made , Status , Add_Date , Cat_ID , Member_ID , tags)
                                        VALUES (:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags) ");
                    $stmt->execute(array(
                        'zname'    => $name,
                        'zdesc'    => $desc,
                        'zprice'   => $price,
                        'zcountry' => $country,
                        'zstatus'  => $status,
                        'zcat'     => $cat,
                        'zmember'  => $member,
                        'ztags'    => $tags
                    ));                    
                   $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Add Success </div>';
                   redirectHome($Msg,'back',5);
                       
                }
           }else{
            $Msg= '<div class ="alert alert-danger"> You can\'t Browse This Page Directly </div>';
            redirectHome($Msg,'back',5);
           }
           echo "</tr>";
              
               }
    elseif($do == 'Edit'){ 
         
        $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0;
        $stmt = $con->prepare("SELECT *  FROM  items where Item_ID = ?");
        $stmt->execute(array($itemid));
        $item= $stmt->fetch(); // call data
        $count=$stmt->rowCount();
            if($count > 0){  ?>
                
            <h2 class="text-center">Edit Item</h2>

            <div class="container">

                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>
                    <div class="form-group form-group-lg">
                        <label  class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-5">
                            <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                            placeholder="Name Of Item"
                            value="<?php echo $item['Name'] ?>">
                            
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                                <label  class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-5">
                                    <input
                                    type="text"
                                    name="description"
                                    class="form-control"
                                    required
                                    placeholder="Add Description"
                                    value="<?php echo $item['Description'] ?>">
                                </div>
                            </div>
                    
                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-5">
                                <input
                                    type="text"
                                    name="price"
                                    class="form-control"
                                    required
                                    placeholder="Add Price"
                                    value="<?php echo $item['Price'] ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-5">
                                <input
                                    type="text"
                                    name="country"
                                    class="form-control"
                                    required
                                    placeholder="Country Of Made"
                                    value="<?php echo $item['Country_Made'] ?>">
                            </div>
                        </div>


                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-5">
                            <select  name="status">
                                <option value="1" <?php if($item['Status']==1){echo "selected";} ?>>New</option>
                                <option value="2" <?php if($item['Status']==2){echo "selected";} ?>>Hight Copy</option>
                                <option value="3" <?php if($item['Status']==3){echo "selected";} ?>>Used</option>
                                <option value="4" <?php if($item['Status']==4){echo "selected";} ?>>Old</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2 control-label">Member</label>
                            <div class="col-sm-10 col-md-5">
                            <select  name="member">
                                <?php
                                $allMembers = getAllFrom("*", "users" , "" ,"" ,"UserID");
                                    foreach($allMembers as $user){
                                        echo "<option value='".$user['UserID']."'"; 
                                        if($item['Member_ID']== $user['UserID']){echo 'selected';} echo">".$user['Username']."</option>";
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2 control-label">categorey</label>
                            <div class="col-sm-10 col-md-5">
                            <select  name="categorey">
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats=$stmt2->fetchAll();
                                    foreach($cats as $cat){
                                        echo "<option value='".$cat['ID']."'"; 
                                        if($item['Cat_ID']== $cat['ID']){echo 'selected';} echo">".$cat['Name']."</option>";
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                    <div class="form-group form-group-lg">
                        <label  class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-5">
                            <input
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Tag For Descripe Your Item"
                                value="<?php echo $item['tags'] ?>">
                </div>
            </div>

                    <div class="form-group form-group-lg">
                        
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Edit Item" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>

<?php
                // select all user without Admin
        $stmt =$con->prepare("SELECT 
                              comments.*,
                              users.Username AS Member
                               FROM comments
                              
                               INNER JOIN users
                               ON users.UserID = comments.user_id
                               WHERE item_id = ?");
        $stmt->execute(array($itemid));
        $rows = $stmt->fetchAll();
        if(! empty($rows)){
        ?>

        <h2 class="text-center">Manage [ <?php echo $item['Name'] ?>] Comments</h2>
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>Comment</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo '<tr>';
                            echo '<td>' . $row['comment'] . '</td>';
                            echo '<td>' . $row['Member'] . '</td>';
                            echo '<td>' . $row['comment_date']    . '</td>';
                            echo '<td>
                            <a href="comments.php?do=Edit&comid='. $row['c_id'] .'" class="btn btn-success"><i class = "fa fa-edit"></i> Edit</a>
                            <a href="comments.php?do=Delete&comid='. $row['c_id'] .'" class="btn btn-danger confirm"><i class = "fa fa-close"></i>Delete</a>';

                            if($row['status']==0){
                                echo '<a href="comments.php?do=Approve&comid='. $row['c_id'] .'" 
                                class="btn btn-info activate">
                                <i class = "fa fa-check"></i>Approve</a>';
                            }

                            echo  '</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
         </div>
         <?php }?>
        </div>

   <?php
            }  
            else{
                $Msg = '<div class ="alert alert-danger">there is no id like this</div>';
                redirectHome($Msg,'back',5);
            } 
       
        } 
            elseif ($do == 'Update'){
        
                echo "<h2 class='text-center'>Update Item</h2>";
                echo  "<div class='container'>" ;
                  if($_SERVER['REQUEST_METHOD']=='POST'){
                   // get data from edit form
                   $id      =$_POST['itemid'];
                   $name    =$_POST['name'];
                   $desc    =$_POST['description'];
                   $price   =$_POST['price'];
                   $country =$_POST['country'];
                   $status  =$_POST['status'];
                   $cat     =$_POST['categorey'];
                   $member  =$_POST['member'];
                   $tags    =$_POST['tags'];
       
   
                          // Validate Edit Form
                $formerror = array();
                if(empty($name)){
                    $formerror[] = 'Name can\'t be Empty';
                }
                if(empty($desc)){
                    $formerror[] = 'Description can\'t be Empty';
                }
                if(empty($price)){
                    $formerror[] = 'Price can\'t be Empty';
                }
                if(empty($country)){
                    $formerror[] = 'Country can\'t be Empty';
                }
                if($status==0){
                    $formerror[] = 'You Must Choise The Status';
                }
                if($member==0){
                    $formerror[] = 'You Must Choise The Member';
                }
                if($cat==0){
                    $formerror[] = 'You Must Choise The Category';
                }
                foreach($formerror as $error){
                    $Msg ='<div class ="alert alert-danger">'  . $error . '</div>';
                    redirectHome($Msg,'back',5);
                }

   
                       //Check if no Error Make Update
                       if(empty($formerror)){
   
                       //update database 
                          $stmt = $con->prepare("UPDATE items SET 
                          Name = ? , Description = ? , Price = ? 
                          ,Country_Made = ? ,Status = ? , Cat_ID = ? ,Member_ID = ? , tags = ?
                          WHERE Item_ID = ?");
                          $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$id));
                          $Msg=  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Update </div>';
                          redirectHome($Msg,'back',5);
                       }           
                   
                  }else{
                   $Msg = '<div class ="alert alert-danger">You can\'t Browse This Page Directly</div>';
                   redirectHome($Msg,'back',5);
                  }
                  echo "</div>";
            } 

            elseif ($do == 'Delete'){

                echo "<h2 class='text-center'>Delete Item</h2>";
                echo  "<div class='container'>" ;
                $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  items where Item_ID = ? LIMIT 1 ");

                $stmt->execute(array($itemid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitem ");
                        $stmt->bindParam(":zitem" , $itemid);
                        $stmt->execute();
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Deleted </div>';
                        redirectHome($Msg,'back',5);
                    }else{
                      $Msg =  '<div class ="alert alert-danger"> No User Found </div>';
                      redirectHome($Msg,'back',5);
                    }
                    echo "</div>" ;
          
            }
            elseif ($do == 'Approve'){

                echo "<h2 class='text-center'>Approve Item</h2>";
                echo  "<div class='container'>" ;
                $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  items where Item_ID = ? LIMIT 1 ");

                $stmt->execute(array($itemid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ? ");
                        $stmt->execute(array($itemid));
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Approve </div>';
                        redirectHome($Msg,'back',5);
                    }else{ 
                      $Msg =  '<div class ="alert alert-danger"> No User Found </div>';
                      redirectHome($Msg);
                    }
                    echo "</div>" ;
            }

   include $tpl.'footer.php'; 
} else{
   header('Location: index.php');
   exit();
}
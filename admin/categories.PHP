<?php
session_start();
$pagetitle='Members';
if(isset($_SESSION['Username'])){

    include 'init.php';
   
    $do = isset($_GET['do']) ?   $do =$_GET['do'] :  'Manage';

            if($do == 'Manage'){ 

                $sort='ASC';
                $sort_array= array('ASC','DESC');
                if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
                    $sort = $_GET['sort'];
                }
                $stmt = $con->prepare("SELECT * FROM categories where parent = 0 ORDER BY Ordering $sort");
                $stmt->execute();
                $cats=$stmt->fetchAll(); ?>

                <h2 class="text-center">Manage Categories</h2>
                <div class="container categories">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <i class="fa fa-edit"></i> Manage Categories
                            <div class="option pull-right">
                               <i class="fa fa-sort"></i> Ordering : [ 
                                <a class="<?php if($sort == 'ASC'){echo'active';}?>" href="?sort=ASC">ASC </a>|
                                <a class="<?php if($sort == 'DESC'){echo'active';}?>"href="?sort=DESC">DESC</a>] 
                                View : <i class="fa fa-eye"></i>  [
                                <span class="active" data-view="full">Full</span> |
                                <span data-view="classic">Classic</span>]
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                                foreach($cats as $cat){
                                    echo '<div class="cat">';
                                    echo '<div class="hidden-buttons">';
                                    echo    '<a href ="categories.php?do=Edit&catid=' .$cat["ID"]. '"class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                                    echo    '<a href ="categories.php?do=Delete&catid=' .$cat["ID"].'"class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i> Delete</a>';
                                    echo '</div>';
                                    echo '<h3>' . $cat['Name'] . '</h3>';
                                    echo '<div class="full-view">';
                                   
                                    echo '<p>'  .$cat['Description'] . '</p>';
                                    if($cat['Visibility'] == 1){ echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden </span>';}
                                    if($cat['Allow_Comment'] == 1){ echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disable </span>';}
                                    if($cat['Allow_Ads'] == 1){ echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disable </span>';}
                                    $childCats = getAllFrom("*", "categories" , "WHERE parent = {$cat['ID']}" ,"" ,"ID" , "ASC");
                                    if(!empty($childCats)){
                                    echo '<h4 class="child-head">Sub Categories</h4>'; 
                                    echo '<ul class="list-unstyled child-cats">';
                                    foreach($childCats as $c){
                                        echo '<li class="child-link">
                                        <a href ="categories.php?do=Edit&catid=' .$c["ID"]. '" >'  .$c['Name']. '</a>
                                        <a href ="categories.php?do=Delete&catid=' .$c["ID"].'"class="confirm show-delete">Delete</a>
                                        </li>' ; 
                                    }
                                    echo '</ul>';  
                                }
                                    echo '</div>';
                               
                                    echo  '</div>';
                                   

                                
                                echo '<hr>';
                                }
                            ?>
                        </div>
                    </div>
                    <a class="add-category btn btn-primary" href="categories.PHP?do=Add"><i class="fa fa-plus"></i> Add Category</a>
                </div>

                <?php
            }

            elseif($do == 'Add'){ ?>

        <h2 class="text-center">Add Category</h2>

        <div class="container">

            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="name"  class="form-control" autocomplete="off" required placeholder="Name Of Category">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="description"  class="form-control"  placeholder="Add Description">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="ordering" class="form-control"  placeholder="Number To Arrange Categories">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Parent?</label>
                    <div class="col-sm-10 col-md-5">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php 
                                $allCats = getAllFrom("*", "categories" , "WHERE parent = 0" ,"" ,"ID" , "ASC");
                                echo $allCats;
                                foreach($allCats as $cat){
                                    echo "<option value ='" . $cat['ID'] . "'>".$cat['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-5">
                      <div>
                        <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                        <label for="vis-yes">Yes</label>
                      </div>
                      <div>
                        <input id="vis-no" type="radio" name="visibility" value="1" />
                        <label for="vis-no">No</label>
                      </div>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Allow Comment</label>
                    <div class="col-sm-10 col-md-5">
                      <div>
                        <input id="com-yes" type="radio" name="Commenting" value="0" checked />
                        <label for="com-yes">Yes</label>
                      </div>
                      <div>
                        <input id="com-no" type="radio" name="Commenting" value="1" />
                        <label for="com-no">No</label>
                      </div>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-5">
                      <div>
                        <input id="ads-yes" type="radio" name="ads" value="0" checked />
                        <label for="ads-yes">Yes</label>
                      </div>
                      <div>
                        <input id="ads-no" type="radio" name="ads" value="1" />
                        <label for="ads-no">No</label>
                      </div>
                    </div>
                </div>
                
                <div class="form-group form-group-lg">
                    
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>

            <?php
            }

            elseif($do == 'Insert'){

                if($_SERVER['REQUEST_METHOD']=='POST'){

                    echo "<h2 class='text-center'>Add Category</h2>";

                    echo  "<div class='container'>" ;

                    // send data from add form
                    $name     =$_POST['name'];
                    $desc     = $_POST['description'];
                    $parent   = $_POST['parent'];
                    $order    =$_POST['ordering'];
                    $visible  =$_POST['visibility'];
                    $Comment  =$_POST['Commenting'];
                    $ads      =$_POST['ads'];


                    //Check if Category Exist In DATABASE
                    $check = checkItem("Name","categories",$name);

                    if($check == 1){
                    $Msg= '<div class ="alert alert-danger">Sorry This Category Is Exist </div>';
                    redirectHome($Msg,'back',5);
                    }
                    else{
                    //Insert Info to database 
                    $stmt =$con->prepare("INSERT INTO categories(Name  , Description , parent , Ordering , Visibility , Allow_Comment , Allow_Ads)
                                        VALUES (:zname , :zdesc , :zparent , :zorder , :zvisible , :zcomment , :zads) ");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zparent' => $parent,
                        'zorder'=> $order,
                        'zvisible' => $visible,
                        'zcomment' => $Comment,
                        'zads' => $ads
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

                $catid=isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :  0;
                $stmt = $con->prepare("SELECT *  FROM  categories where ID = ? ");
                $stmt->execute(array($catid));
                $cat= $stmt->fetch(); // call data
                $count=$stmt->rowCount();
                    if($count > 0){  ?>

   <h2 class="text-center">Edit Category</h2>

<div class="container">

    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="catid" value="<?php echo $catid; ?>">
        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-5">
                <input type="text" name="name"  class="form-control" required placeholder="Name Of Category" value="<?php echo $cat['Name']?>">
            </div>
        </div>

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-5">
                <input type="text" name="description"  class="form-control"  placeholder="Add Description" value="<?php echo $cat['Description']?>">
            </div>
        </div>

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-5">
                <input type="text" name="ordering" class="form-control"  placeholder="Number To Arrange Categories" value="<?php echo $cat['Ordering']?>">
            </div>
        </div>

        <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Parent?</label>
                    <div class="col-sm-10 col-md-5">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php 
                                $allCats = getAllFrom("*", "categories" , "WHERE parent = 0" ,"" ,"ID" , "ASC");
                                echo $allCats;
                                foreach($allCats as $c){
                                    echo "<option value ='" . $c['ID'] . "'";
                                    if($cat['parent'] == $c['ID']){
                                        echo 'selected';
                                    }
                                    echo ">".$c['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Visible</label>
            <div class="col-sm-10 col-md-5">
              <div>
                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked';}?> />
                <label for="vis-yes">Yes</label>
              </div>
              <div>
                <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo 'checked';}?> />
                <label for="vis-no">No</label>
              </div>
            </div>
        </div>

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Allow Comment</label>
            <div class="col-sm-10 col-md-5">
              <div>
                <input id="com-yes" type="radio" name="Commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';}?> />
                <label for="com-yes">Yes</label>
              </div>
              <div>
                <input id="com-no" type="radio" name="Commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked';}?>/>
                <label for="com-no">No</label>
              </div>
            </div>
        </div>

        <div class="form-group form-group-lg">
            <label  class="col-sm-2 control-label">Allow Ads</label>
            <div class="col-sm-10 col-md-5">
              <div>
                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';}?> />
                <label for="ads-yes">Yes</label>
              </div>
              <div>
                <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';}?>/>
                <label for="ads-no">No</label>
              </div>
            </div>
        </div>
        
        <div class="form-group form-group-lg">
            
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Update Category" class="btn btn-primary btn-lg">
            </div>
        </div>
    </form>
</div>
        
           <?php
                    }  
                    else{
                        $Msg = '<div class ="alert alert-danger">there is no id like this</div>';
                        redirectHome($Msg,'back',5);
                    } 
            }

            elseif ($do == 'Update'){

                echo "<h2 class='text-center'>Update Category</h2>";
                echo  "<div class='container'>" ;
                  if($_SERVER['REQUEST_METHOD']=='POST'){
                   // get data from edit form
                   $id      =$_POST['catid'];
                   $name    =$_POST['name'];
                   $desc    =$_POST['description'];
                   $order   =$_POST['ordering'];
                   $parent   =$_POST['parent'];
                   $visible   =$_POST['visibility'];
                   $comment   =$_POST['Commenting'];
                   $ads   =$_POST['ads'];
                          $stmt = $con->prepare("UPDATE categories SET Name = ? , Description = ? , Ordering = ? , parent = ? , Visibility = ? , Allow_Comment = ? , Allow_Ads = ? WHERE ID = ?");
                          $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));
                          $Msg=  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Update </div>';
                          redirectHome($Msg,'back',5);
                               
                   
                  }else{
                   $Msg = '<div class ="alert alert-danger">You can\'t Browse This Page Directly</div>';
                   redirectHome($Msg,'back',5);
                  }
                  echo "</div>";
            } 

            elseif ($do == 'Delete'){

                echo "<h2 class='text-center'>Delete Category</h2>";
                echo  "<div class='container'>" ;
                $catid=isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  categories where ID = ? LIMIT 1 ");

                $stmt->execute(array($catid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid ");
                        $stmt->bindParam(":zid" , $catid);
                        $stmt->execute();
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Deleted </div>';
                        redirectHome($Msg,'back',5);
                    }else{
                      $Msg =  '<div class ="alert alert-danger"> No User Found </div>';
                      redirectHome($Msg,'back',5);
                    }
                    echo "</div>" ;
            }

   include $tpl.'footer.php'; 
} else{
   header('Location: index.php');
   exit();
}
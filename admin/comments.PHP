<?php
session_start();
$pagetitle='Comments';
if(isset($_SESSION['Username'])){

    include 'init.php';
   
    $do = isset($_GET['do']) ?   $do =$_GET['do'] :  'Manage';

    if($do == 'Manage'){ 

    
        // select all user without Admin
        $stmt =$con->prepare("SELECT 
                              comments.*,
                              items.Name AS Item_Name
                              ,users.Username AS Member
                               FROM comments
                               INNER JOIN items
                               ON items.Item_ID = comments.item_id
                               INNER JOIN users
                               ON users.UserID = comments.user_id
                               ORDER BY c_id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(! empty($rows)){
        ?>

        <h2 class="text-center">Manage Comments</h2>
        
        <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                <td>#ID</td>
                <td>Comment</td>
                <td>Item Name</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo '<tr>';
                            echo '<td>' . $row['c_id']   . '</td>';
                            echo '<td>' . $row['comment'] . '</td>';
                            echo '<td>' . $row['Item_Name']    . '</td>';
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
        </div>
        <?php }else{
            echo '<div class="container">';
            echo '<div class="nice-message">No Recored To Show</div>'; 
            echo'</div>';
        } ?>
   <?php }

   

        elseif($do == 'Edit'){ 
        
        $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0;
        $stmt = $con->prepare("SELECT *  FROM  comments where c_id = ? LIMIT 1 ");
        $stmt->execute(array($comid));
        $row= $stmt->fetch(); // call data
        $count=$stmt->rowCount();
            if($count > 0){  ?>
       
       <h2 class="text-center">Edit Comment</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="comid" value="<?php echo $comid ; ?>" />
                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Comment</label>
                    <div class="col-sm-10 col-md-5">
                       <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Edit" class="btn btn-primary btn-lg">
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
               echo "<h2 class='text-center'>Update Comment</h2>";
             echo  "<div class='container'>" ;
               if($_SERVER['REQUEST_METHOD']=='POST'){
                // get data from edit form
                $comid     = $_POST['comid'];
                $comment   =$_POST['comment'];
             
                    //update database 
                       $stmt = $con->prepare("UPDATE comments SET comment = ?  WHERE c_id = ?");
                       $stmt->execute(array($comment,$comid));
                       $Msg=  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Update </div>';
                       redirectHome($Msg,'back',5);
                                               
               }else{
                $Msg = '<div class ="alert alert-danger">You can\'t Browse This Page Directly</div>';
                redirectHome($Msg,'back',5);
               }
               echo "</div>";
            } 

            elseif ($do == 'Delete'){
                echo "<h2 class='text-center'>Delete Comment</h2>";
                echo  "<div class='container'>" ;
                $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  comments where c_id = ? LIMIT 1 ");

                $stmt->execute(array($comid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcomment ");
                        $stmt->bindParam(":zcomment" , $comid);
                        $stmt->execute();
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Deleted </div>';
                        redirectHome($Msg,'back',5);
                    }else{
                      $Msg =  '<div class ="alert alert-danger"> No User Found </div>';
                      redirectHome($Msg);
                    }
                    echo "</div>" ;
            }

            elseif($do == 'Approve'){
                echo "<h2 class='text-center'>Approve Comment</h2>";
                echo  "<div class='container'>" ;
                $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  comments where c_id = ? LIMIT 1 ");

                $stmt->execute(array($comid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ? ");
                        $stmt->execute(array($comid));
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
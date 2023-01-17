<?php
session_start();
$pagetitle='Members';
if(isset($_SESSION['Username'])){

    include 'init.php';
   
    $do = isset($_GET['do']) ?   $do =$_GET['do'] :  'Manage';

    if($do == 'Manage'){ 

        $query ='';
        if(isset($_GET['page']) && $_GET['page']=='Pending'){
            $query = 'AND RegStatus = 0';
        }

        // select all user without Admin
        $stmt =$con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(! empty($rows)){
        ?>

        <h2 class="text-center">Manage Member</h2>
        
        <div class="container">
        <div class="table-responsive">
            <table class="main-table manage-mambers text-center table table-bordered">
                <tr>
                <td>#ID</td>
                <td>Image</td>
                <td>User Name</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registerd Date</td>
                <td>Control</td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo '<tr>';
                            echo '<td>' . $row['UserID']   . '</td>';
                            echo '<td>';
                            if(empty($row['avatar'])){
                                echo '<img src="uploads/Avatars/Sample_User_Icon.png" alt="" />';
                            }else{
                               echo '<img src="uploads/Avatars/' . $row['avatar'] . '" alt="" />';
                            }
                            echo '</td>';
                            echo '<td>' . $row['Username'] . '</td>';
                            echo '<td>' . $row['Email']    . '</td>';
                            echo '<td>' . $row['FullName'] . '</td>';
                            echo '<td>' . $row['Dates']    . '</td>';
                            echo '<td>
                            <a href="members.php?do=Edit&userid='. $row['UserID'] .'" class="btn btn-success"><i class = "fa fa-edit"></i> Edit</a>
                            <a href="members.php?do=Delete&userid='. $row['UserID'] .'" class="btn btn-danger confirm"><i class = "fa fa-close"></i>Delete</a>';

                            if($row['RegStatus']==0){
                                echo '<a href="members.php?do=Activate&userid='. $row['UserID'] .'" 
                                class="btn btn-info activate">
                                <i class = "fa fa-check"></i>Activate</a>';
                            }

                            echo  '</td>';
                        echo '</tr>';
                    }
                ?>
            

            </table>
        </div>
        <a href = "members.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Member</a>
        </div>
        <?php }else{
            echo '<div class="container">';
            echo '<div class="nice-message">No Recored To Show</div>'; 
            echo '<a href = "members.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Member</a>';
            echo'</div>';
        } ?>
   <?php }

    elseif($do == 'Add'){ ?>

        <h2 class="text-center">Add Member</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="username"  class="form-control" autocomplete="off" required placeholder="Add User Name">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="password" name="password"  class="password form-control" required autocomplete="new-password" placeholder="Add Password">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="email" name="email" class="form-control" required placeholder="Add Email">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="full" class="form-control" required placeholder="Add Full Name">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">User Image</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="file" name="avatar" class="form-control" required>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>

   <?php }

    elseif($do == 'Insert'){

              
               if($_SERVER['REQUEST_METHOD']=='POST'){
                echo "<h2 class='text-center'>Add Member</h2>";
                echo  "<div class='container'>" ;

                //upload varible
                $avaterName = $_FILES['avatar']['name'];
                $avaterSize = $_FILES['avatar']['size'];
                $avaterTmp  = $_FILES['avatar']['tmp_name'];
                $avaterType = $_FILES['avatar']['type'];

                $avaterAllowedExtension = array("jpeg", "jpg", "png", "gif");
                $avaterExtension = explode('.', $avaterName);
                $dump = strtolower(end($avaterExtension));

               

                // send data from add form
                $user     = $_POST['username'];
                $pass     = $_POST['password'];
                $email    = $_POST['email'];
                $name     = $_POST['full'];
                $hashpass = sha1($_POST['password']);
               
                    // Validate Edit Form
                    $formerror = array();
                    if( strlen($user) < 3 ){
                        $formerror[] = 'User Name can\'t be Less Then <strong> 4 Charcter </strong>';
                    }
                    if(empty($user)){
                        $formerror[] = 'User Name can\'t be Empty';
                    }
                    if(empty($pass)){
                        $formerror[] = 'Password can\'t be Empty';
                    }
                    if(empty($email)){
                        $formerror[] = 'Email can\'t be Empty';
                    }
                    if(empty($name)){
                        $formerror[] = 'Full Name can\'t be Empty';
                    }

                    if(! empty($avaterName) && ! in_array($dump , $avaterAllowedExtension)){
                        $formerror[] = 'This Extension is Not <strong>Allowed</strong>';
                    }

                    if(empty($avaterName)){
                        $formerror[] = 'Upload <strong>Image</strong>';
                    }

                    if($avaterSize > 4194304){
                        $formerror[] = 'Image can\'t Be Larger Than <strong>4MB</strong>';
                    }

                    foreach($formerror as $error){
                        $Msg ='<div class ="alert alert-danger">'  . $error . '</div>';
                        redirectHome($Msg,'back',5);
                    }

                    //Check if no Error Make Update
                    if(empty($formerror)){
                     
                        $avatar = rand(0,1000000) . "_" . $avaterName;
                        move_uploaded_file($avaterTmp , "uploads\Avatars\\" . $avatar);
                     //Check if User Exist In DATABASE
                     $check = checkItem("Username","users",$user);
                     if($check == 1){
                        $Msg= '<div class ="alert alert-danger">Sorry This User Is Exist </div>';
                        redirectHome($Msg,'back',5);
                     }else{
                    //Insert Info to database 
                        $stmt =$con->prepare("INSERT INTO users(Username , Password , Email , FullName , RegStatus , Dates , avatar)
                                            VALUES (:zuser , :zpass , :zemail , :zname , 1 , now() , :zavatar) ");
                        $stmt->execute(array(
                            'zuser'   => $user,
                            'zpass'   => $hashpass,
                            'zemail'  => $email,
                            'zname'   => $name,
                            'zavatar' => $avatar
                        ));                    
                       $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Add Success </div>';
                       redirectHome($Msg,'back',5);
                    }           
                    }
               }else{
                $Msg= '<div class ="alert alert-danger"> You can\'t Browse This Page Directly </div>';
                redirectHome($Msg);
               }
               echo "</tr>";}

        elseif($do == 'Edit'){ 
        
        $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0;
        $stmt = $con->prepare("SELECT *  FROM  users where UserID = ? LIMIT 1 ");
        $stmt->execute(array($userid));
        $row= $stmt->fetch(); // call data
        $count=$stmt->rowCount();
            if($count > 0){  ?>
       
       <h2 class="text-center">Edit Member</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ; ?>" />
                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" autocomplete="off" required>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="hidden" name="oldpassword"  value="<?php echo $row['Password'] ?>">
                        <input type="password" name="newpassword"  class="form-control" autocomplete="new-password" placeholder="Edit Password">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label  class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-5">
                        <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required>
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
               echo "<h2 class='text-center'>Update Member</h2>";
             echo  "<div class='container'>" ;
               if($_SERVER['REQUEST_METHOD']=='POST'){
                // get data from edit form
                $id     = $_POST['userid'];
                $user   =$_POST['username'];
                $email  =$_POST['email'];
                $name   =$_POST['full'];

                // send password to database Trick

                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                    /* $pass='';
                    if(empty($_POST['newpassword'])){
                        $pass = $_POST['oldpassword'];
                    }else{
                        $pass = sha1($_POST['newpassword']);
                    }*/

                    // Validate Edit Form
                    $formerror = array();
                    if(empty($user)){
                        $formerror[] = '<div class ="alert alert-danger">  User Name can\'t be Empty </div>';
                    }
                    if(empty($email)){
                        $formerror[] = '<div class ="alert alert-danger">  Email can\'t be Empty </div>';
                    }
                    if(empty($name)){
                        $formerror[] = '<div class ="alert alert-danger">  Full Name can\'t be Empty </div>';
                    }
                    foreach($formerror as $error){
                        $Msg = $error ;
                        redirectHome($Msg,'back',5);
                    }

                    //Check if no Error Make Update
                    if(empty($formerror)){

                        $stmt2=$con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                        $stmt2->execute(array($user,$id));
                        $count = $stmt2->rowCount();
                        if($count == 1){ 
                            echo 'Sorry This User Is Exist';
                            redirectHome($Msg,'back',5);
                        }else{
                    //update database 
                       $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
                       $stmt->execute(array($user,$email,$name,$pass,$id));
                       $Msg=  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Update </div>';
                       redirectHome($Msg,'back',5);
                    }  
                }
                
               }else{
                $Msg = '<div class ="alert alert-danger">You can\'t Browse This Page Directly</div>';
                redirectHome($Msg,'back',5);
               }
               echo "</div>";
            } 

            elseif ($do == 'Delete'){
                echo "<h2 class='text-center'>Delete Member</h2>";
                echo  "<div class='container'>" ;
                $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  users where UserID = ? LIMIT 1 ");

                $stmt->execute(array($userid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser ");
                        $stmt->bindParam(":zuser" , $userid);
                        $stmt->execute();
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Deleted </div>';
                        redirectHome($Msg,'back',5);
                    }else{
                      $Msg =  '<div class ="alert alert-danger"> No User Found </div>';
                      redirectHome($Msg);
                    }
                    echo "</div>" ;
            }

            elseif($do == 'Activate'){
                echo "<h2 class='text-center'>Activate Member</h2>";
                echo  "<div class='container'>" ;
                $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0;
             
                $stmt = $con->prepare("SELECT *  FROM  users where UserID = ? LIMIT 1 ");

                $stmt->execute(array($userid));

                $count=$stmt->rowCount();

                    if($count > 0){  
                        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ? ");
                        $stmt->execute(array($userid));
                        $Msg =  '<div class ="alert alert-success">' . $stmt->rowCount() . ' Success Activated </div>';
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
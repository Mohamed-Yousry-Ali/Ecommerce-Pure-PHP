<?php 
session_start();
$pagetitle ='Login';

if(isset($_SESSION['user'])){
    header('Location: index.php');
}

include 'init.php';

 // check if the user come from post method
 if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['login'])){
        $user =$_POST['username'];
        $pass= $_POST['password'];
        $hashedpass=sha1($pass);

        //Check if the usr exist in database
        $stmt = $con->prepare("SELECT
                                    UserID,
                                    Username , 
                                    Password
                              FROM 
                                    users 
                            where Username = ?
                            AND Password = ? ");

        $stmt->execute(array($user,$hashedpass));
        $get = $stmt->fetch();
        $count=$stmt->rowCount(); 
        
        // if count > 0 this main the user inside database

        if($count > 0){
            $_SESSION['user']=$user; //register username in session
            $_SESSION['uid'] = $get['UserID'];
            header('Location: index.php'); // redirect to dashboard if user found
            exit();
    }
        } else{

           $formErrors = array();
           $username = $_POST['username'] ;
           $password = $_POST['password'] ;
           $password2 = $_POST['password2'] ;
           $email = $_POST['email'] ;

           if(isset($_POST['username'])){
            $filterdUser=filter_var($username ,FILTER_SANITIZE_STRING);
            if(strlen($filterdUser) < 3){
                $formErrors [] = 'User Name Must Be More Than 3 Charcters';
            }
           }

           if(isset($password) && isset($password2)  ){
            if(empty($password)){
                $formErrors [] ='Password Can\'t Be Empty';
            }
           if(sha1($password) !== sha1($password2)){
            $formErrors [] = 'Password Not Match';
           }
           }

           if(isset($email)){
            $filterdEmail=filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
            if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL)!=true){
                $formErrors [] = 'This Email is not Valid';
            }
           }

           if(empty($formErrors)){
                     
            //Check if User Exist In DATABASE
            $check = checkItem("Username","users",$username);
            if($check == 1){
                $formErrors [] = 'This User Is Exists';
            }else{
           //Insert Info to database 
               $stmt =$con->prepare("INSERT INTO users(Username , Password , Email  , RegStatus , Dates)
                                   VALUES (:zuser , :zpass , :zemail  , 0 , now()) ");
               $stmt->execute(array(
                   'zuser' => $username,
                   'zpass' => sha1($password),
                   'zemail'=> $email
               ));                    
              $successMsg =  'Congrats You Are Registerd';
           }           
           }
        }
            }
?>
<div class="container login-page">
    <h2 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h2>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username"/>
        <input class="form-control" type="password" name="password" autocomplete="new-password"  placeholder="Password"/>
        <input class="btn btn-primary btn-block" type="submit" name="login" value="Login"/>
    </form>
    <!-- End Login Form -->

    <!-- Start Signup Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <input pattern=".{3,8}" title="Username Must be Between 3 & 8 Characters" class="form-control" type="text" name="username"   require  autocomplete="off" placeholder="Username"/>
        <input minlength="4" maxlength="10" class="form-control" type="password" name="password"  autocomplete="new-password"  placeholder="Password" require/>
        <input minlength="4" maxlength="10" class="form-control" type="password" name="password2" autocomplete="new-password"  placeholder="Confirm Password" require/>
        <input class="form-control" type="email"    name="email"     placeholder="Email"/>
        <input class="btn btn-success btn-block"    type="submit"      name="signup" value="signup"/>
    </form>

    <div class="the-errors text-center">
     <?php 
        if(! empty($formErrors)){
            foreach($formErrors as $error){
                echo $error . '</br>';
            }
        }
        if(isset($successMsg)){
            echo '<div class="msg success">' . $successMsg .'</div>';
        }
     ?>
    </div>
    <!-- End Signup Form -->
</div>
<?php
include $tpl . 'footer.php';
?>
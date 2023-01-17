<?php
session_start();

$no_navbar='';

$pagetitle ='Login';

if(isset($_SESSION['Username'])){
    header('Location: dashboard.php');
}
include 'init.php';


 // check if the user come from post method
 if($_SERVER['REQUEST_METHOD']=='POST'){
    $username =$_POST['user'];
    $password= $_POST['pass'];
    $hashedpass=sha1($password);

    //Check if the usr exist in database
    $stmt = $con->prepare("SELECT
                                 UserID , Username , 
                           Password FROM 
                                 users 
                           where Username = ?
                           AND Password = ? 
                           AND GroupID = 1 
                           LIMIT 1 ");

    $stmt->execute(array($username,$hashedpass));
    $row= $stmt->fetch(); // call data
    $count=$stmt->rowCount(); 
    
    // if count > 0 this main the user inside database

    if($count > 0){
        echo 'welcome'. $username;
        $_SESSION['Username']=$username; //register username in session
        $_SESSION['ID'] = $row['UserID'];
        header('Location: dashboard.php'); // redirect to dashboard if user found
        exit();
    }
 }
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
<h1 class="text-center">Admin Login</h1>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>
<?php include $tpl.'footer.php'; ?>
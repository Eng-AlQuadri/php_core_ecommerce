<?php 
    session_start();
    $no_navbar = '';
    $page_title = 'login';
    if(isset($_SESSION['UserName'])){
        header('Location: dashboard.php');
    }

    include 'init.php';

    // Check If User Comming From HTTP Post Request

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // Check If The User Exist In Database
        
        $stmt = $con->prepare("Select UserID,UserName,Password from users Where UserName = ? AND Password = ? AND GroupID = 1 LIMIT 1");
        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // If Count > 0 This Means That Database Contains Record For This Username 

        if($count> 0){

            $_SESSION['UserName'] = $username;  // Register Session Name

            $_SESSION['id'] = $row['UserID'];   // Register Session ID

            header('Location: dashboard.php');
            
            exit();
        }
    }
?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>"  method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="UserName" autocomplete = "off"/>
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete = "new-password"/>
        <input class="btn btn-primary btn-block" type="submit" value = "login">
    </form>

<?php include $tpl . "footer.php"; ?>
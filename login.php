<?php 

    ob_start();

    session_start();

    if(isset($_SESSION['user'])) {

        header('Location:index.php');
    }

    include 'init.php'; 

    // Check If User Comming From HTTP Request

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['login'])) {

            

            // Get User Data

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashpass = sha1($pass);

            // Check If User Exists In Database

            $stmt = $con->prepare("SELECT UserID,UserName,`Password` FROM users WHERE UserName = ? AND `Password` = ?");

            $stmt->execute(array($user,$hashpass));

            $data = $stmt->fetch();

            $count = $stmt->rowCount();

            if($count > 0) {

                $_SESSION['user'] = $user;

                $_SESSION['uid'] = $data['UserID'];

                header('Location:index.php');

                exit();

            }

        } elseif(isset($_POST['signup'])) {

            // Validate Inputs

            $formErrors = array();

            if(isset($_POST['username'])) {

                $filterdName = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

                if(strlen($filterdName) < 4) {

                    $formErrors[] = 'Username Must Be Larger Than 4 Characters';
                }
            }

            if(isset($_POST['password']) && isset($_POST['password-again'])) {

                if(empty($_POST['password'])){

                    $formErrors[] = 'Password Can\'t be Empty';
                }
                
                $password1 = sha1($_POST['password']);
                $password2 = sha1($_POST['password-again']);

                if($password1 !== $password2) {

                    $formErrors[] = 'Password Is Not Match!';
                }
            }

            if(isset($_POST['email'])) {

                $filterdEmail = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

                if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL) != true) {

                    $formErrors[] = 'This Email Is Not Valid';
                }
            }

            // Get User Data

            $user = $_POST['username'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $hashpass = sha1($pass);

            // Check If User Exists In Database

            $check = checkItem('UserName','users',$_POST['username']);

            if($check) {

                $formErrors[] = 'This User Is Exists!';

            } else {

                if(empty($formErrors)){

                    $stmt = $con->prepare("INSERT INTO users(UserName,Email,`Password`,RegStatus,`Date`) VALUES (?,?,?,0,now())");

                    $stmt->execute(array($user,$email,$hashpass));

                    // Success Message

                    echo '<p class="text-center">Registerd Successfully</p>';
                }
            }


            
        }
    }
?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span>
        </h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="login" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName" />
            </div>
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" />
            <input type="submit" class="btn btn-primary btn-block" value="Login" name='login'/>
        </form>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="signup">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName" pattern='.{4,}' title='Username Must Be Between 4 Chars' required = 'required'/>
            <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Email" />
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" minlength="4" required/>
            <input class="form-control" type="password" name="password-again" autocomplete="new-password" placeholder="Type Your Password Again" minlength="4" required />
            <input type="submit" class="btn btn-success btn-block" value="SignUp" name='signup'/>
        </form>
    </div>

    <div class="the-errors text-center">
        <?php

            if(!empty($formErrors)){
                
                foreach($formErrors as $error) {

                    echo $error . '<br>';
                }
            }
        ?>
    </div>

<?php 

    include $tpl . 'footer.php'; 

    ob_end_flush();

?>
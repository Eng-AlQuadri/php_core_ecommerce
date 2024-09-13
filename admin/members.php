<?php

/*
    Manage Members Here

    You Can Add | Edit | Delete Members From Here
*/

session_start();

$page_title = "Members";

if(isset($_SESSION['UserName'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage') {

        // Query For Pending Users

        $query = '';

        if(isset($_GET['page']) && $_GET['page'] == 'Pending') {

            $query = 'AND RegStatus = 0';
        }

        // Select All Users

        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");

        $stmt->execute();

        $rows = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead class="text-center">
                        <th class="text-center">#ID</th>
                        <th class="text-center">UserName</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">FullName</th>
                        <th class="text-center">Registered Date</th>
                        <th class="text-center">Control</th>
                    </thead>

                    <?php

                        foreach($rows as $row) {

                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" . $row['UserName'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date']. "</td>";
                            echo "<td>  
                                <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

                            if($row['RegStatus'] == 0) {
                                echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info confirm activate'><i class='fa fa-close'></i> Activate</a>";

                            }
                            echo "</td>";
                            echo "</tr>";

                        }

        ?>

                    
                </table>
            </div>
            <a href="members.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> Add New Member</a>
        </div>

    <?php } elseif($do == 'Add') { ?>

        
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="username"  autocomplete="off" required = 'required'>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="password form-control" name="password" autocomplete="off" required ='required'>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" required = 'required'>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="full">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>

    <?php } elseif ($do == 'Insert') {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Insert Member Page</h1>';

            // Get Variables From The Form

            $user   =    $_POST['username'];
            $pass   =    $_POST['password'];
            $email  =    $_POST['email'];
            $name   =    $_POST['full'];

            $hashedPass = sha1($pass);


            // Validate The Form

            $formErrors = array();

            if (empty($user)) {

                $formErrors[] = '<div class="alert alert-danger">UserName Can\'t Be Empty</div>';

            }
            if (empty($email)) {

                $formErrors[] = '<div class="alert alert-danger">Email Can\'t Be Empty</div>';

            }
            if (empty($name)) {

                $formErrors[] = '<div class="alert alert-danger">FullName Can\'t Be Empty</div>';
            }

            foreach($formErrors as $error) {

                echo $error;
            }

            // If There Is No Error Update Database

            if(empty($formErrors)) {

                // Check If The User Exists In Database

                $check = checkItem("UserName", "users", $user);

                if($check) {

                    $theMsg = '<div class="alert alert-danger container"> Sorry, This User Is Exists</div>';

                    redirectHome($theMsg, 'back');

                }

                // Insert New Member Into Database

                $stmt = $con->prepare("insert into users(UserName,Password,Email,FullName,RegStatus,Date) values(?,?,?,?,1,now())");
                $stmt->execute(array($user,$hashedPass,$email,$name));


                // Print Success Message

                $theMsg = '<div class="alert alert-success container">Added Successfully</div>';

                redirectHome($theMsg, 'BACK');
            }


        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Edit') {

        // Get User Data

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("select * from users where UserID = ? LIMIT 1");

        $stmt->execute(array($userid));

        $row = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count > 0) { ?>

                <h1 class="text-center">Edit Members</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value = "<?php echo $userid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="username" value="<?php echo $row['UserName'] ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                                <input type="password" class="form-control" name="newpassword" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" value="<?php echo $row['Email'] ?>" name="email">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="<?php echo $row['FullName'] ?>" name="full">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="save" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </form>
                </div>
        <?php
        } else {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

    } elseif($do == 'Update') {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Update Page</h1>';
            echo '<div class="container">';

            // Get Variables From The Form

            $id     =    $_POST['userid'];
            $user   =    $_POST['username'];
            $email  =    $_POST['email'];
            $name   =    $_POST['full'];

            // Password Trick

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Validate The Form

            $formErrors = array();

            if (empty($user)) {

                $formErrors[] = '<div class="alert alert-danger">UserName Can\'t Be Empty</div>';

            }
            if (empty($email)) {

                $formErrors[] = '<div class="alert alert-danger">Email Can\'t Be Empty</div>';

            }
            if (empty($name)) {

                $formErrors[] = '<div class="alert alert-danger">FullName Can\'t Be Empty</div>';
            }

            foreach($formErrors as $error) {

                echo $error;
            }

            // If There Is No Error Update Database

            if(empty($formErrors)) {

                $stmt = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ? ");

                $stmt->execute(array($user,$id));

                $count = $stmt->rowCount();

                
                // Check IF The New UserName Exists In Database

                if($count > 0) {

                    $theMsg = '<div class="alert alert-danger container"> This Name Is Exists In The System </div>';

                    redirectHome($theMsg, 'back');

                } else {

                    // Update Database

                    $stmt = $con->prepare("update users set UserName = ? , Email = ? , FullName = ?, Password = ? where UserID = ?");

                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    $count = $stmt->rowCount();

                    // Print Success Message

                    $theMsg = '<div class="alert alert-success container"> Updated Successfully</div>';
                    
                    redirectHome($theMsg, 'back');
                }

                
            }


        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');

        }

        echo "</div>";

    } elseif($do == 'Delete') {

        echo '<h1 class="text-center">Delete Page</h1>';

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Check If User Exists In Database

        $check = checkItem("UserID", "users", $userid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        $stmt = $con->prepare('DELETE FROM users WHERE UserID = ?');

        $stmt->execute(array($userid));

        // Successful Message

        $theMsg = '<div class="alert alert-success container"> Deleted Successfully</div>';

        redirectHome($theMsg, 'back');

    } elseif($do == 'Activate') {

        echo '<h1 class = "text-center"> Activate Page </h1>';

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

        // Check If User Exists In Database

        $check = checkItem("UserID", "users", $userid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        // Activate User

        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

        $stmt->execute(array($userid));

        // Successful Message

        $theMsg = '<div class="alert alert-success container"> Activated Successfully</div>';

        redirectHome($theMsg, 'back');

    } else {

        header('Location:index.php');

        exit();
    }



    include $tpl . 'footer.php';

} else {

    header('Location:index.php');

    exit();
}

<?php 

    ob_start();

    session_start();

    $page_title = "Comments";

    if(!isset($_SESSION['UserName'])) {

        header('Location:index.php');

        exit();
    }

    include 'init.php';


    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage') {

        // Get All Comments

        $stmt = $con->prepare("select comments.*, items.Name,users.UserName FROM comments inner join items on comments.Item_ID = items.Item_ID inner join users on comments.User_ID = users.UserID");

        $stmt->execute();

        $rows = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead class="text-center">
                        <th class="text-center">#ID</th>
                        <th class="text-center">Comment</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Item Name</th>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Control</th>
                    </thead>

                    <?php

                        foreach($rows as $data) {

                            echo "<tr>";
                            echo "<td>" . $data['C_ID'] . "</td>";
                            echo "<td>" . $data['Comment'] . "</td>";
                            echo "<td>" . $data['C_Date'] . "</td>";
                            echo "<td>" . $data['Name']. "</td>";
                            echo "<td>" . $data['UserName']. "</td>";
                            echo "<td>  
                                <a href='comments.php?do=Edit&commentid=" . $data['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='comments.php?do=Delete&commentid=" . $data['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if($data['Status'] ==0) {

                                    echo "<a href='comments.php?do=Approve&commentid=" . $data['C_ID'] . "' class='btn btn-info confirm'><i class='fa fa-check'></i> Approve</a>";
                                }

                            echo "</td>";
                            echo "</tr>";

                        }

                    ?>

                    
                </table>
            </div>
            <a href="comments.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> Add New Comment</a>
        </div>

        <?php

    } elseif($do == 'Add') {

        ?>

        <h1 class="text-center">Add New Comment</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Comment</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="comment"  autocomplete="off" required = 'required'>
                    </div>
                </div>
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10">
                        <select name="member" class="form-control" required = "required">
                            <option value="0">...</option>
                            <?php
                                $stmt= $con->prepare('SELECT * FROM users');
                                $stmt->execute();
                                $rows = $stmt->fetchAll();

                                foreach($rows as $row) {
                                    echo "<option value = '".$row['UserID']."'>".$row['UserName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Item</label>
                    <div class="col-sm-10">
                        <select name="item" class="form-control" required = "required">
                            <option value="0">...</option>
                            <?php
                                $stmt= $con->prepare('SELECT * FROM items');
                                $stmt->execute();
                                $rows = $stmt->fetchAll();

                                foreach($rows as $row) {
                                    echo "<option value = '".$row['Item_ID']."'>".$row['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Comment" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>

        <?php

    } elseif($do == 'Insert') {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Insert Comment Page</h1>';

            // Get Variables From The Form

            $comment = $_POST['comment'];
            $member =  $_POST['member'];
            $item = $_POST['item'];

            

            // Insert Comment In Database

            $stmt = $con->prepare('insert into comments(Comment,Status,C_Date,Item_ID,User_ID) values(?,0,now(),?,?)');

            $stmt->execute(array($comment,$item,$member));

            // Print Successful Message

            $theMsg = '<div class="alert alert-success container">Added Successfully</div>';

            redirectHome($theMsg, 'BACK');

        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Edit') {

        // Get Comment Data

        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;

        $stmt = $con->prepare("select * from comments where C_ID = ? LIMIT 1");

        $stmt->execute(array($commentid));

        $row = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count > 0) { ?>

                <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="commentid" value = "<?php echo $commentid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Comment</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="comment" value="<?php echo $row['Comment'] ?>" autocomplete="off">
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

            echo '<h1 class="text-center">Update Item Page</h1>';

            // Get Data From The Form

            $commentid = $_POST['commentid'];
            $comment = $_POST['comment'];
            
            // Check If Comment Exists In Database

            $check = checkItem('C_ID','comments',$commentid);

            if(!$check) {

                $theMsg = '<div class="alert alert-danger container"> Sorry, This Comment Is Not Exists</div>';

                redirectHome($theMsg, 'back');
            }

            // Update Database

            $stmt = $con->prepare("UPDATE comments SET `Comment`=? WHERE C_ID = ?");

            $stmt->execute(array($comment,$commentid));

            // Print Success Message

            $theMsg = '<div class="alert alert-success container"> Updated Successfully</div>';

            redirectHome($theMsg, 'back');

        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }


    } elseif($do == 'Delete') {

        echo '<h1 class="text-center">Delete Comment Page</h1>';

        $commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;

        // Check If Comment Exists In Database

        $check = checkItem('C_ID','comments',$commentid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The Comment Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        // Update Database

        $stmt = $con->prepare("DELETE FROM comments WHERE C_ID = $commentid");

        $stmt->execute();

        // Print Success Message

        $theMsg = '<div class="alert alert-success container"> Deleted Successfully</div>';

        redirectHome($theMsg, 'back');

    } else {

        header('Location:index.php');

        exit();
    }


    include $tpl . 'footer.php';

    ob_end_flush();
?>
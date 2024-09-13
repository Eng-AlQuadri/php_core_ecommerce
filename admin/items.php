<?php

    ob_start();

    session_start();

    if(!isset($_SESSION['UserName'])) {

        header('Location:index.php');

        exit();
    }
    

    $page_title = 'Items';

    include 'init.php';


    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage') {


        // Select All Items

        $stmt = $con->prepare("SELECT items.*,categories.Name as categ_name,users.UserName
                                from items inner join categories on items.Categ_ID = categories.ID
                                inner join users on users.UserID = items.Member_ID");

        $stmt->execute();

        $rows = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead class="text-center">
                        <th class="text-center">#ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Adding Date</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Member Name</th>
                        <th class="text-center">Control</th>
                    </thead>

                    <?php

                        foreach($rows as $row) {

                            echo "<tr>";
                            echo "<td>" . $row['Item_ID'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Description'] . "</td>";
                            echo "<td>" . $row['Price'] . "</td>";
                            echo "<td>" . $row['Add_Date']. "</td>";
                            echo "<td>" . $row['categ_name']. "</td>";
                            echo "<td>" . $row['UserName']. "</td>";
                            echo "<td>  
                                <a href='items.php?do=Edit&itemid=" . $row['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='items.php?do=Delete&itemid=" . $row['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if($row['Approve'] ==0) {
                                    echo "<a href='items.php?do=Approve&itemid=" . $row['Item_ID'] . "' class='btn btn-info confirm'><i class='fa fa-check'></i> Approve</a>";

                                }

                            echo "</td>";
                            echo "</tr>";

                        }

                    ?>

                    
                </table>
            </div>
            <a href="items.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> Add New Item</a>
        </div>
        <?php
    } elseif($do == 'Add') {

        ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name"  autocomplete="off" required = 'required'>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="price">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="country">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control">
                            <option value="0">...</option>
                            <option value="1">Like New</option>
                            <option value="2">New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
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
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10">
                        <select name="category" class="form-control" required = "required">
                            <option value="0">...</option>
                            <?php
                                $stmt= $con->prepare('SELECT * FROM categories');
                                $stmt->execute();
                                $rows = $stmt->fetchAll();

                                foreach($rows as $row) {
                                    echo "<option value = '".$row['ID']."'>".$row['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>
        <?php

    } elseif($do == 'Insert') {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Insert Item Page</h1>';

            // Get Variables From The Form

            $name = $_POST['name'];
            $description =  $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];

            // Check If Category Exists In Database

            $check = checkItem('Name','items',$name);

            if($check) {

                $theMsg = '<div class="alert alert-danger container"> Sorry, This Item Is Exists</div>';

                redirectHome($theMsg, 'back');
            }

            // Insert Category In Database

            $stmt = $con->prepare('insert into items(Name,Description,Price,Country_Made,Add_Date,Status,Categ_ID,Member_ID) values(?,?,?,?,now(),?,?,?)');

            $stmt->execute(array($name,$description,$price,$country,$status,$category,$member));

            // Print Successful Message

            $theMsg = '<div class="alert alert-success container">Added Successfully</div>';

            redirectHome($theMsg, 'BACK');


            
        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Edit') {

        // Get The ID 

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

        // Get The Data From Database

        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = $itemid");

        $stmt->execute();

        $data = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count > 0) {

            ?>

                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name ="itemid" value ="<?php echo $data['Item_ID'] ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required" >Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name"  autocomplete="off" required = 'required' value = "<?php echo $data['Name'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="description" value = "<?php echo $data['Description'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="price" value = "<?php echo $data['Price'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="country" value = "<?php echo $data['Country_Made'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <select name="status" class="form-control">
                                    <option value="0" <?php if($data['Status'] == 0) echo 'selected'; ?>>...</option>
                                    <option value="1" <?php if($data['Status'] == 1) echo 'selected'; ?>>Like New</option>
                                    <option value="2" <?php if($data['Status'] == 2) echo 'selected'; ?>>New</option>
                                    <option value="3" <?php if($data['Status'] == 3) echo 'selected'; ?>>Used</option>
                                    <option value="4" <?php if($data['Status'] == 4) echo 'selected'; ?>>Very Old</option>
                                </select>
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
                                            echo "<option value = '".$row['UserID']."'";
                                            if($data['Member_ID'] == $row['UserID']) echo 'selected'; 
                                            echo" >".$row['UserName']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category" class="form-control" required = "required">
                                    <option value="0">...</option>
                                    <?php
                                        $stmt= $con->prepare('SELECT * FROM categories');
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();

                                        foreach($rows as $row) {
                                            echo "<option value = '".$row['ID']."'";
                                            if($data['Categ_ID'] == $row['ID']) echo 'selected'; 
                                            echo" >".$row['Name']."</option>";                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Edit Item" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </form>
        <?php

        // Get All Comments

        $stmt = $con->prepare("select comments.*,users.UserName FROM comments inner join users on comments.User_ID = users.UserID where Item_ID = $itemid");

        $stmt->execute();

        $rows = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead class="text-center">
                        <th class="text-center">Comment</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Control</th>
                    </thead>

                    <?php

                        foreach($rows as $data) {

                            echo "<tr>";
                            echo "<td>" . $data['Comment'] . "</td>";
                            echo "<td>" . $data['C_Date'] . "</td>";
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

            $itemid = $_POST['itemid'];
            $name = $_POST['name'];
            $description =  $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $category = $_POST['category'];
            $member = $_POST['member'];
            
            // Check If Category Exists In Database

            $check = checkItem('Item_ID','items',$itemid);

            if(!$check) {

                $theMsg = '<div class="alert alert-danger container"> Sorry, This Item Is Not Exists</div>';

                redirectHome($theMsg, 'back');
            }

            // Update Database

            $stmt = $con->prepare("UPDATE items SET `Name`=?,`Description`=?,Price =?,Country_Made =?,`Status`=?,Categ_ID =?,Member_ID=? WHERE Item_ID = ? ");

            $stmt->execute(array($name,$description,$price,$country,$status,$category,$member,$itemid));

            // Print Success Message

            $theMsg = '<div class="alert alert-success container"> Updated Successfully</div>';

            redirectHome($theMsg, 'back');

        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Delete') {

        echo '<h1 class="text-center">Delete Item Page</h1>';

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Check If Category Exists In Database

        $check = checkItem('Item_ID','items',$itemid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        // Update Database

        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = $itemid");

        $stmt->execute();

        // Print Success Message

        $theMsg = '<div class="alert alert-success container"> Deleted Successfully</div>';

        redirectHome($theMsg, 'back');

    } elseif($do == 'Approve') {

        echo '<h1 class="text-center">Approve Item Page</h1>';

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Check If Category Exists In Database

        $check = checkItem('Item_ID','items',$itemid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        // Update Database

        $stmt = $con->prepare("UPDATE items SET Approve =1 WHERE Item_ID = $itemid");

        $stmt->execute();

        // Print Success Message

        $theMsg = '<div class="alert alert-success container"> Approved Successfully</div>';

        redirectHome($theMsg, 'back');

    } else {

        header('Location:index.php');
        
        exit();
    }


    include $tpl .'footer.php';


    ob_end_flush();


?>
<?php

    ob_start(); // Start Output Buffering

    session_start();

    $page_name = 'Categories';

    if(!isset($_SESSION['UserName'])) {

        header('Location:index.php');

        exit();
    }

    include 'init.php';


    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage') {

        // For Sorting

        $sort = 'ASC';

        $sort_array = array('ASC','DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)) {

            $sort = $_GET['sort'];
        }

        // Select All Categories

        $stmt = $con->prepare("SELECT * FROM categories ORDER BY ID $sort");

        $stmt->execute();

        $rows = $stmt->fetchAll();

        ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Categories
                        <div class="ordering pull-right">
                            <a href="?sort=ASC" class = "<?php if($sort == 'ASC') echo 'active'; ?>">Asc</a>
                            <a href="?sort=DESC" class = "<?php if($sort == 'DESC') echo 'active'; ?>">Desc</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                            foreach($rows as $row) {

                                echo '<div class="categ">';
                                echo '<div class="hidden-buttons">';
                                    echo "<a href = 'categories.php?do=Edit&catid=" . $row['ID'] ."' class = 'btn btn-xs btn-primary confirm'><i class = 'fa fa-edit'></i>Edit</a>";
                                    echo "<a href = 'categories.php?do=Delete&catid=" . $row['ID'] ."' class = 'btn btn-xs btn-danger confirm'><i class = 'fa fa-close'></i>Delete</a>";
                                echo '</div>';
                                echo '<h3>' . $row['Name'] .  '</h3>';
                                    echo '<p>'; if($row['Description'] == ''){echo $row['Description'];} else echo 'No Description'; '</p>';
                                    if($row['Visibility'] == 0) {echo '<span class="visibility">Hidden</span>'; } 
                                    if($row['Allow_Comment'] == 0) {echo '<span class="commenting">Comment Disabled</span>'; } 
                                    if($row['Allow_Adds'] == 0) {echo '<span class="adds">Ads Disabled</span>'; } 
                                echo '</div>';
                                echo '<hr>';
                            }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> Add New Category</a>

            </div>

        <?php

    } elseif($do == 'Add') {

        ?>

        <h1 class="text-center">Add New Category</h1>
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
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ordering">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" id="vis-yes" name="visibility" value="1" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" id="vis-no" name="visibility" value="0">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Allow-Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" id="com-yes" name="allow-commenting" value="1" checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" id="com-no" name="allow-commenting" value="0">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label" required = "required">Allow-Adds</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" id="adds-yes" name="allow-adds" value="1" checked>
                            <label for="adds-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" id="adds-no" name="allow-adds" value="0">
                            <label for="adds-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>

        <?php

    } elseif($do == 'Insert') {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center">Insert Category Page</h1>';

            // Get Variables From The Form

            $name = $_POST['name'];
            $description =  $_POST['description'];
            $ordering = $_POST['ordering'];
            $visiblity = $_POST['visibility'];
            $commenting = $_POST['allow-commenting'];
            $adds = $_POST['allow-adds'];

            // Check If Category Exists In Database

            $check = checkItem('Name','categories',$name);

            if($check) {

                $theMsg = '<div class="alert alert-danger container"> Sorry, This Category Is Exists</div>';

                redirectHome($theMsg, 'back');
            }

            // Insert Category In Database

            $stmt = $con->prepare('insert into categories(Name,Description,Ordering,Visibility,Allow_Comment,Allow_Adds) values(?,?,?,?,?,?)');

            $stmt->execute(array($name,$description,$ordering,$visiblity,$commenting,$adds));

            // Print Successful Message

            $theMsg = '<div class="alert alert-success container">Added Successfully</div>';

            redirectHome($theMsg, 'BACK');


            
        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Edit') {

        // Get The ID 

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;

        // Get The Data From Database

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = $catid");

        $stmt->execute();

        $data = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count > 0) {

            ?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $data['ID'] ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name"  autocomplete="off" required = 'required' value="<?php echo $data['Name']?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="description" value="<?php echo $data['Description']?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ordering" value="<?php echo $data['Ordering']?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="vis-yes" name="visibility" value="1" <?php if($data['Visibility'] == 1) echo 'checked'; ?>>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="vis-no" name="visibility" value="0" <?php if($data['Visibility'] == 0) echo 'checked'; ?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Allow-Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="com-yes" name="allow-commenting" value="1" <?php if($data['Allow_Comment'] == 1) echo 'checked'; ?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="com-no" name="allow-commenting" value="0" <?php if($data['Allow_Comment'] == 0) echo 'checked'; ?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" required = "required">Allow-Adds</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="adds-yes" name="allow-adds" value="1" <?php if($data['Allow_Adds'] == 1) echo 'checked'; ?>>
                                    <label for="adds-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="adds-no" name="allow-adds" value="0" <?php if($data['Allow_Adds'] == 0) echo 'checked'; ?>>
                                    <label for="adds-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Update Category" class="btn btn-primary btn-block text-center">
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

            // Get Data From The Form

            $catid = $_POST['catid'];
            $name = $_POST['name'];
            $description =  $_POST['description'];
            $ordering = $_POST['ordering'];
            $visiblity = $_POST['visibility'];
            $commenting = $_POST['allow-commenting'];
            $adds = $_POST['allow-adds'];

            // Check If Category Exists In Database

            $check = checkItem('ID','categories',$catid);

            if(!$check) {

                $theMsg = '<div class="alert alert-danger container"> Sorry, This Category Is Not Exists</div>';

                redirectHome($theMsg, 'back');
            }

            // Update Database

            $stmt = $con->prepare("UPDATE categories SET `Name` = ?, `Description` =?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Adds = ? WHERE ID = $catid");

            $stmt->execute(array($name,$description,$ordering,$visiblity,$commenting,$adds));

            // Print Success Message

            $theMsg = '<div class="alert alert-success container"> Updated Successfully</div>';

            redirectHome($theMsg, 'back');

        } else {

            $errMsg = '<div class="alert alert-danger container">You cannot Browes this page directly</div>';

            redirectHome($errMsg, 'BACK');
        }

    } elseif($do == 'Delete') {

        echo '<h1 class="text-center">Delete Page</h1>';

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        // Check If Category Exists In Database

        $check = checkItem('ID','categories',$catid);

        if(!$check) {

            $theMsg = '<div class="alert alert-danger container"> The ID Is Not Found</div>';

            redirectHome($theMsg, 'back');
        }

        // Update Database

        $stmt = $con->prepare("DELETE FROM categories WHERE ID = $catid");

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
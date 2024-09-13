<?php

    ob_start();

    session_start();

    include 'init.php';

    $page_title = 'New Ad';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $formErrors = array();

        // Get Data And Validate

        $title    = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $desc     = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price    = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
        $country  = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

        if(strlen($title) < 4 || empty($title))
            $formErrors[] = 'Name Must Be Greater Than 4 Chars';
        if(strlen($desc) < 10 || empty($desc))
            $formErrors[] = 'Description Must Be Greater Than 10 Chars';
        if($price < 1 || empty($price))
            $formErrors[] = 'Price Must Be Greater Than $1';
        if(strlen($country) < 2) 
            $formErrors[] = 'Country Must Be Greater Than 2 Chars';
        if(empty($status))
            $formErrors[] = 'Status Is Required';
        if(empty($category))
            $formErrors[] = 'Category Is Required';

        // Add Item To Database

        if(empty($formErrors)){

            // $stmt= $con->prepare("INSERT INTO items(`Name`,`Description`,Price,Add_Date,Country_Made,`Status`)")
        }

    }

    if(isset($_SESSION['user'])) {

    ?>

    <h1 class="text-center">Create New Ad</h1>
    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Create New Ad</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label" required = "required">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control live" name="name"  autocomplete="off" required = 'required' data-class=".live-title">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control live" name="description" data-class=".live-desc">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Price</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control live" name="price" data-class=".live-price">
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
                        <div class="col-md-4">
                            <div class = "thumbnail item-box live-preview">
                                <span class="price-tag">$<span class="live-price"></span></span>
                                <img class = "img-responsive" src="img.png"/>
                                <div class = "caption">
                                    <h3 class='live-title'>Title</h3>
                                    <p class='live-desc'>Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ERRORS -->
                    <div class="the-errors text-center">
                        <?php
                            if(!empty($formErrors)){
                    
                                foreach($formErrors as $error) {

                                    echo '<div class="alert alert-danger container">' . $error . '</div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    } else {

        header('Location: login.php');

        exit();
    }

?>

    

<?php

    include $tpl . 'footer.php';

    ob_end_flush();

?>
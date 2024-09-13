<?php 

    ob_start();

    session_start();

    $page_title = 'Items';

    include 'init.php';

    // Get The ID 

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

    // Get The Data From Database

    $stmt = $con->prepare("SELECT items.*,categories.Name as categ_name,users.UserName
                                from items inner join categories on items.Categ_ID = categories.ID
                                inner join users on users.UserID = items.Member_ID");

    $stmt->execute();

    $count = $stmt->rowCount();

    if($count > 0) {

        $data = $stmt->fetch();

        ?>

            <h1 class="text-center"><?php echo $data['Name'] ?></h1>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <img src="img.png" class="img-responsive img-thumbnail center-block">
                    </div>
                    <div class="col-md-9">
                        <h2><?php echo $data['Name'] ?></h2>
                        <p><?php echo $data['Description'] ?></p>
                        <span><?php echo $data['Add_Date'] ?></span>
                        <div>Price: <?php echo $data['Price'] ?></div>
                        <div>Made In: <?php echo $data['Country_Made'] ?></div>
                        <div><a href='categories.php?pageid=<?php echo $data['Categ_ID'] ?>'>Category: <?php echo $data['categ_name'] ?></a></div>
                        <div>Added By: <?php echo $data['UserName'] ?></div>
                    </div>
                </div>
            </div>

        <?php

    } else {

        echo '<h1 class="text-center">There Is No Such Item</h1>';
    }

    


?>

    

<?php

    include $tpl . 'footer.php';

    ob_end_flush();
?>
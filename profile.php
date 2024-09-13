<?php

    session_start();

    include 'init.php';

    $page_title = 'Profile';

    if(isset($_SESSION['user'])) {

        // Get User Data

        $stmt = $con->prepare("SELECT * FROM users WHERE UserName = ?");

        $stmt->execute(array($session_user));
    
        $data = $stmt->fetch();
    ?>

    <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Information</div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Name:</span> <?php echo $data['UserName'] . '</br>'; ?>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <span>Email:</span> <?php echo $data['Email'] . '</br>'; ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>FullName:</span> <?php echo $data['FullName'] . '</br>'; ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>RegDate:</span> <?php echo $data['Date'] . '</br>'; ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Fav Category:</span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Ads</div>
                <div class="panel-body">
                    <div class="row">
                        <?php
                        if(!empty(getItems('Member_ID',$data['UserID']))){
                            foreach(getItems('Member_ID',$data['UserID']) as $item) {
                                echo '<div class = "col-sm-6 col-md-3">';
                                    echo '<div class = "thumbnail item-box">';
                                        echo '<span class="price-tag">' . $item['Price'] . '</span>';
                                        echo '<img class = "img-responsive" src="img.png"/>';
                                        echo '<div class = "caption">';
                                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] .' "' . $item['Name'] . '</h3>';
                                            echo '<p>' . $item['Description'] . '</p>';
                                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        } else {

                            echo 'There Is No Ads! <a href="newad.php">Add New Ad</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="My-comments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Comments</div>
                <div class="panel-body">
                    <?php 

                        // Get Comments

                        $stmt = $con->prepare("SELECT Comment FROM comments WHERE User_ID = ?");

                        $stmt->execute(array($data['UserID']));

                        $data = $stmt->fetchAll();

                        if(!empty($data)) {

                            foreach($data as $comment) {

                                echo '<p>' . $comment['Comment'] . '</p>';
                            }

                        } else {

                            echo 'There Is No Comments';
                        }

                    ?>
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

?>
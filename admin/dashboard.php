<?php

ob_start();

session_start();

if(isset($_SESSION['UserName'])) {

    $page_title = 'Dashboard';

    include 'init.php';

    ?>

        <div class="container home-stats">
            <h1 class="text-center">Dashboard</h1>
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="stat st-members">
                        Total Members
                        <span><a href="members.php"><?php echo countItems("UserID", "users") ?></a></span>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat st-pending">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo CountItemsWithCondition("RegStatus", "users", "RegStatus", 0) ?></a></span>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat st-members">
                        Total Items
                        <span><a href="items.php"><?php echo countItems("Item_ID", "items") ?></a></span>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat st-comments">
                        Total Comments
                        <span><a href="comments.php"><?php echo countItems("C_ID", "comments") ?></a></span>
                    </div>
                </div>
            </div>

            <div class="latest">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest Registerd Users
                                <span class="pull-right toggle-info">
                                    <i class="fa fa-minus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php

                                        $latestUsers = getLatest('*', 'users', 'UserID desc');

                                        foreach($latestUsers as $user) {

                                            echo '<li>' . $user['UserName'] . '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i><a href=members.php?do=Edit&userid='.$user['UserID'].'>Edit</a></span></li>';
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest Items
                                <span class="pull-right toggle-info">
                                    <i class="fa fa-minus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php

                                        $latestItems = getLatest('*', 'items', 'Item_ID desc');

                                        foreach($latestItems as $item) {

                                            echo '<li>' . $item['Name'] . '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i><a href=items.php?do=Edit&itemid='.$item['Item_ID'].'>Edit</a></span>'; if($item['Approve']==0) echo '<span class="btn btn-info pull-right"><i class="fa fa-edit"></i><a href=items.php?do=Approve&itemid='.$item['Item_ID'].'>Approve</a></span>'; echo'</li>';
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <?php

    include $tpl . "footer.php";

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush();
?>
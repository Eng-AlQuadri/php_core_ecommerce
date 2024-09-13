<?php

    session_start();

    include 'init.php';

?>

    <div class="container">


        <div class="row">
            <?php
                foreach(getItems('Categ_ID',$_GET['pageid']) as $item) {
                    echo '<div class = "col-sm-6 col-md-3">';
                        echo '<div class = "thumbnail item-box">';
                            echo '<span class="price-tag">' . $item['Price'] . '</span>';
                            echo '<img class = "img-responsive" src="img.png"/>';
                            echo '<div class = "caption">';
                                echo '<h3>' . $item['Name'] . '</h3>';
                                echo '<p>' . $item['Description'] . '</p>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>

<?php

    include $tpl .'footer.php';

?>
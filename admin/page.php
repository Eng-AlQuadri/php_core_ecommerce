<?php 

    /*
        categories => [Manage | Edit | Update | Add | Insert | Delete | Status]
    */


    if(isset($_GET['do'])){

        $do = $_GET['do'];

    } else {

        $do = 'Manage';
    }

    // If The Page Is Main Page

    if($do == 'Manage') {

        echo 'welcome you are in category Page';

    } else if ($do == 'Add') {

        echo 'welcome you are in Add Page';

    } else {

        echo 'Error: there is no page with this name';

    }


?>
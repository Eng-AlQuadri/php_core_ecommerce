<?php

/*
    Title Function v1.0
    Title Function That Print The Page Title if
    $page_title exsists, else echo default for
    other pages
*/

function get_Title()
{

    global $page_title;

    if(isset($page_title)) {

        echo $page_title;

    } else {

        echo 'default';
    }

}



/*
** Home Redirect Function [This Function Accepts Parameter] v2.0
** $theMsg       => Print The Message
** $url       => The Link You Want To Redirect To
** $seconds   => Seconds Before Redirecting
*/


function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if($url === null) {

        $url = 'index.php';

        $link = 'Home Page';

    } else {


        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];

            $link = 'Previous Page';

        } else {

            $url = 'index.php';

            $link = 'Home Page';
        }
    }

    echo $theMsg;

    echo "<div class = 'alert alert-info container'>You Will Be Redirected To $link After $seconds Seconds</div>";

    header("refresh:$seconds;url=$url");

    exit();
}


/*
 * Check Item Function v1.0
 * Function To Check Item In Database [Function Accepts Parameters]
 * $select   => The Item To Select [ Example: user, item, category ]
 * $from     => The Table To Select From [ Example: users, items, categories ]
 * $value    => The Value Of Select [ Example: John, Box, Electronics]
 */


function checkItem($select, $from, $value)
{

    global $con;

    $stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $stmt->execute(array($value));

    $count = $stmt->rowCount();

    if($count > 0) {

        return 1;
    }

    return 0;
}



/*
 * Count Number Of Item Function v1.0
 * $item  => The Item To Count
 * $table => The Table To Choose From
 */


function countItems($item, $table)
{
    global $con;

    $stmt = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt->execute();

    $count = $stmt->fetchColumn();

    return $count;
}



/*
 * Function Count Item With Condition
 * Parameters
 * $element
 * $table
 * $condition
 * $value
 */


function CountItemsWithCondition($element, $table, $condition, $value)
{

    global $con;

    $stmt = $con->prepare("SELECT $element FROM $table WHERE $condition = $value");

    $stmt->execute();

    $result = $stmt->rowCount();

    return $result;
}


/*
 * Function To Get Latest Things
 * Variables
 * $select
 * $table
 * $order by
 * $limit
 */


function getLatest($select, $table, $orderBy, $limit = 5)
{

    global $con;

    $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $orderBy LIMIT 5");

    $stmt->execute();

    $result = $stmt->fetchAll();

    return $result;
}



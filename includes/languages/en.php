<?php


function lang($phrase)
{
    static $lang = array(

        // Navbar

        'HOME_ADMIN'       => 'Home',
        'CATEGORIES'       => 'Categories',
        'ITEMS'            => 'items',
        'MEMBERS'          => 'members',
        'STATISTICS'       => 'statistics',
        'LOGS'             => 'logs'

    );

    return $lang[$phrase];
}

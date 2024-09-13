<?php 

    function lang ($phrase) {
        static $lang = array(
            'Message' => 'Welcome in arabic',
            'Admin' => 'arabic adminstrator'
        );

        return $lang[$phrase];
    }
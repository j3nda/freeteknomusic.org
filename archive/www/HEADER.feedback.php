<?php

    /**
     * HEADER.feedback.php, (c) <tekk@fv.cz>
     * -- feedback main SIMPLE controller
     * --
     * -- rev.0.1, 110728-2300jsm - file created && initial concept
     */


    include_once dirname(__FILE__).'/HEADER.dibi.min.php';
    dibi::connect(array(
        'driver'   => 'mysql',
        'charset'  => 'utf8',
        'username' => 'fv.cz.tekk',
        'password' => 'fv2304T3KK',
        'database' => 'fvcz_tekk',
        'host'     => 'localhost',
    ));

    $view = (isset($_REQUEST['view']) ? strtolower(trim($_REQUEST['view'])) : 'list');
    switch($view) {
        case 'form':
        case 'add':
        case 'list':
        default:
            break;
    }
    echo $html;
    exit;

?>

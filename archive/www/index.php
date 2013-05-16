<?php

    // 130119-2117jsm - +ibrowse!
    // 130115-2252jsm - +support for: /p.xspf/ without .htaccess, WTF!@#$%^&*() wedos???


    $URI = $_SERVER['REQUEST_URI'];

    include_once __DIR__.'/CONFIG.php';


    // HOOK: /p.xspf/
    if (preg_match('/^\/p\.xspf\/?(.*)$/i', $URI, $tmp)) {
        if (empty($tmp[1])) {
            include_once __DIR__.'/404.php';
            exit;
        }
        $_REQUEST['dir'] = $tmp[1].'/';

        include_once __DIR__.'/p.php';
        exit;
    }


    $_REQUEST['id'] = $URI;


    // WEB-SITE
    include_once __DIR__.'/HEADER.php';
    include_once __DIR__.'/.ibrowse/i.php';
    include_once __DIR__.'/FOOTER.php';


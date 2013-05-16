<?php

    // 130411-1821jsm - +google adSense, +http://archive.freeteknomusic.org/cgi.ads/gas

    include_once __DIR__.'/../CONFIG.php';

    $list = array(
        'img-728x90/130411-1831-freeteknomusic.org.png',
    );
    $max  = count($list);
    $rnd  = mt_rand(0, $max-1);


    // TODO: uid ~ $_REQUEST[uid]

    // NOTE:
    // uid = 'gas' ~ google adsence

    $info = getimagesize($list[$rnd]);
    header('Content-Type: '.$info['mime']);
    header('Content-Length: '.filesize($list[$rnd]));

    readfile($list[$rnd]);


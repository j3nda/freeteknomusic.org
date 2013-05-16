<?php

    // .rand.php, (c) tekk@fv.cz
    // -- generuje on-the-fly playlist
    // --
    // -- rev.0.6, 130115-2213jsm - revize pro: freeteknomusic.org
    // -- rev.0.5, 090531-1400jsm - revize souboru: playlist.php -> .rand.php
    // -- rev.0.4, 090521-1336jsm - *is_rand, rc2
    // -- rev.0.3, 090521-1317jsm - *func, rc1
    // -- rev.0.2, 090424-1133jsm - +dir_recursive, +rand|sort|rsort
    // -- rev.0.1, 090101-2034jsm - revize, +logovani

    include_once dirname(__FILE__).'/../playlist.func.php';

    $u      ='http://archive.freeteknomusic.org';
    $DIR    =dirname(__FILE__).'/../../../../mp3/';
//    $dir    =(isset($_REQUEST['dir']) && trim($_REQUEST['dir']) != '' ? trim($_REQUEST['dir']) : false);
//    $opt    =(isset($_REQUEST['opt']) && trim($_REQUEST['opt']) != '' ? trim($_REQUEST['opt']) : false);
    $dir    ='';
    $opt    ='.rand';

    $is_rand=($opt == '.rand' || $opt == '.rnd' ? true : false);

/*
    if ($dir == '' || !is_dir($DIR.$dir) || !is_readable($DIR.$dir)) {
        echo 'sorry, no dir!';
        exit;
    }
    

    if (($cache=__is_cache($dir, ($is_rand ? false : $opt), $DIR)) !== false) {
        header('Content-Type: audio/x-mpegurl');
        header('Content-Length: '.filesize($cache));

        if ($is_rand) {
            echo implode('', __opt(file($cache), $opt));

        } else {
            echo file_get_contents($cache);
        }

        $ff=fopen($DIR.'.playlists/request.log', 'a+');
        fputs($ff, date('Y-m-d H:i:s').', ip: '.$_SERVER['REMOTE_ADDR'].' , dir['.$opt.']: '.$dir."\n");
        fclose($ff);

        exit;

    } else {
*/
        $cache=__md5_cache($dir, ($is_rand ? false : $opt), $DIR);
//    }

    $fi=__opt(__get_files($dir, $u, $DIR, true), $opt);
    $S =implode("\n", $fi);
//    $ff=fopen($cache, 'w');
//    fputs($ff, $S, strlen($S));
//    fclose($ff);
//    chmod($cache, 0666);


    header('Content-Type: audio/x-mpegurl');
    header('Content-Length: '.strlen($S));
    echo $S;

//    $ff=fopen($DIR.'.playlists/request.log', 'a+');
//    fputs($ff, date('Y-m-d H:i:s').', ip: '.$_SERVER['REMOTE_ADDR'].' , dir['.$opt.']: '.$dir."\n");
//    fclose($ff);

    exit;

?>

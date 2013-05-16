<?php

    // p.php, (c) fv.cz <jan@smid.sk>
    // -- nahodne generuje playlist pro flash-ovy prehravac
    // --
    // -- rev.0.5, 130115-2256jsm - upgrade for: freeteknomusic.org
    // -- rev.0.4, 090719-1719jsm - *zdroj: http://musicplayer.sourceforge.net/
    // -- rev.0.3, 090521-1354jsm - *cache, *.rand, rc2
    // -- rev.0.2, 090521-1134jsm - *playlist-podle-umisteni-prehravace
    // -- rev.0.1, 090508-0025jsm - rc1

    include_once dirname(__FILE__).'/playlist.func.php';

//    $u   = 'http://archive.freeteknomusic.org/';
    $u   = 'http://freeteknomusic.org/mp3/';
    $dir = (isset($_REQUEST['dir']) && trim($_REQUEST['dir']) != ''
                ? rawurldecode(substr(str_replace(array('/../', '/./'), '', trim($_REQUEST['dir'])), 0, -1))
                : false
    );
    $DIR = dirname(__FILE__).'/../../mp3/';
    $dP  = dirname(__FILE__).'/.playlists/';
    $d   = $DIR.$dir;
    $isF = false;

    if ($dir != '' && is_readable($d) && is_dir($d)) {
        if (($cache=__is_cache($dir, false, $DIR)) !== false) {
            $lines=__opt(file($cache), '.rand');

        } else {
            $cache=__md5_cache($dir, false, $DIR, $dP);
            $fi   =__get_files($dir, $u, $DIR);
            $S    =implode("\n", $fi);

            $ff=fopen($cache, 'w');
            fputs($ff, $S, strlen($S));
            fclose($ff);
            chmod($cache, 0666);

            unset($cache, $S, $ff);
            $lines=__opt($fi, '.rand');

            unset($fi);
        }
        if (!is_array($lines) || count($lines) == 0) {
            $lines=__opt(file($DIR.$dP.'.rand'), '.rand');
            $isF  =true;
        }

    } elseif (is_readable($DIR.$dP.'.rand')) {
        $lines=__opt(file($DIR.$dP.'.rand'), '.rand');
        $isF  =true;
    }


if (isset($lines) && count($lines) > 0) {
    $tmp='';
    for($i=0, $max=count($lines); $i<$max; $i++) {
        if ($isF && $i >= 9) {
            break;
        }
        $nn  =str_replace(array(chr(10), chr(13)), array('', ''), $lines[$i]);
        $bn  =basename($nn);
        $dn  =str_replace($u, '', dirname($nn));
        $tmp.='<track>'
            . '<location>'.$nn.'</location>'
            . '<title>'.$dn.' :: '.$bn.'</title>'
            . '</track>'
            . '';
    }

    // radio-mode=true
    $tmp.='<track>'
        . '<location>http://archive.freeteknomusic.org/p.xspf</location>'
        . '</track>'
        . '';


    $out="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
        ."<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n"
        ."<trackList>"
        .$tmp
        ."</trackList>\n"
        ."</playlist>\n"
        ."";

//    header('Content-Type: audio/x-mpegurl');
    header('Content-Type: text/xml');
    header('Content-Length: '.strlen($out));

    echo $out;

    $ff=fopen(($dP != '' ? $dP : $DIR.'.playlists/').'request.log', 'a+');
    fputs($ff, date('Y-m-d H:i:s')
                    .', ip: '.$_SERVER['REMOTE_ADDR'].' , random-playlist'
                        . (!$isF && $dir != '' ? '('.$dir.')' : '')
                        . (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != '' ? ', ('.$_SERVER['HTTP_USER_AGENT'].')' : '')
                    ."\n"
    );
    fclose($ff);
}
    exit;

?>

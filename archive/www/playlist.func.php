<?php

    // playlist.func.php, (c) <jan@smid.sk>
    // -- generuje on-the-fly playlist: funkce
    // --
    // -- rev.0.4, 130115-2218jsm - upgrade for: freeteknomusic.org
    // -- rev.0.3, 090521-1306jsm - revize: playlist.php -> playlist.func.php
    // -- rev.0.2, 090424-1133jsm - +dir_recursive, +rand|sort|rsort
    // -- rev.0.1, 090101-2034jsm - revize, +logovani


// doc: vraci pole se seznamem souboru [rekurzivni prochazeni]
// doc: -- dir = vychozi adresar, pro ktery chceme ziskat seznam
// doc: -- u   = url adresa       [http://tekk.fv.cz/]
// doc: -- D   = korenovy adresar [dirname(__FILE__).'/']
function __get_files($dir='', $u=false, $D=false, $is_empty=false) {
    $r  =array();
    if (!$is_empty && $dir == '') { return $r; }

    $dir=preg_replace('/\/+$/i', '', $dir);
    $dd =opendir($D.$dir);
    while ($dn=readdir($dd)) {
        if ($dn == '.' || $dn == '..') { continue; }

        if (is_dir($D.$dir.'/'.$dn) && is_readable($D.$dir.'/'.$dn)) {
            $r=array_merge($r, __get_files($dir.'/'.$dn, $u, $D));
        }

        if (
            is_file($D.$dir.'/'.$dn)     &&
            is_readable($D.$dir.'/'.$dn) &&
            preg_match('/.+\.(mp3|ogg|wav|wma)$/i', $dn)
           ) {

//            $fi.=$u.str_replace('%2F', '/', rawurlencode(ereg_replace('//', '/', $dir.'/'.$dn)))."\n";
            $r[]=$u.str_replace('%2F', '/', rawurlencode($dir.'/'.$dn));
        }

    }
    closedir($dd);
    return $r;
}


// doc: vrati uplnou cestu k souboru v cache
function __md5_cache($dir, $opt, $DIR, $dP=null) {
    return ereg_replace('//', '/', ($dP != '' ? $dP : $DIR.'.playlists/').md5($dir.'/playlist'.$opt));
}


// doc: vraci false pokud cache soubor neexistuje, jinak vraci jeho uplnou cestu
function __is_cache($dir, $opt, $DIR=false) {
    if ($DIR === false) {
        $DIR=dirname(__FILE__).'/';
    }

    $p =__md5_cache($dir, $opt, $DIR);
    $ft=0;

    if (is_readable($p)) {
        $ft=filemtime($p);
    }

    if (time()-(24*60*60) <= $ft) {
        return $p;
    }
    return false;
}


// doc: aplikuje $opt na "playlist"
function __opt($fi, $opt) {
    switch($opt) {
        case '.rand':
        case '.rnd':
            shuffle($fi);
            break;

        case '.asort':
        case '.rsort':
            rsort($fi);
            break;

        default:
        case '':
        case '.sort':
            sort($fi);
            break;
    }

    return $fi;
}


?>

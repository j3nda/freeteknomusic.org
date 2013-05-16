<?php

    // i.config.php, (c) <jan@smid.sk>
    // -- nastaveni
    // --
    // -- rev.0.4, 130411-1855jsm - +google adSense
    // -- rev.0.3, 130119-2056jsm - upgrade for: freeteknomusic.org; *copyright:tekk.fv.cz <tekk@fv.cz> -> jan@smid.sk
    // -- rev.0.2, 090628-2026jsm - +functions: _{header|footer|cache_is|cache_gen|get_dir}()
    // -- rev.0.1, 090626-1508jsm - vytvoreni souboru


$cache = array(
    '#' => 600,     // default ttl
    '@' => 1800,    // id='' <empty> ~ / ~ index

//    'id' => 1234, //
);

date_default_timezone_set('Europe/Prague');

// zakladni adresar
define('IDIR',      __DIR__);                            // napr: /home/www/tekk/.ibrowse
define('ADIR',      realpath(__DIR__.'/../'));           // napr: /home/www/tekk/
//define('DIR',       realpath(__DIR__.'/../../../mp3/')); // napr: /home/www/tekk
define('DIR',       (
    $_SERVER['SERVER_NAME'] == 'fv.cz.tekk.dev.fv.loc'
        ? realpath(__DIR__.'/../../www_data_mp3.faked/')
        : realpath(__DIR__.'/../../../mp3/')
));
define('CACHE_DIR', IDIR.'/.cache');                     // napr: /home/www/tekk/.ibrovse/.cache
define('URL',       'http://freeteknomusic.org/mp3');    // napr: http://tekk.fv.cz/


// doc: generuje 404 chybovou hlasku
// doc: -- from = url adresa, kam se muzeme alternativne presmerovat. logiku resi: 404.php
function _404($from='') {
    @header('HTTP/1.0 404 Not Found');

    $GLOBALS['from']     = $from;
    $GLOBALS['noheader'] = true;
    $GLOBALS['nofooter'] = true;

    include_once ADIR.'/404.php';
//    exit;
}


function _url($url) {
    return str_replace('%2F', '/', rawurlencode($url));
}

// doc: vraci true|false na dostupnost cache souboru
function _cache_is($fn=false, $ttl=false) {
    if ($fn !== false && is_readable($fn)) {
        if ($ttl === false) {
            global $cache;
            $ttl = $cache['#'];
        }
        $ft=filemtime($fn);
        if (time() < ($ft+$ttl)) {
            return true;
        }
        unlink($fn);
    }
    return false;
}


// doc: zobrazi hlavicku stranky
function _header() {
//    readfile(ADIR.'/HEADER.html');
}

// doc: zobrazi paticku stranky
function _footer() {
//    readfile(ADIR.'/FOOTER.html');
}


// doc: array of files without directories... optionally filtered by extension
function _get_dir_files($d, $x=false, $deny=false) {
    $l = array();
    foreach(array_diff(scandir($d), array('.',' ..')) as $f) {
//        if (is_file($d.'/'.$f) && (($x) ? ereg($x.'$', $f) :1)) {
        if (is_file($d.'/'.$f) && (($x !== false) ? preg_match('/'.$x.'$/i', $f) : 1)) {
            if (!$deny || ($deny && !preg_match('/'.$deny.'/i', $f))) {
                $l[] = $f;
            }
        }
    }
    return $l;
}


// doc: array of directories
function _get_dir_dirs($d) {
    $l = array();
    foreach(array_diff(scandir($d), array('.', '..')) as $f) {
        if (is_dir($d.'/'.$f) && !preg_match('/^\..+$/i', $f)) {
            $l[] = $f;
        }
    }
    return $l;
}


// doc: vrati seznam souboru a adresaru
function _get_dir($dir=false) {
    if ($dir !== false && is_dir($dir) && is_readable($dir)) {
        return array_merge(_get_dir_dirs($dir), array('-'), _get_dir_files($dir, false, '^\.htaccess$'));
    }
    return false;
}


// doc: zapise data do souboru
function _write_file($data, $fn) {
    if (($ff=fopen($fn, 'w')) !== false) {
        fputs($ff, $data, strlen($data));
        fclose($ff);
        chmod($fn, 0666);
    }
    return true;
}


// doc: generuje cache soubor pro dalsi nacteni
function _cache_gen($dir, $data, $id, $fn) {

    // vlastni zobrazeni obsahu 1x adresare
/*
    echo '<pre>';
    echo var_dump($dir)."\n";
    echo var_dump($id)."\n";
    echo var_dump($fn)."\n";
    print_r($data);
    echo '</pre>';
*/
    $C = array(
        'wrec' => 32,
    );


    global $CONFIG;

    // google adSense - repeat: divided into equals pieces
    $C['max'] = count($data);
    $C['gas-728x90'] = $CONFIG['ads']['google-adsense-a_siroky-728x90'];

//echo '<pre>';print_r($data);exit;


ob_start();
    echo  "<?php\n"
        . "function _cache() {\n"
        . "?>\n";

    echo  '<table style="width:100%;border:0px;"><tr>'
        . '<td style="width:24px;"><img src="/img/icons/apache/blank.gif" alt="Icon "></td>'
        . '<td style="width:30%;"><a href="?C=N;O=D">Name</a></td>'
        . '<td style="width:20%;"><a href="?C=M;O=A">Last modified</a></td>'
        . '<td style="width:10%;"><a href="?C=S;O=A">Size</a></td>'
        . '<td style="width:;"><a href="?C=D;O=A">Description</a></td>'
        . '</tr>'
        . '<tr><td colspan="5"><hr /></td></tr>'
        . '';

    $D  = true;
    $P  = true;
    $i  = 0;
    $iD = 0; // counter: dirs
    $iF = 0; // counter: files
    foreach($data as $rec) {
        if ($rec == '-') {
            $D = false;
            continue;
        }
        $rec_len = mb_strlen($rec);


        echo '<tr>';
        if ($P) {
            $P = false;
            if (!empty($id)) {
                echo ''
                    .'<td><img src="/img/icons/apache/back.gif" alt="[DIR]"></td>'
                    .'<td><a href="'._url(dirname($id)).'">Parent Directory</a></td>'
                    .'<td>&nbsp;</td>'
                    .'<td>&nbsp;</td>'
                    .'<td>&nbsp;</td>'
                    .'</tr><tr>';
            }
        }


        if ($D) {
            $iD++;
            if ($rec == '.' || $rec == '..') { continue; }
            echo '<td><img src="/img/icons/apache/folder.gif" alt="[DIR]"></td>'
                .'<td><a href="'._url($id.($id != '' ? '/' : '').$rec).'">'
                    .mb_substr($rec, 0, $C['wrec'])
                    .($rec_len > $C['wrec'] ? '...' : '')
                    .'</a></td>'
                .'<td>&nbsp;</td>'
                .'<td>&nbsp;</td>'
                .'<td>&nbsp;</td>'
                .'';

            // ads
            if ($C['gas-728x90']['repeat-dir'] > 0 && $iD % $C['gas-728x90']['repeat-dir'] == 0) {
                echo '</tr>';
                echo '<td colspan="5"><div style="width:719px;height:90px;overflow:hidden;background-image:url(\'/.ads/img-728x90/130411-1831-freeteknomusic.org.png\');">'
                        .$C['gas-728x90']['content']
                        .'</div></td>';
                echo '<tr>';

//                $C['gas-728x90']['repeat-dir'] += mt_rand(-2, 2);
            }


        } else {
            // filtr na priponu
            // mp3|ogg|wma|wav -> sound2.gif
            $iF++;
            $fullpath = realpath($dir.'/'.$rec);
            echo '<td><img src="/img/icons/apache/sound2.gif" alt="[SND]"></td>'
                .'<td><a href="'.URL._url(($id != '' ? $id.'/' : '').$rec).'">'
                    .mb_substr($rec, 0, $C['wrec'])
                    .($rec_len > $C['wrec'] ? '...' : '')
                    .'</a></td>'
                .'<td>'.date('j-M-Y H:i', filectime($fullpath)).'</td>'
                .'<td>'._size2human(filesize($fullpath)).'</td>'
                .'<td>&nbsp;</td>'
                .'';

            // ads
            if ($C['gas-728x90']['repeat-file'] > 0 && $iF % $C['gas-728x90']['repeat-file'] == 0) {
                echo '<tr>';
                echo '<td colspan="5"><div style="width:719px;height:90px;overflow:hidden;background-image:url(\'/.ads/img-728x90/130411-1831-freeteknomusic.org.png\');">'
                        .$C['gas-728x90']['content']
                        .'</td>';
                echo '</div></tr>';

//                $C['gas-728x90']['repeat-file'] += mt_rand(-3, 3);
            }

        }
        echo '</tr>';
    }

    echo '<tr><td colspan="5"><hr /></td></tr>';
    echo '</table>';
    echo "<?php\n"
        . "}\n"
        . "?>\n";
$out = ob_get_contents();
ob_end_clean();

    _write_file($out, $fn);
}
//function _cache() {}


// doc: prevede velikost na lidsky citelnou velikost (512 MB, 3 GB, 400 kB, ...)
// doc: -- size = velikost v bytes
function _size2human($size=0)
{
    $_format="%s %s";
    $_sizes =array( 'b' => 'b', 'kb' => 'kB', 'mb' => 'MB', 'gb' => 'GB' );

    if ($size == 0) { return sprintf($_format, $size, $_sizes['b']); }

    $_kb=1024;  // kilo
    $_mb=1024*$_kb; // mega
    $_gb=1024*$_mb; // giga

    if ($size > $_gb) {
        return sprintf($_format, round($size/$_gb, 2), $_sizes['gb']);

    } else if ($size > $_mb) {
        return sprintf($_format, round($size/$_mb, 2), $_sizes['mb']);

    } else if ($size > $_kb) {
        return sprintf($_format, round($size/$_kb, 2), $_sizes['kb']);
    }

    return sprintf($_format, $size, $_sizes['b']);
}


?>

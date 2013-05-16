<?php

    // i.php, (c) <jan@smid.sk>
    // -- prochazeni archivu s moznosti definice dalsich akci
    // --
    // -- rev.0.3, 130119-2056jsm - upgrade for: freeteknomusic.org; *copyright:tekk.fv.cz <tekk@fv.cz> -> jan@smid.sk
    // -- rev.0.2, 090628-2027jsm - logika
    // -- rev.0.1, 090626-1508jsm - vytvoreni souboru

    include_once dirname(__FILE__).'/i.config.php';

$id  = rawurldecode(preg_replace('/\/$/', '', (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : false)));
$md5 = md5($id);
$ttl = (trim($id) == '' ? $cache['@'] : (isset($cache[$id]) ? $cache[$id] : $cache['#']));


// existuje pro tento zaznam v cache?
// ano: nactu jej primo
//  ne: generuju
if (_cache_is(CACHE_DIR.'/'.$md5, $cache['#'])) {
    include CACHE_DIR.'/'.$md5;

    // $_cache=array();
    // _cache() {}

    _header();
    _cache();
    _footer();
//    exit;

} else {
//echo var_dump(IDIR);
//echo var_dump(ADIR);
//echo var_dump(DIR);
//echo var_dump(CACHE_DIR);
//echo var_dump(URL);
//exit;
    // je $id adresar?
    // ano: provedu jeho projiti a vytvoreni seznamu
    //  ne: provedu redirect o uroven niz || zobrazim 404 s nabidkou o uroven vys
    if (!is_dir(DIR.'/'.$id)) {
        _404(_url($id != '' ? dirname($id) : ''));
//        exit;

    } else {
        $data = _get_dir(DIR.'/'.$id);
        _cache_gen(DIR.'/'.$id, $data, $id, CACHE_DIR.'/'.$md5);

        include CACHE_DIR.'/'.$md5;
// $_cache=array();
// _cache() {}

        _header();
        _cache();
        _footer();
//        exit;
    }
}

?>

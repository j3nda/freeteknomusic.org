<?php

    if (!isset($GLOBALS['noheader']) || !$GLOBALS['noheader']) {
        include_once __DIR__.'/HEADER.php';
    }

?>
<div id="nenalezen">
  <div id="uvnitr">
    <p class="velky">404, page not found</p>
    <p style="padding-top:1em;padding-bottom:1em;">go to entry page:
    <a href="/" title="tekno stable archive entry page(archive.freeteknomusic.org)">archive.freeteknomusic.org</a> of this archive<?php
        if (isset($GLOBALS['from']) && $GLOBALS['from']) {
            echo '<br /> or try <a href="'.$from.'" title="go to upper">this</a> page';
        }
?>.</p>
    <img src="/404.jpg" alt="the man, who shows on 404 - not found" style="border:solid 1px black;" />
  </div>
</div>
<?php

    if (!isset($GLOBALS['nofooter']) || !$GLOBALS['nofooter']) {
        include_once __DIR__.'/FOOTER.php';
    }
?>

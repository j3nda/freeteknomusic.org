<?php

    // 130411-1825jsm - +config

$CONFIG = array(
    'ads' => array(
        'billboard-468x60'               => true,
        'google-adsense-a_siroky-728x90' => array(
            'repeat-dir'  => mt_rand(35, 72),
            'repeat-file' => mt_rand(18, 35),
            'content'     => file_get_contents(__DIR__.'/.ads/codes/google-adsense-a_siroky-728x90.html'),
        ),
    ),
);

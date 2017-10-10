<?php

function X($x) {
    echo "<pre>";
    print_r($x);
    echo "</pre>";
}

function isLocalhost() {
    return (strpos($_SERVER["HTTP_HOST"], "localhost") > -1);
}

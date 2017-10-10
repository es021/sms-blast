<?php

include_once 'functions.php';

/* * URLs** */
DEFINE("HOST_URL", "http://" . $_SERVER["HTTP_HOST"]);
DEFINE("APP_URL", HOST_URL . "/SMSBlast/app/");
DEFINE("SERVICE_URL", HOST_URL . "/SMSBlast/service/");
DEFINE("IS_PROD", !isLocalhost());

/* * App Basic ** */
DEFINE("APP_NAME", "SMS Blast");
DEFINE("APP_DESC", "powered by Westports IT");
DEFINE("APP_ICON", APP_URL . "img/westports.png");
DEFINE("APP_VERSION", "1.0");

/* * DATABASE CONFIG** */
if (IS_PROD) {
    DEFINE("DB_HOST", "localhost");
    DEFINE("DB_USER", "");
    DEFINE("DB_PASSWORD", "");
} else {
    DEFINE("DB_HOST", "localhost");
    DEFINE("DB_USER", "");
    DEFINE("DB_PASSWORD", "");
}

/* * STATIC ASSET VERSION ** */
DEFINE("JS_VERSION", "v1");
DEFINE("CSS_VERSION", "v1");
DEFINE("HTML_VERSION", "v1");



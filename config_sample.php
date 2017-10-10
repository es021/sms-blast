<?php

include_once 'functions.php';

/* * App Basic ** */
DEFINE("IS_PROD", !isLocalhost());
DEFINE("APP_NAME", "SMS Blast");
DEFINE("APP_VERSION", "1.0");

/* * URLs** */

DEFINE("HOST_URL", "http://" . $_SERVER["HTTP_HOST"]);
DEFINE("APP_URL", HOST_URL . "/SMSBlast/app/");
DEFINE("SERVICE_URL", HOST_URL . "/SMSBlast/service/");

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



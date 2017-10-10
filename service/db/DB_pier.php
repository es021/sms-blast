<?php

class DB_pier extends DB {

    const DB_NAME = "pier";
    const TB_SMS_GATEWAY = "sms_gateway";

    public function __construct() {
        parent::__construct(self::DB_NAME);
    }

}

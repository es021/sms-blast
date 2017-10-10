<?php

class DB_smsblast extends DB{

    const DB_NAME = "csr";
    const TB_USERS = "sb_users";
    const TB_CONTACTS = "sb_contacts";
    const TB_MESSAGES = "sb_messages";
    const TB_LOGS = "sb_logs";

    public function __construct() {
        parent::__construct(self::DB_NAME);
    }

}

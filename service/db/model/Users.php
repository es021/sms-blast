<?php

class Users {

    const TB_NAME = "sb_users";
    const COL_ID = "id";
    const COL_USERNAME = "user_name";
    const COL_PASS = "password";
    const COL_STATUS = "status";
    const COL_ROLE = "role";
    const ROLE_ADMIN = "admin";
    const PASS_SALT = "heythisisrandomsalt";

    //return status and data
    public static function verifyUser($user_name, $pass) {
        $user = self::getFromUserName($user_name);

        if (empty($user)) {
            returnRes(STATUS_NOT_OK, "User $user_name Does Not Exist");
        } else {
            $user = $user[0];
            if (!self::password_verify($pass, $user[self::COL_PASS])) {
                returnRes(STATUS_NOT_OK, "Wrong Password");
            }

            return $user;
        }
    }

    public static function password_verify($user_entered_pass, $the_password) {
        return self::encryptPassword($user_entered_pass) == $the_password;
    }

    public static function createUser($user_name, $pass, $status = "Active") {
        global $db_sms_blast;

        $data = array(
            self::COL_USERNAME => $user_name
            , self::COL_PASS => self::encryptPassword($pass)
            , self::COL_STATUS => $status
        );

        return $db_sms_blast->insert(self::TB_NAME, $data);
    }

    public static function getFromUserName($user_name) {
        $sql = sprintf("SELECT * FROM %s WHERE %s = '%s'"
                , self::TB_NAME
                , self::COL_USERNAME
                , $user_name);

        global $db_sms_blast;
        return $db_sms_blast->exec($sql);
    }

    public static function encryptPassword($pass) {

        $salt = self::PASS_SALT;
        $salt = base64_encode($salt);
        $salt = str_replace('+', '.', $salt);
        $hash = crypt($pass, '$2y$10$' . $salt . '$');

        return $hash;
    }

}

<?php

class SMSGateway {

    const DEV_NUM = "0192114756";
    const TB_NAME = "pier.sms_gateway";
    const COL_ID = "id";
    const COL_TO = "sms_to";
    const COL_MESSAGE = "sms_message";
    const COL_CRT_BY = "crt_by";
    const COL_STATUS = "sms_status";
    const COL_CRT_DT = "crt_dtime";
    const CRT_BY_PREFIX = "smsblast_";
    const STATUS_PRE_SENT = "pending";
    const LIMIT_NUM_PER_MES = 15;

    //this function to properly format the message text
    // to be stored into pier.sms_gateway
    public static function formatMessageToDB($str) {
        $str = str_replace(array('\r\n', '\r', '\n'), PHP_EOL, $str);
        $str = str_replace('"', "'", $str);
        $str = str_replace('\\', '', $str);
        return $str;
    }

    //return array is sms ids from created row in sms_gateway table
    public static function sendSMS($message, $user_id, $to = array()) {
        $message = self::formatMessageToDB($message);

        global $db_pier;

        if (empty($to)) {
            $to = Contacts::getNumbers();
        }

        // as COL_TO char limit is 255,
        // need to divided up the number to be sent to smaller chuck,
        // based on LIMIT_NUM_PER_MES
        $toArr = self::generateToNumberArray($to);

        if (empty($toArr)) {
            return false;
        }

        $insert_id = array();

        foreach ($toArr as $toStr) {
            $data = array(
                self::COL_TO => $toStr
                , self::COL_MESSAGE => $message
                , self::COL_STATUS => self::STATUS_PRE_SENT
                , self::COL_CRT_BY => self::CRT_BY_PREFIX . $user_id
            );

            $sms_id = $db_pier->insert(self::TB_NAME, $data, false);

            if ($sms_id > 0) {
                array_push($insert_id, $sms_id);
            }
        }

        return $insert_id;
    }

   
    public static function generateToNumberArray($to) {

        $toArr = array();
        $batchArr = array();
        $count = 0;

        foreach ($to as $t) {
            $count ++;
            array_push($batchArr, $t);

            if ($count >= self::LIMIT_NUM_PER_MES) {
                array_push($toArr, self::generateToNumberStr($batchArr));
                $count = 0;
                $batchArr = array();
            }
        }

        if (!empty($batchArr)) {
            array_push($toArr, self::generateToNumberStr($batchArr));
        }

        return $toArr;
    }

    public static function generateToNumberStr($to) {
        if (empty($to)) {
            return false;
        }
        $toRet = "";
        foreach ($to as $t) {
            $toRet .= "{$t[Contacts::COL_NUMBER]},";
        }
        return trim($toRet, ",");
    }

}

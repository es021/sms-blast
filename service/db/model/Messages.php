<?php

class Messages {

    const PAGE_LIMIT = 10;
    const TB_NAME = "sb_messages";
    const COL_ID = "id";
    const COL_MESSAGE = "message";
    const COL_STATUS = "status";
    const COL_SMS_ID = "sms_id";
    const COL_CRT_BY = "created_by";
    const COL_UPT_BY = "updated_by";
    const COL_CRT_AT = "created_at";
    const COL_UPT_AT = "updated_at";
    const VAL_STATUS_PENDING = "pending";
    const VAL_STATUS_SENT = "sent";
    const VAL_STATUS_DRAFT = "draft";

    public static function deleteMessage($id) {
        global $db_sms_blast;

        if ($id == "") {
            return false;
        }

        $where = sprintf("%s = '%s'", self::COL_ID, $id);

        return $db_sms_blast->delete(self::TB_NAME, $where);
    }

    public static function updateMessage($id, $data) {
        global $db_sms_blast;

        if ($id == "") {
            return false;
        }

        $where = sprintf("%s = '%s'", self::COL_ID, $id);

        return $db_sms_blast->update(self::TB_NAME, $data, $where);
    }

    public static function getMessage($page = 1, $status = "") {

        global $db_sms_blast;

        $select = "SELECT *";
        $limit = DB::prepareLimit($page, self::PAGE_LIMIT);
        $where = "";
        $order = DB::prepareOrder(array(self::COL_UPT_AT . " DESC"));

        if ($status != "") {
            //$select = DB::prepareSelect(array(self::COL_ID, self::COL_MESSAGE, self::COL_STATUS));
            $where = sprintf(" WHERE %s = '%s' ", self::COL_STATUS, $status);
        }

        $sql = sprintf("$select FROM %s $where $order $limit", self::TB_NAME);
    
        return $db_sms_blast->exec($sql);
    }

    public static function addNewMessage($data) {
        global $db_sms_blast;
        
        if(!isset($data[self::COL_UPT_BY])){
            $data[self::COL_UPT_BY] = "";
        }
        
        //X($db_sms_blast);
        
        $res =  $db_sms_blast->insert(self::TB_NAME, $data);
        //X($res);
        return $res;
    }

}

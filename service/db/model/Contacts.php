<?php

class Contacts {

    const PAGE_LIMIT = 10;
    const TB_NAME = "sb_contacts";
    const COL_ID = "id";
    const COL_NUMBER = "number";
    const COL_NAME = "name";
    const COL_CRT_BY = "created_by";
    const COL_UPT_BY = "updated_by";
    const COL_CRT_AT = "created_at";
    const COL_UPT_AT = "updated_at";

    public static function deleteContact($id) {
        global $db_sms_blast;

        if ($id == "") {
            return false;
        }

        $where = sprintf("%s = '%s'", self::COL_ID, $id);

        return $db_sms_blast->delete(self::TB_NAME, $where);
    }

    public static function updateContact($id, $data) {
        global $db_sms_blast;

        if ($id == "") {
            return false;
        }

        $where = sprintf("%s = '%s'", self::COL_ID, $id);

        return $db_sms_blast->update(self::TB_NAME, $data, $where);
    }

    public static function getNumbers($isDetail = false, $page = 1) {
        global $db_sms_blast;
        if ($isDetail) {
            $select = DB::prepareSelect(array(self::COL_ID, self::COL_NAME, self::COL_NUMBER));
            $order = DB::prepareOrder(array(self::COL_UPT_AT . " DESC"));
            $limit = DB::prepareLimit($page, self::PAGE_LIMIT);
        } else {
            $select = DB::prepareSelect(array(self::COL_NUMBER));
            $limit = "";
            $order = "";
        }
        $sql = sprintf("$select FROM %s $order $limit", self::TB_NAME);
        return $db_sms_blast->exec($sql);
    }

    public static function addNewContact($data) {
        //X($data);
        global $db_sms_blast;
        return $db_sms_blast->insert(self::TB_NAME, $data);
    }

    public static function getNumberCount() {
        global $db_sms_blast;
        $sql = sprintf("SELECT COUNT(*) as count FROM %s", self::TB_NAME);
        $raw = $db_sms_blast->exec($sql);

        if (!empty($raw)) {
            return $raw[0];
        }

        return false;
    }

}

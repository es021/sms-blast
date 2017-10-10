<?php

class DB {

    private $user;
    private $password;
    private $database;
    //Use this connection
    public $conn;

    public function __construct($database) {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->password = DB_PASSWORD;
        $this->database = $database;
        $this->conn = $this->connect();
    }

    private function connect() {
        $conn = mysqli_connect($this->host, $this->user, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysqli_error($conn));
        } else {
            mysqli_select_db($conn, $this->database);
            mysqli_set_charset($conn, "UTF8");
        }

        return $conn;
    }

    //return key value pair
    public function exec($sql) {
        $res = mysqli_query($this->conn, $sql);

        $arr = array();
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $arr[] = $row;
            }
        }

        return $arr;
    }

    // tablename string
    // data -- array of key value pair
    public function insert($table, $data, $escape = true) {
        $keys = "(";
        $values = "(";
        foreach ($data as $k => $d) {
            $keys .= " $k, ";

            if ($escape) {
                $values .= " '" . mysqli_real_escape_string($this->conn, $d) . "', ";
            } else {
                $values .= ' "' . $d . '", ';
            }
        }
        $keys = trim($keys, ", ") . " )";
        $values = trim($values, ", ") . " )";

        $sql = "INSERT INTO $table $keys VALUES $values";
        mysqli_query($this->conn, $sql);

        return $this->conn->insert_id;
    }

    // all param is strings
    // data is key value pair
    public function get($table, $select, $where = "", $extra = "") {

        return $data;
    }

    public function delete($table, $where) {
        if ($where == "") {
            return false;
        }

        $sql = "DELETE FROM $table WHERE $where";

        return mysqli_query($this->conn, $sql);
    }

    //return true or false
    public function update($table, $data, $where) {

        if ($where == "") {
            return false;
        }

        $key_pair = "";
        foreach ($data as $k => $d) {
            $key_pair .= " $k = ";
            $key_pair .= " '" . mysqli_real_escape_string($this->conn, $d) . "', ";
        }
        $key_pair = trim($key_pair, ", ");

        $sql = "UPDATE $table SET $key_pair WHERE $where";
        //X($sql);
        return mysqli_query($this->conn, $sql);
    }

    public function escapeInputDeep($data) {
        foreach ($data as $k => $d) {
            if (is_array($d)) {
                $data[$k] = $this->escapeInputDeep($d);
            } else {
                $data[$k] = mysqli_real_escape_string($this->conn, $d);
            }
        }

        return $data;
    }

    /** STATIC FUNCTION ****************************** */
    public static function prepareOrder($or = array()) {
        $return = "";
        foreach ($or as $o) {
            $return .= $o . ", ";
        }
        return "ORDER BY " . trim($return, ", ");
    }

    public static function prepareSelect($sel = array()) {
        $return = "";
        foreach ($sel as $s) {
            $return .= $s . ", ";
        }

        return "SELECT " . trim($return, ", ");
    }

    public static function prepareLimit($page, $offset) {
        $start = ($page - 1) * $offset;
        return "LIMIT $start,$offset";
    }

}

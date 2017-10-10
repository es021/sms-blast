<?php

include_once '../config.php';
include_once '../functions.php';

//allow all origin
header("Access-Control-Allow-Origin: *");

// Allow Authorization to be sent by client
header("Access-Control-Allow-Headers: Authorization");


//for NGINX
if (!function_exists('getallheaders')) {

    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

}


/* ------ CONSTANT Definition ------------------------------------------------- */
DEFINE('SERVICE_ROOT', str_replace('\\', '/', __DIR__));
DEFINE('SERVICE_SRC', SERVICE_ROOT . "/src");
DEFINE('SERVICE_DB', SERVICE_ROOT . "/DB");
DEFINE('SERVICE_VENDOR', SERVICE_ROOT . "/vendor");
DEFINE("STATUS_OK", "OK");
DEFINE("STATUS_NOT_OK", "NOT_OK");

include_once SERVICE_SRC . "/MyJWT.php";


/* ------ Function Definition ------------------------------------------------- */

function strToHtml($str) {
    $str = str_replace(array('\r\n', '\r', '\n'), '<br />', $str);
    $str = str_replace('\\', '', $str);
    return $str;
}

function strToHtmlArray($arr) {
    if (is_array($arr)) {
        foreach ($arr as $k => $d) {
            $arr [$k] = strToHtml($d);
        }
    } else {
        $arr [$k] = strToHtml($d);
    }

    return $arr;
}

function returnResEscape($status, $data, $extra = null) {
    if (is_array($data)) {
        foreach ($data as $k => $d) {
            $data [$k] = strToHtmlArray($d);
        }
    }

    returnRes($status, $data, $extra);
}

function returnRes($status, $data, $extra = null) {
    $res = array();
    $res["status"] = $status;

    $res["data"] = $data;
    if ($extra != null) {
        $res["extra"] = $extra;
    }
    echo json_encode($res);
    exit();
}

function filterPost() {
    if (!isset($_POST["action"])) {
        returnRes(STATUS_NOT_OK, "Action not specify");
    }


    /*
      if (!isset($_POST["credential"])) {
      returnRes(STATUS_NOT_OK, "Credential not specify");
      }

      if (!checkCredential($_POST["credential"])) {
      returnRes(STATUS_NOT_OK, "You does not have permission for this request");
      }

     */
}

function authenticateRequest() {
    try {

        $headers = getallheaders();
        $MyJWT = new MyJWT();

        if (isset($headers["Authorization"])) {
            return $MyJWT->parseToken($headers["Authorization"]);
        } else {
            returnRes(STATUS_NOT_OK, "Not Authorized");
        }
    } catch (Exception $err) {
        //$err->getMessage();
        returnRes(STATUS_NOT_OK, "Authorization Token Invalid. Please logout and login back to renew Authorization Token");
    }
}

function updateData($table, $id, $updateData) {
    switch ($table) {
        case Contacts::TB_NAME:
            if (Contacts::updateContact($id, $updateData)) {
                returnRes(STATUS_OK, "Contact successfully updated");
            } else {
                returnRes(STATUS_NOT_OK, "Unable to update contact");
            }
            break;
        case Messages::TB_NAME:
            if (Messages::updateMessage($id, $updateData)) {
                returnRes(STATUS_OK, "Message successfully updated");
            } else {
                returnRes(STATUS_NOT_OK, "Unable to update message");
            }
            break;
    }
}

function deleteData($table, $id) {
    switch ($table) {
        case Contacts::TB_NAME:
            if (Contacts::deleteContact($id)) {
                returnRes(STATUS_OK, "Contact successfully deleted");
            } else {
                returnRes(STATUS_NOT_OK, "Unable to delete contact");
            }
            break;
        case Messages::TB_NAME:
            if (Messages::deleteMessage($id)) {
                returnRes(STATUS_OK, "Message successfully deleted");
            } else {
                returnRes(STATUS_NOT_OK, "Unable to delete message");
            }
            break;
    }
}

function insertData($table, $data) {
    switch ($table) {
        case Contacts::TB_NAME:
            $insert_id = Contacts::addNewContact($data);
            if ($insert_id) {
                returnRes(STATUS_OK, $insert_id);
            } else {
                returnRes(STATUS_NOT_OK, "Unable to create new contact");
            }
            break;
        case Messages::TB_NAME:

            $insert_id = Messages::addNewMessage($data);
            if ($insert_id) {
                returnRes(STATUS_OK, $insert_id);
            } else {
                returnRes(STATUS_NOT_OK, "Unable to store message into database");
            }
            break;
    }
}

function configDB(&$db_pier, &$db_sms_blast) {
    include_once './db/DB.php';
    include_once './db/DB_pier.php';
    include_once './db/DB_smsblast.php';

    $db_pier = new DB_pier();
    $db_sms_blast = new DB_smsblast();
}

/* ------ Code Start Here ------------------------------------------------- */

function Main() {
    filterPost();

    //if not login request, need to authenticate with secret
    if ($_POST["action"] != "login") {
        $token = authenticateRequest();
    }

    include_once './db/model/Users.php';
    include_once './db/model/SMSGateway.php';
    include_once './db/model/Contacts.php';
    include_once './db/model/Messages.php';

    if (isset($_POST["not_jQuery"])) {
        $_POST = json_decode($_POST["not_jQuery"], true);
    }

    global $db_pier;
    global $db_sms_blast;
    configDB($db_pier, $db_sms_blast);

    $_POST = $db_pier->escapeInputDeep($_POST);


    switch ($_POST["action"]) {
        case "login":
            $data = $_POST["data"];
            $user = Users::verifyUser($data["user_name"], $data["pass"]);

            $MyJWT = new MyJWT();
            $jwt = $MyJWT->createTokenFromLogin($user[Users::COL_USERNAME], $user[Users::COL_ROLE]);

            returnRes(STATUS_OK, $jwt);
            break;

        case "send_message":

            $data = $_POST["data"];

            $sms_ids = SMSGateway::sendSMS($data["message"], $data["user_name"]);
            
            if (empty($sms_ids)) {
                $return_data = "Failed to send message";
                returnRes(STATUS_NOT_OK, $return_data);
            } else {
                $return_data = array("message" => "Message Successfully Sent",
                    Messages::COL_SMS_ID => json_encode($sms_ids));
                returnRes(STATUS_OK, $return_data);
            }

            break;

        case "insert" :
            $data = $_POST["data"];
            $table = $data["table"];
            $insertData = $data["insertData"];

            //return data handle in this function
            insertData($table, $insertData);

            break;

        case "update":
            $data = $_POST["data"];
            $table = $data["table"];
            $id = $data["id"];
            $updateData = $data["updateData"];
            updateData($table, $id, $updateData);
            break;

        case 'delete':
            $data = $_POST["data"];
            $table = $data["table"];
            $id = $data["id"];
            deleteData($table, $id);
            break;

        case "get_message":
            $data = $_POST["data"];
            $raw = Messages::getMessage($data["page"], $data["status"]);
            $extra = null;
            if ($data["page"] == 1) {
                $extra = array("page_limit" => Messages::PAGE_LIMIT);
            }


            returnResEscape(STATUS_OK, $raw, $extra);
            break;

        case "get_contacts":
            $data = $_POST["data"];
            $raw = Contacts::getNumbers(true, $data["page"]);
            $extra = null;
            if ($data["page"] == 1) {
                $extra = array();
                $extra["page_limit"] = Contacts::PAGE_LIMIT;
                $extra["total_row"] = (int) Contacts::getNumberCount()["count"];
            }
            returnResEscape(STATUS_OK, $raw, $extra);
            break;

        case "get_number_count":
            $data = Contacts::getNumberCount();
            if ($data) {
                returnRes(STATUS_OK, $data);
            } else {
                returnRes(STATUS_NOT_OK, "Failed to get number count");
            }

            break;

        case "delete_contact":
            $data = $_POST["data"];
            $raw = Contacts::deleteContact($data[Contacts::COL_ID]);
            if ($raw) {
                returnRes(STATUS_OK, $data);
            } else {
                returnRes(STATUS_NOT_OK, "Failed to delete contact");
            }

            break;

        default:
            returnRes(STATUS_NOT_OK, "Action not valid");
            break;
    }
}

//this function will exit and echo result
Main();


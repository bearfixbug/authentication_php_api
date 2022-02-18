<?php
    require_once('./config.php');


    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $token = $_POST['token'];

        $query = "select * from tbl_user where token = ?";
        $stmt = $db->prepare($query);
        if($stmt->execute([
            $token
        ])) {
            $num = $stmt->rowCount();
            if($num == 1) {
                $query = 'update tbl_user set token = ? where token = ? ';
                $stmt = $db->prepare($query);
                if($stmt->execute([
                    null, $token
                ])) {
                    $object = new stdClass();
                    $object->RespCode = 200;
                    $object->RespMessage = 'good';
                }
                else {
                    $object = new stdClass();
                    $object->RespCode = 400;
                    $object->RespMessage = 'bad';
                    $object->Log = 2;
                }
            }
            else {
                $object = new stdClass();
                $object->RespCode = 400;
                $object->RespMessage = 'bad';
                $object->Log = 3;
            }
        }
        else {
            $object = new stdClass();
            $object->RespCode = 400;
            $object->RespMessage = 'bad';
            $object->Log = 1;
        }
        echo json_encode($object);
        http_response_code(200);
    }
    else {
        http_response_code(405);
    }
?>
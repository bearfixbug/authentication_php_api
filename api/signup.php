<?php
    require_once('./config.php');
    date_default_timezone_set("Asia/Bangkok");

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];

        $salt = generateRandomString(10);
        $newpassword = md5($password . $salt);

        $now = date("Y-m-d H:i:s"); 
        $token = md5(generateRandomString(10) . $now);

        $query = "insert into tbl_user (username, password, salt, token, fullname, email) values (?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        if($stmt->execute([
            $username, $newpassword, $salt, $token, $fullname, $email
        ])) {
            $object = new stdClass();
            $object->RespCode = 200;
            $object->RespMessage = 'good';
            $object->Token = $token;
            $object->Fullname = $fullname;
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
<?php
    require_once 'Controller.php';
    $db = new Controller();
    
    $response = array("error" => FALSE);

    if (isset($_POST['data'])) {

        $data = $_POST['data'];
        $message_digest = md5($data);
     
        $store = $db->verification($message_digest);
            if ($store == "success") {
                $response["status"] = 200;
                $response["message"] = "File is fully original";
                $response["message_digest"] = $message_digest;
                echo json_encode($response);
            } else {
                $response["error"] = TRUE;
                $response["status"] = 200;
                $response["message"] = "File not original";
                echo json_encode($response);
            }
    } else {
        $response["error"] = TRUE;
        $response["message"] = "Terjadi Kesalahan";
        echo json_encode($response);
    }
?>
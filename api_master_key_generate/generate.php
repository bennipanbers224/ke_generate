<?php
    require_once 'Controller.php';
    $db = new Controller();
    
    $response = array("error" => FALSE);

     $conf = array(
        "digest_alg"=>'md5',
        "private_key_bits" => 512,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
     );
    
     $keyPair = openssl_pkey_new($conf);

     openssl_pkey_export($keyPair, $privKey, null, $conf);

     $publicKey = openssl_pkey_get_details($keyPair)['key'];
    
    if (isset($_POST['data']) && isset($_POST['file_id'])) {

        openssl_private_encrypt($data, $encryted, $privKey);
        $encrypt = bin2hex($encryted);

        $data = $_POST['data'];
        $file_id = $_POST['file_id'];
        $publicKey = $publicKey;
        $privateKey = $privKey;
        $message_digest = md5($data);
        $signature = $encrypt;
     
        $store = $db->simpanData($publicKey, $privateKey, $message_digest, $signature, $file_id);
            if ($store) {
                $response["status"] = 200;
                $response["publicKey"] = $publicKey;
                $response["privateKey"] = $privateKey;
                $response["message_digest"] = $message_digest;
                $response["signature"] = $signature;
                $response["file_id"] = $file_id;
                
                echo json_encode($response);
            } else {
                $response["error"] = TRUE;
                $response["error_msg"] = "Terjadi kesalahan saat menyimpan data";
                echo json_encode($response);
            }
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Terjadi Kesalahan";
        echo json_encode($response);
    }
?>
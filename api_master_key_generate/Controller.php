<?php

class Controller {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'Database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }
 
    public function simpanData($publicKey, $privateKey, $message_digest, $signature, $nim) { 
        $stmt = $this->conn->prepare("INSERT INTO key_generate(`private_key`, `public_key`, `message_digest`, `signature`, `nim`) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $privateKey, $publicKey, $message_digest, $signature, $nim);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function verification($message_digest){
        $stmt = $this->conn->prepare("SELECT * FROM key_generate WHERE message_digest = ?");
        $stmt->bind_param("s", $message_digest);

        if($stmt->execute()){
            $file_data = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if($file_data != NULL){
                if($message_digest == $file_data["message_digest"]){    
                    return $file_data;
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }
        else{
            return NULL;
        }
    }
 
}
 
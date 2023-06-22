<?php

class Controller {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'Database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }
 
    public function simpanData($publicKey, $privateKey, $message_digest, $signature, $file_id) { 
        $stmt = $this->conn->prepare("INSERT INTO key_generate(`private_key`, `public_key`, `message_digest`, `signature`, `file_id`) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $privateKey, $publicKey, $message_digest, $signature, $file_id);
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

            if($message_digest == $file_data["message_digest"]){
                return "success";
            }
            else{
                return "error";
            }

        }
        else{
            return "error";
        }
    }
 
}
 
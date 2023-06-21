<?php
    class Database{

        function connect(){
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbName = "system_key";
            
            // Create connection
            $conn = mysqli_connect($servername, $username, $password, $dbName);
            
            // Check connection

            return $conn;
        }
}

 
?>
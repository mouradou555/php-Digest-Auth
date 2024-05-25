<?php 
    function getDatabaseConnection() {
            $conn = new mysqli("localhost", "root", "", "tslh_db");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            return $conn;
        }
    }
?>

<?php
$servername = "localhost";
$username = "username_db_nya";
$password = "password_db_nya";

try {
        $conn = new PDO("mysql:host=$servername;dbname=nama_db_nya", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Sukses";
    }
catch(PDOException $e)
    {
        //echo "Connection failed: " . $e->getMessage();
    }
?>
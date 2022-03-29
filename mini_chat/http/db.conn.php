<?php
#server name
$sName = "localhost";
# user name
$uName = "root";
# password
$pass= "";

#database name
$db_name = "mini_chat";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name;charset=utf8", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur:' . $e->getMessage());
}
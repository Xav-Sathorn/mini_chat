<?php
session_start();

#vérifie si l'utilisateur est en ligne
if (isset($_SESSION['username'])) {


        #connection bdd
        include '../http/db.conn.php';

        #get data from XHR request and store them in var
        $message = $_POST['message'];
        $to_id = $_POST['to_id'];

        #get the logged in user's username from the SESSION
        $from_id = $_SESSION['user_id'];
        $sql = "INSERT INTO chats (from_id, to_id, message) VALUES (?,?,?)";

        $stmt = $conn->prepare($sql);
        $rest = $stmt->execute([$from_id, $to_id, $message]);


} else {
    header("location: ../../index.php");
    exit;
}

print_r ($chat);
$chat = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);
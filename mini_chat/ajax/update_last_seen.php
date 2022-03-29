<?php
session_start();

#vérifie si l'utilisateur est en ligne
if(isset($_SESSION['username'])){

    #connection bdd
    include '../http/db.conn.php';

    #récupère le pseudo utilisateur dans SESSION
    $id = $_SESSION['user_id'];
    $sql = "UPDATE users SET last_seen = NOW()
    WHERE user_id =?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);


    }else {
        header("location: ../../index.php");
}


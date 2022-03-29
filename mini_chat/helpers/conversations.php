<?php

function getConversation($user_id, $conn){
    # reçoit toutes les conversations pour l'actuel (connecté) utilisateur
    $sql = "SELECT * FROM conversations 
    WHERE user_1=? OR user_2=?
    ORDER BY conversation_id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt ->execute([$user_id, $user_id]);
    

    if ($stmt->rowCount() > 0) {
        $conversations = $stmt->fetchAll();

        #création d'un tableau vide pour stocker la conversation de l'utilisateur
        $user_data = [];
        #bloucle à travers les conversations
        foreach($conversations as $conversation){
            #si conversations de la ligne user_1 sont égale à user_id
            if ($conversation['user_1'] == $user_id) {
                $sql2 = "SELECT name, username, pp, last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conversation ['user_2']]);
            }else {
                $sql2 = "SELECT name, username, pp, last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conversation ['user_1']]);
            }
            $allConversations = $stmt2->fetchAll();
            #pousse les données dans le tableau
            array_push($user_data, $allConversations[0]);
        }

        return $user_data;
        
    }else {
        $conversations = [];
        return $conversations;
    }
}
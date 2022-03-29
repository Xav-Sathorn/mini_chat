<?php
session_start();
    # vérification si pseudo et mot de passe ont été pris en compte
if(isset($_POST['username']) &&
    isset($_POST['password'])){

    # connection bdd
    include '../http/db.conn.php';

    //RECUPERE DONNEES DE LA REQUETE POST ET LES ENREGISTRE COMME VARIABLE
    $username= $_POST['username'];
    $password= $_POST['password'];
    
    # validation formulaire
    if(empty($username)){
        #message d'erreur
        $em = "Pseudo requis";
        header("location: ../index.php?error=$em");
    }else if (empty($password)){
        #message erreur
        $em = "Mot de passe requis";
        header("location: ../index.php?error=$em");
    }else {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        # si le pseudo existe
        if($stmt->rowCount() === 1){
        # aller chercher les données de l'utilisateur
        $user = $stmt->fetch();

        # si les deux pseudo sont trictement égaux
        if ($user['username'] === $username){
        
        # vérification encryptage du mor de passe
        if(password_verify($password, $user['password'])){
            # authentification réussie
            # création de session
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];

            #redirigine vers'home.php'
            header("location: ../home.php");

        }else{
            $em = "Pseudo ou mot de passe incorrect";
            header("location: ../index.php?error=$em"); 
        }

        }else{    
            $em = "Pseudo ou mot de passe incorrect";
            header("location: ../index.php?error=$em");    
        }
        }
    }

}else{
    header("location: ../../index.php");
    exit;
}
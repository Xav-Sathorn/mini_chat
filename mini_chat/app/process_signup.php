<?php

//VERIF SI NOM, PSEUDO & MDP SONT ENVOYES

if (isset($_POST['name']) &&
    isset($_POST['username']) &&
    isset($_POST['password'])
){
    # connection bdd
    include '../http/db.conn.php';

    //RECUPERE DONNEES DE LA REAQUETE POST ET LES ENREGISTRE COMME VARIABLE
    $name= $_POST['name'];
    $username= $_POST['username'];
    $password= $_POST['password'];

    //GENERE FORMAT DONNEES URL
    $data  = 'name ='.$name .'&username='.$username;

    //SIMPLE VERIFICATION DU FORMULAIRE
    if (empty($name)) {
        $em = "Nom requis";
        //REDIRIGE VERS 'signup.php' ET ENVOIE DU MESSAGE D'ERREUR
        
        header("location: ../signup.php?error=$em");
        exit;
        
    }else if(empty($username)){
        $em = "Pseudo requis";
        header("location: ../signup.php?error=$em&$data");
        exit;
    }else if(empty($password)){
        $em = "Mot de passe requis";
        header("location: ../signup.php?error=$em&$data");
        exit;
    }else{
        // verifier si la BDD est bien prise en compte
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        if($stmt->rowCount() > 0){
            $em = "Le Pseudo ($username) est déjà pris";
            header("location: ../signup.php?error=$em&$data");
            exit;
        }else{
            #Téléchargement de la photo de profile
            if(isset($_FILES['pp'])) {
                $img_name = $_FILES['pp'] ['name'];
                $tmp_name = $_FILES['pp'] ['tmp_name'];
                $error = $_FILES['pp'] ['error'];
                    #Si aucune erreur de téléchargement
                    if($error === 0){
                    # Ajout des extension de l'image dans la variable
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    #Convertie l'entension de l'image en petite case et la store dans variable
                    $img_ex_lc = strtolower($img_ex);
                    #Insertion dans tableau qui permet le storage du téléchargement de l'extension de l'image
                    $allowed_exs = array('jpg', 'jpeg', 'png');
                    #vérifie si l'extension de l'image est présente dans le tableau $allowed_exs
                    if (in_array($img_ex_lc, $allowed_exs)){
                    #renommer l'image avec pseudo de l'utilisateur comme : username.$img_ex_lc
                    $new_img_name = $username. '.' .$img_ex_lc;
                    #Insertion du chemin de téléchargement dans le dossier racine
                    $img_upload_path = '../uploads/'.$new_img_name;
                    #déplace image téléchargée dans le fichier uploads
                    move_uploaded_file($tmp_name, $img_upload_path);
                    }else{
                        $em = "Le format de l'image n'est pris en compte!";
                        header("location: ../signup.php?error=$em&$data");
                        exit;
                    }
                }else{
                    $em = "Sélectionnez une image de profil!";
                    header("location: ../signup.php?error=$em&$data");
                    exit;
                }
            }
            #hachage du mot de passe
            $password = password_hash($password, PASSWORD_DEFAULT);
            #is l'utilisateur télécharge la photo de profil
            if( isset( $new_img_name)){
                #inserion des données dans la base de donnée
                $sql = "INSERT INTO users (name, username, password, pp) Value (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password, $new_img_name]);
            }else {
                $sql = "INSERT INTO users (name, username, password) Value (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password]);
            }
            #message de succès
            $sm = "Création compte confirmée!";
            # redirige sur la page index et passe message de succès
            header("location: ../index.php?success=$sm");
            exit;
        }
    }
}else{
    header("location: ../signup.php");
    exit;
}


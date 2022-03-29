<?php

session_start();

#vérifie si l'utilisateur est en ligne
if (isset($_SESSION['username'])) {
    #vérifie que la clé est soumise
    if (isset($_POST['key'])) {
        #connection à la base de donnée
        include '../http/db.conn.php';

        #création d'une simple recherche d'algorithmes
        $key = "%{$_POST['key']}%";

        $sql = "SELECT * FROM users WHERE username LIKE ? OR name LIKE ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$key, $key]);

        if ($stmt->rowCount() > 0) { 
            $users=$stmt->fetchAll();

            foreach($users as $user){
                if ($user['user_id'] == $_SESSION['user_id']) continue;
            ?>
            <li class="list-group-item">
                            <a href="chat.php?user=<?= $user['username'] ?>" class="d-flex justify-content-between align-items-center p-2">
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?= $user['pp'] ?>" class="w-10 rounded-circle">

                                    <h3 class="fs-xs m-2"><?= $user['name'] ?></h3>
                                </div>
                            </a>
                        </li>
        <?php  } } else { ?>
            <div class="alert alert-info text-center">
                <i class="fa fa-comments d-block fs-big"></i>
                L'utilisateur "<?=htmlspecialchars($_POST['key'])?>" est introuvable!
            </div>
<?php }
    }
} else {
    header("location: ../../index.php");
}

/* 1.32 */

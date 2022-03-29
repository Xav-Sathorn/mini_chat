<?php
session_start();
if (isset($_SESSION['username'])) {
    # connection à la base de donnée
    include 'http/db.conn.php';

    include 'helpers/user.php';
    include 'helpers/conversations.php';
    include 'helpers/timeAgo.php';

    # récupère donnée de getUser de user.php
    $user = getUser($_SESSION['username'], $conn);

    # récupère la conversation des utilisateurs
    $conversations = getConversation($user['user_id'], $conn);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mini Chat | Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




    </head>

    <body class="d-flex justify-content-center align-items-center vh-100">

        <div class="w-400 p-2 rounded shadow ">
            <div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="uploads/<?= $user['pp'] ?>" class="w-25 rounded-circle">
                    <h3 class="fs-xs m-2"><?= $user['name'] ?></h3>
                </div>
                <a href="logout.php" class="btn btn-dark">Déconnexion</a>
            </div>
            <div class="input-group mb-3">
                <input type="text" placeholder="Recherche..." id="searchText" class="form-control">
                <button class="btn btn-primary" id=searchBtn><i class="fa fa-search"></i></button>
            </div>
            <ul id="chatList" class="list-group mvh-50 overflow-auto">

                <?php if (!empty($conversations)) { ?>
                    <?php

                    foreach ($conversations as $conversation) { ?>
                        <li class="list-group-item">
                            <a href="chat.php?user=<?= $conversation['username'] ?>
                            " class="d-flex justify-content-between align-items-center p-2">
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?= $conversation['pp'] ?>" 
                                    class="w-10 rounded-circle">
                                    <h3 class="fs-xs m-2"><?= $conversation['name'] ?></h3>
                                </div>
                                <?php if (last_seen($conversation['last_seen']) == "Active") { ?>
                                    <div title="online">
                                        <div class="online"></div>
                                    </div>
                                <?php } ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-info text-center">
                        <i class="fa fa-comments d-block fs-big"></i> Pas de messages, commencez une conversation!
                    </div>
                <?php } ?>
            </ul>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        

        <script src="./index.js"></script>

    </body>

    </html>
<?php
} else {
    header("location: index.php");
    exit;
}
?>
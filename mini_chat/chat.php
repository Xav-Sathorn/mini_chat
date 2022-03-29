<?php
session_start();
if (isset($_SESSION['username'])) {
    # connection à la base de donnée
    include 'http/db.conn.php';

    include 'helpers/user.php';
    include 'helpers/chat.php';

    include 'helpers/timeAgo.php';

    if (!isset($_GET['user'])) {
        header("location: home.php");
        exit;
    }
    # récupère donnée de getUser de user.php
    $chatWith = getUser($_GET['user'], $conn);

    if (empty($chatWith)) {
        header("location: home.php");
        exit;
    }

    $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mini Chat | App</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="w-400 shadow p-4 rounded">
            <a href="home.php" class="fs-4 link-dark">&#8592;</a>
            <div class="d-flex align-items-center">
                <img src="uploads/<?= $chatWith['pp'] ?>" class="w-15 rounded-circle">
                <h3 class="display-4 fs-sm m-2"><?= $chatWith['name'] ?> <br>
                    <div class="d-flex align-items-center" title="online">
                        <?php
                        if (last_seen($chatWith['last_seen']) == "Active") {
                        ?>
                            <div class="online"></div>
                            <small class="d-block p1">Online</small>
                        <?php } else { ?>
                            <div class="online"></div>
                            <small class="d-block p1">
                                <?=$chatWith['last_seen']?>
                            </small>
                        <?php } ?>
                    </div>
                </h3>
            </div>
            <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
                <?php
                    if (!empty($chats)){
                        foreach ($chats as $chat){
                            if($chat['from_id'] == $_SESSION['user_id'])
                        { ?>
                            <p class="rtext align-self-end border rounded p-2 mb-1"><?= $chat['message'] ?>
                                <small class="d-block"><?= $chat['created_at'] ?></small>
                            </p>
                        <?php } else{ ?>
                                <p class="ltext border rounded p-2 mb-1"><?= $chat['message'] ?>
                                    <small class="d-block"><?= $chat['created_at'] ?></small>
                                </p>
                            <?php }
                    }
                } else{ ?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-comments d-block fs-big"></i> Pas de messages, commencez une conversation!
                        </div>
                    <?php }?>
            </div>
            <div class="input-group mb-3">
                <textarea cols="3" 
                id= "message" class="form-control"></textarea>
                <button class="btn btn-primary" 
                id= "sendBtn" data-user-id = "<?=$chatWith['user_id']?>">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script>
            const scrollDown = function(){
    let chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
}

scrollDown();

$(document).ready(function(){
    $("#sendBtn").on('click', function(){
        message = $("#message").val();
        if(message == "") return;

        $.post("ajax/insert.php",
        {
            message: message,
            to_id: <?=$chatWith['user_id']?>
        },
        function(data, status){
            $("#message").val("");
            $("#chatBox").append(data);
            scrollDown();
        });
    });

//rafraichissement automatique de la page/ reload
let fetchData = function(){
        $.post("ajax/getMessage.php",
        {
            id_2: <?=$chatWith['user_id']?>
        },
        function(data, status){
            $("#chatBox") .append(data);
            if(data != "") scrolldown();
        });
    }

    fetchData();
    /**maj auto last_seen toutes les 0.5secs */
    setInterval(fetchData, 500);

});

        </script>
    </body>

    </html>
<?php
} else {
    header("location: index.php");
    exit;
}
?>
<?php
session_start();
if(!isset($_SESSION['username'])){
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Chat | Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">

</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 p-5 shadow rounded">
        <form method="POST" action=./app/process_signup.php enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img src="img/logo.png" alt="logo" class="w-50">
                <h3 class="display-4 fs-1 text-center">Enregistrement</h3>
            </div>

            <?php if (isset($_GET['error'])){?>
            <div class="alert alert-warning" role="alert">
            <?php echo htmlspecialchars($_GET['error']);?>
            </div>
            <?php } 
            
                if(isset($_GET['name'])) {
                    $name = $_GET['name'];
                }else $name = '';

                if(isset($_GET['username'])) {
                    $username = $_GET['username'];
                }else $username = '';
            ?>
            
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="name" value="<?=$name?>" class="form-control"> 
            </div>
            <div class="mb-3">
                <label class="form-label">Pseudo</label>
                <input type="text" name="username" value="<?=$username?>" class="form-control" >
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label class="form-label">Photo de profil</label>
                <input type="file" class="form-control" name="pp">
            </div>

            <button type="submit" class="btn btn-primary">Enregistrement</button>
            <a href="./signup.php">Identification</a>
        </form>
    </div>
</body>

</html>
<?php
}else{
    header("location: home.php");
    exit;
}
?>
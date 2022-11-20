<?php
    session_start();

    if (isset($_GET['logout']) && $_GET['logout']){
        $_SESSION = [];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="/admin/assets/js/main.js"></script>
</head>

<?php 
    if (isset($_SESSION['user'])) {
    echo '<header>
    <img src="' . $_SESSION['user']['photo'] . '" alt="avatar">
    <a href="mailto:' . $_SESSION['user']['email'] . '">' . $_SESSION['user']['email'] . '</a>
    <p>' . $_SESSION['user']['name'] . '</p>
    <a href="?logout=true">Выход</a>
    </header>';
    }
?>

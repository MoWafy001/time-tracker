<?php
    session_start(); 
    if (!$_SESSION['username']) {
        header('Location: index.php');
        exit(401);
    }
    $user = $_SESSION['username'];

    $title = $_POST["title"];
    $currTime = $_POST['currTime'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "time";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "UPDATE time SET end = '$currTime' WHERE user='$user' AND title = '$title' AND end IS NULL;";
    $result = $conn->query($sql);
    
    echo json_encode($result);
?>
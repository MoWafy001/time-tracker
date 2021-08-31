<?php
    session_start(); 
    if (!$_SESSION['username']) {
        header('Location: index.php');
    }
    $user = $_SESSION['username'];

    $title = $_POST["title"];
    $currTime = $_POST['currTime'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "time";

    $conn = new mysqli($servername, $username, $password, $dbname);

    
    $sql = "SELECT * FROM time where user='$user' AND title = '$title' AND end IS NULL AND start >= '$currTime' AND start < '$currTime' + INTERVAL 1 DAY;";
    $result = $conn->query($sql);
    if($result->num_rows<1){
        $sql = "UPDATE time SET end = '".$currTime."' where user='".$user."' AND end IS NULL;";
        $conn->query($sql);
        $sql = "INSERT INTO time(title, start, user) VALUES('".$title."', '".$currTime."', '".$user."');";
        $conn->query($sql);
    };
    
    echo json_encode($currTime);
?>
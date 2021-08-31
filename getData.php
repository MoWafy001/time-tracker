<?php
    session_start(); 
    if (!$_SESSION['username']) {
        header('Location: index.php');
    }
    $user = $_SESSION['username'];
    if(!$user){exit(422);}

    $i = $_GET['prevDay'];
    $curdate = $_GET['curdate'];
    if (!$i) {
        $i=0;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "time";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $FIRST = "(start >= '$curdate'-INTERVAL $i DAY AND start < '$curdate' + INTERVAL 1 DAY -INTERVAL $i DAY)";
    $SECOND = "(end >= '$curdate'-INTERVAL $i DAY AND end < '$curdate' + INTERVAL 1 DAY -INTERVAL $i DAY) OR ($i=0 AND end IS NULL)";
    $THIRD = "start <= '$curdate'-INTERVAL $i DAY AND end IS NULL";
    $FOURTH = "start < '$curdate'-INTERVAL $i DAY AND end > '$curdate'-INTERVAL $i DAY";

    $sql = "SELECT * FROM time where user='$user' AND ($FIRST OR $SECOND OR $THIRD OR $FOURTH) ;";

    $result = $conn->query($sql);
    if ($result) {
        echo json_encode($result->fetch_all());
    }else{
        exit(500);
    }
?>
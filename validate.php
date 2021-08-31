<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "time";

    $data = json_decode(file_get_contents('php://input'), true);
    $user = $data['user'];
    $pass = $data['pass'];
    $login = $data['login'];

    if(preg_match('/"/ ', $user) || preg_match('/"/ ', $pass)){
        echo json_encode(['success'=>false]);
        exit('invlaid');
    }
    // Create connection
    $conn = new mysqli  ($servername, $username, $password, $dbname);

    $sql = "SELECT username FROM users WHERE username = '".$user."' and password = '".$pass."';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($login) {
            $row = $result->fetch_assoc();
            session_start();
            $_SESSION['username']=$row['username'];
            echo json_encode(['success'=>true]);
        }else{
            echo json_encode(['success'=>false]);
        }
    } else {
        if (!$login) {
            $sql = "INSERT INTO users VALUES('".$user."', '".$pass."');";
            if ($conn->query($sql)) {
                session_start();
                $_SESSION['username']=$user;
                echo json_encode(['success'=>true]);
            }else {
                echo json_encode(['success'=>false]);
            }
        }else{
            echo json_encode(['success'=>false]);
        }
    }
    $conn->close();
?>
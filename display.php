<?php

    session_start(); 
    if (!$_SESSION['username']) {
        header('Location: index.php');
    }
    $user = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display</title>
    <link rel="stylesheet" href="res/display.css">
    <script src="res/script.js" defer></script>
    <script>
        var d = 0;
        var currTask = "";
        var tod = new Date();
    </script>
</head>
<body>
    <div id="time">
        <div class="top">
            <h2>Your Day</h2>
            <script>
                document.querySelector('h2').textContent = tod.toDateString();
            </script>
            <div class="line"></div>
            <div class="buttons">
                <button class="p" onclick="update(++d);" >&#8592;</button>
                <button class="f" onclick="update(--d);">&#8594;</button>
            </div>
        </div>
        <div class="main">
            <div class="tw">
                <h5 class="top">12 AM</h5>
                <h5 class="bottom">12 PM</h5>
            </div>
            <div class="tw">
                <h5 class="top">12 PM</h5>
                <h5 class="bottom">12 AM</h5>
            </div>
        </div>
    </div>

    <div id="dash">
        <div class="top-bar">
            <h2>Howdy, <?php echo $user; ?></h2>
            <a href="logout.php">logout</a>
        </div>
        <div class="stuff">
            <div class="input">
                <input type="text" placeholder="What are you doing?" id="rightnow" onkeyup="document.querySelector('.input button').disabled = false;"><button onclick="updateCurrentTask(document.querySelector('.input input').value);this.disabled = true;" disabled>update</button>
            </div>
            <div class="tags"></div>
        </div>
    </div>

</body>
</html>
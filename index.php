<?php
    session_start();
    if ( isset($_SESSION['username']) && $_SESSION['username']) {
        header('Location: display.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Gate</title>
    <style>
        body{
            padding: 0;
            margin: 0;
            background: #111;
            color:#fff;
            font-family: Arial, Helvetica, sans-serif;
            color: #eee;
        }
        form{
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #222;
            padding: 40px;
            border-radius: 5px;
        }
        form h2{
            text-align: center;
            margin: 0;
            padding-bottom: 20px;
            color: #eee;
        }
        input{
            outline: none;
            border: none;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            background: #333;
            color: #aaa;
        }
        label{
            padding: 10px 5px;
            color: #ccc;
        }
        button{
            margin-top: 20px;
            padding: 10px 5px;
            outline: none;
            border:none;
            border-radius: 5px;
            background: #aaa;
            color: #333;
        }
        button:hover{
            background: #eee;
        }
        .register-link{
            margin-top: 10px;
        }
        .chmod{
            color:seagreen;
            cursor: pointer;
        }
        .error{
            border: 1px solid red;
        }
    </style>
</head>
<body>
    <form>
        <h2>Login</h2>
        <label for="username">username</label>
        <input type="text" id="username" placeholder="username" required>
        <label for="password">password</label>
        <input type="password" id="password" placeholder="password" required>
        <button onclick="validate(event);">login</button>
        <span class="register-link">don't have an account? <span class="chmod" onclick="chmod();">register</span></span>
    </form>
    <script>
        var login = true;
        function chmod(){
            if (login) {
                document.querySelector('h2').textContent = 'Register';
                document.querySelector('button').textContent = 'Register';
                document.querySelector('.register-link').innerHTML = 'Register';
                document.querySelector('.register-link').innerHTML = "have an account? <span class='chmod' onclick='chmod();'>login</span>";
            }else{
                document.querySelector('h2').textContent = 'Login';
                document.querySelector('button').textContent = 'Login';
                document.querySelector('.register-link').innerHTML = "don't have an account? <span class='chmod' onclick='chmod();'>register</span>";
            }
            login = !login;
        }
        async function postData(url = '', data = {}) {
            // Default options are marked with *
            const response = await fetch(url, {
                method: 'POST', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                'Content-Type': 'application/json'
                // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify(data) // body data type must match "Content-Type" header
            });
            return response.json(); // parses JSON response into native JavaScript objects
        }

        function error(){
            document.querySelectorAll('input').forEach(inp=>{
                inp.classList.add('error')
            })
        }
        async function validate(event){
            event.preventDefault();
            const user = document.getElementById('username').value;
            const pass = document.getElementById('password').value;
            if (document.querySelector('form').checkValidity() && user.split(" ").length==1 && pass.split(" ").length==1) {
                const data = {'user':user, 'pass':pass, "login":login};
                const res = await postData('validate.php', data);
                if (res.success) {
                    document.location = "display.php";
                }else{
                    error();
                }            
            }else{
                error();
            }
        }
    </script>
</body>
</html>
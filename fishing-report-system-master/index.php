<?php 
    session_start();
    if (isset($_SESSION['login'])) {
        header("Location: main.php");
    }
    // Log out
    if (isset($_GET['action'])){
        if ($_GET['action'] === 'logout'){
            session_destroy();
            unset($_SESSION);
        }
    }
    include("db_connect.php");
    if(isset($_POST['auth'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $hash_pass = hash('sha256', $password);
        $query = mysqli_query($connect, "SELECT id, login, password, email from angler WHERE login = '$login' OR email = '$login' and password = '$hash_pass'") or die (mysqli_error($connect));
        $user_db = mysqli_fetch_assoc($query);
        $isError = True;
        switch(true) {
            case empty($login): 
                echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Enter your login</div>";
                break;
            case empty($password):
                echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Enter your password</div>";
                break;
            case ($user_db['login'] == $login || $user_db['email'] == $login) && $user_db['password'] == $hash_pass:
                $_SESSION['login'] = $user_db['login'];
                $_SESSION['angler_id'] = $user_db['id'];
                $_SESSION['load'] = False;
                $isError = False;
                header("Location: main.php");
            default:
                echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Incorrect login or password</div>";
        }
        if ($isError) {
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    $(".notice").delay(5000).fadeOut("slow");
                })
            </script>
            <?php
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <script src="https://kit.fontawesome.com/20422689a9.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <title>Fishing Report System - Welcome</title>
</head>
<body><form action="index.php" method = "POST" id = "form1">
    <div class="auth">
        <div class="auth-content">
            <table class = "table">
            <tr>
                <td><span class = "field"><i class="fas fa-user" id = "authIcon"></i></td>
                <td><input type="text" name = 'login' maxlength = "24" placeholder = "Your login or email..."></span></td>
            </tr>
            <tr>
                <td><span class="field"><i class="fas fa-key" id = "authIcon"></i></td>
                <td><input type="password" name = "password" placeholder = "Password" maxlength = "30"></span></td>
            </tr>
            <tr>
                <td>
                    <button type = "submit" name = "auth" form = "form1" class = "authBtn">Log In<i class="fas fa-arrow-right"></i></button>
                    <td><a href = "register.php"><span class = "rText">or register your account now!</span></a></td>
                </td>
            </tr>
            </table>
        </div>
    </div></form>
</body>
</html>
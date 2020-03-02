<?php 
    include "db_connect.php";
    if (isset($_POST['submit'])) {
        $login = $_POST['login'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $gender = !empty($_POST['gender']) ? $_POST['gender'] : '';
        $location = $_POST['location'];
        $password = $_POST['password'];
        $confPassword = $_POST['confPassword'];
        $user = mysqli_query($connect, "SELECT * FROM angler WHERE login = '$login' OR email = '$email' LIMIT 1");
        $user_db = mysqli_fetch_assoc($user);
        $isError = True;

        if ($user_db['login'] === $_POST['login']) {
            echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>A user with such a nickname already exists</div>";
        }
        else if ($user_db['email'] === $_POST['email']) {
            echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>A user with such a email already exists</div>";
        }
        else {
            switch(true) {
                case empty($login) || empty($firstname) 
                || empty($lastname)  || empty($email) 
                || empty($gender) || empty($location)
                || empty ($password) || empty($confPassword):
                    echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Fill in all the fields</div>";
                    break;
                case $password != $confPassword:
                    echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Passwords do not match</div>";
                    break;
                case strlen($login) > 24 || strlen($login) < 5:
                    echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Maximum login length - 24 symbols. Minimum - 5 symb.</div>";
                    break;
                case strlen($password) > 30 || strlen($password) < 6:
                    echo "<div class = 'notice'><i class='fas fa-exclamation-triangle' id = 'noticeIcon'></i>Maximum password length - 30 symbols. Minimum - 6 symb.</div>";
                    break;
                default:
                    $isError = False;
                    $query = mysqli_query($connect, "INSERT INTO angler (login, firstname, lastname, email, gender, location,
                    password) VALUES ('$login','$firstname','$lastname','$email','$gender','$location','".hash('sha256', $password)."')") or die (mysqli_error($connect));
                    header("Location: index.php");
                    sleep(1);
            }
        }
        if($isError) {
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles/reg-style.css">
    <script src="https://kit.fontawesome.com/20422689a9.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Dosis&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <title>Fishing report system - registration</title>
</head>
<body>
    <form action="register.php" method = "POST">
        <table>
        <div class="regBlock">
            <div class="reg-content">
                <span class="text-reg">Registration</span>
                <hr id = "line">
                <span class="rText">Your login:<input type="text" maxlength = "24" name = "login"></span>
                <span class="rText">Firstname:<input type="text" maxlength = "20" name = "firstname"></span>
                <span class="rText">Lastname:<input type="text" maxlength = "20" name = "lastname"></span>
                <span class="rText">Email:<input type="text" maxlength = "30" name = "email"></span>
                <span class="rText">Select your gender:
                    <input type="radio" id = "choice1" name = "gender" value = "male"><label for="choice1" class = "choice">Male</label>
                    <input type="radio" id = "choice2" name = "gender" value = "female"><label for="choice2" class = "choice">Female</label></span>
                <span class="rText">Location:<input type="text" maxlength = "20" name = "location"></span>
                <span class="rText">Password:<input type="password" maxlength = "30" name = "password"></span>
                <span class="rText">Confirm password:<input type="password" maxlength = "24" name = "confPassword"></span>
                <hr id = "line">
                <input type = "submit" value = "REGISTRATION" name = "submit" class = "rBtn">
            </div>
        </div>
    </form></table>
</body>
</html>
<script>
        function close(){
            document.addEventListener("DOMContentLoaded", function() {
            $(".success").delay(3000).fadeOut("slow");
                })
            }            
        </script> 
<?php
        session_start();
        if (isset($_GET['msg'])){
            echo "<div class = 'success'>New story was published</div>";
            echo "<script>close();</script>";
        }
        if (!isset($_SESSION['login'])) {
            header("Location: index.php");
        }
        include "db_connect.php";
        // Session data
        switch(true) {
            case (!isset($_SESSION['login'])): header("Location: index.php");
            case ($_SESSION['load'] == False):
                $_SESSION['load'] = True;
                echo "<div class = 'success'>Welcome back, " . $_SESSION['login'] . "</div>";
                echo "<script>close();</script>";
        }
        //
        if (isset($_POST['submit'])) {
            $angler = $_SESSION['angler_id'];
            $weather = $_POST['weather'];
            $place = $_POST['cPlace'];
            $sTime = $_POST['startTime'] . ":00";
            $eTime = $_POST['endTime']. ":00";
            $fDate = $_POST['fishingDate'];
            $activity = $_POST['activity'];
            $bZones = $_POST['bestZones'];
            $tFish = $_POST['totalFish'];
            $comment = $_POST['comment'];
            switch(true) {
                case empty($angler) || empty($place) 
                || empty($sTime)  || empty($eTime) 
                || empty($activity) || empty($bZones)
                || empty ($tFish) || empty($comment):
                null;
                default:
                    echo $sTime;
                    $query = mysqli_query($connect, "INSERT INTO story (angler_id, place_id, weather, start_time, end_time, date, activity,
                    best_zones, total_fish, comment) VALUES ('$angler','$place','$weather','$sTime','$eTime','$fDate','$activity','$bZones','$tFish','$comment')");
                    header("Location: main.php?msg=new_created");
            }
        }
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="styles/main-styles.css">
    <script src="https://kit.fontawesome.com/20422689a9.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit-icons.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <?php $title = 'Fishing report system - ' . $_SESSION["login"]; echo "<html><title>$title</title></html>"; ?>
</head>
<body>

<div class="menuBlock">
    <table>
        <tr>
        <td><div class="menuButtons"><i class="fa fa-map-marker-alt" id = "menuIcons"></i>Add new place</td></div></tr>
        <tr>
        <td><div onclick="window.location='index.php?action=logout'" class="menuButtons" id = "logOut"><i class="fas fa-sign-out-alt" id = "menuIcons"></i>Log out</td></tr></div>
        </table></div>

<div class = 'storiesBlock'>
    <?php
        $session = $_SESSION['angler_id'];
        $sql = "SELECT * FROM story WHERE angler_id = '$session'";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $anglerId = $session;
                $sTime = $row['start_time'];
                $eTime = $row['end_time'];
                $placeId = $row['place_id'];
                $queryPlace = mysqli_query($connect, "SELECT place FROM places WHERE place_id = $placeId");
                $ztime = mysqli_query($connect, "SELECT TIME_FORMAT(start_time, '%H %i') as starttime, TIME_FORMAT(end_time, '%H %i') as endtime FROM story where start_time = '$sTime' and end_time = '$eTime'");
                $time = mysqli_fetch_assoc($ztime);
                $place = mysqli_fetch_assoc($queryPlace);
                echo "<div class='storyBlock'><div class='date'>" 
                . $row['date'] . "</div>"
                . "<div class = 'storyIcons'><i class='fas fa-cloud'></i>" . " " . $row['weather'] . "</div>"
                . "<div class = 'storyIcons'><i class='fas fa-map-marker'></i>" . " " . $place['place'] . "</div>"
                . "<div class = 'storyIcons'><i class='far fa-clock'></i>" . " " . $time['starttime'] . " - " . $time['endtime'] . "</div>" 
                . "<div class = 'storyIcons'><i class='fas fa-fish'></i>" . " " . $row['total_fish'] . "</div>
                </div>";
            }
        }
    ?>
    </div>
    <i class="fas fa-plus" id = "plus" title = "Add a new fishing story"></i>
<!--------------------------------------------Modal window------------------------------------------>
    <form method = "POST" action = "main.php">
    <div class = "modal">
        <div class="modal-content">
            <span class = "newPost">New Story</span>
            <span class = "text">Weather: <select name="weather" id="weather" class = "selectMenu">
                <option>Select weather</option>
                <option value="Sunny">Sunny</option>
                <option value="Cloudy">Cloudy</option>
                <option value="Rainy">Rainy</option>
                <option value="Stormy">Stormy</option>
            </select></span>
            <span class="text">Catching place: <select name="cPlace" id="cPlace" class = "selectMenu">
                <option>Select place</option>
                <?php 
                    $sql = "SELECT * FROM places";
                    $result = $connect->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['place_id'] . "'>" . $row['place'] . "</option>";
                        }
                    }
                ?>
            </select></span>
            <span class="text">Date: <input type="date" name= "fishingDate" id = "fishingDate"></span>
            <span class="text">Beginning of fishing: <input type="time" class = "inputTime" name = "startTime" id = "startTime"> / End of fishing: <input type="time" class = "inputTime" name = "endTime" id = "endTime"></span>
            <span class="text">Activity of fish: <select name="activity" id="activity" class = "selectMenu">
                <option>Select</option>
                <option value="Active">Active</option>
                <option value="Neutral">Neutral</option>
                <option value="Passive">Passive</option>
            </select></span>

            <span class="text">The best zones: <input type="text" size = "40" name = "bestZones" id = "bestZones"></span>
            <span class="text"><b>Total number of caugh fish: </b><input type="text" size = "5" name = "totalFish" id = "totalFish"></span>
            <span class="text"><b>Comments: </b><br><br><input placeholder = "Type something here..." type="text" class = "comments" size = "75" name = "comment" id = "comment"></span>

        <input type="submit" class = "send" name = "submit" value = "Send">
        </div>
    </div></form>
    <script>
        var modal = document.querySelector(".modal");
        var button = document.querySelector("#plus");
        var mBlock = document.querySelector(".menuBlock");
        var sBlock = document.querySelector(".storyBlock");
       
        button.onclick = function() {
            mBlock.style.position = "absolute";
            modal.style.display = "block";
            button.style.display = "none";
        }
        window.onclick = function(event) { 
            if (event.target == modal) {
                mBlock.style.position = "sticky";
                modal.style.display = "none";
                button.style.display = "block";

            }
        }
    </script>
</body>
</html>
<?php 
error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $connect = mysqli_connect('localhost', 'root', 'Oxygen2000!','fishreport') or die (mysqli_error($connect));
        if (!$connect) {
            echo "Database not connected";
        }
        ?>
<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" || $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }
    }else{
        header("location: ../login.php");
    }
    
    if($_POST){
        // Import database
        include("../connection.php");

        $title = $_POST["title"];
        $docid = $_POST["docid"];
        $nop = $_SESSION["user"];
        $date = $_POST["date"];
        $time = $_POST["time"];

        // Properly escape and quote the string variables to prevent SQL injection and syntax errors
        $docid = $database->real_escape_string($docid);
        $title = $database->real_escape_string($title);
        $date = $database->real_escape_string($date);
        $time = $database->real_escape_string($time);
        $nop = $database->real_escape_string($nop);

        $sql = "INSERT INTO schedule (docid, title, scheduledate, scheduletime, nop) VALUES ('$docid', '$title', '$date', '$time', '$nop');";
        $result = $database->query($sql);

        if ($result) {
            header("location: schedule.php?action=session-added&title=$title");
        } else {
            // Handle query error here if needed
            echo "Error: " . $database->error;
        }
    }

?>

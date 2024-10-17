<?php

session_start();

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "employ";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_SESSION["email"]) && isset($_SESSION["name"])) {
        $email = $_SESSION["email"];
        $name = $_SESSION["name"];
        $_SESSION["RID"] = $row["RID"];

        
        $sql_insert = $conn->prepare("INSERT INTO recruitment (REmail, RName, CompanyName, CompanyLocation, RGender)
                                      VALUES (?, ?, ?, ?, ?)
                                      ON DUPLICATE KEY UPDATE
                                      RName = VALUES(RName), CompanyName = VALUES(CompanyName), CompanyLocation = VALUES(CompanyLocation), RGender = VALUES(RGender)");

        $CompanyName = isset($_POST['CompanyName']) ? $_POST['CompanyName'] : "";
        $CompanyLocation = isset($_POST['CompanyLocation']) ? $_POST['CompanyLocation'] : "";
        $RGender = isset($_POST['RGender']) ? $_POST['RGender'] : "";

        $sql_insert->bind_param("sssss", $email, $name, $CompanyName, $CompanyLocation, $RGender);

        if ($sql_insert->execute()) {
            header("Location: 1login.php");
            exit();
        } else {
            echo "Error: " . $sql_insert->error;
        }
        $sql_insert->close();
    } else {
        echo "Session variables (email and name) not set.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rec details</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<div class="rcontainer">
    <div class="rbox">
        <div class="rboximg">
            <img src="img/working.jpg" width="300px" height="300px">
            <br>
            <center><h1>RECRUITER</h1></center>
        </div>
    </div>
    <div class="rbox1">
        <div class="rde1">
            <center>REGISTER</center>
            <br>
            
            <form method="post" action="">
            <label for="RGender">Gender:</label>
                <input type="radio" name="RGender" value="Male" required>Male
                <input type="radio" name="RGender" value="Female" required>Female<br>
                

                <label for="CompanyName">Company Name:</label>
                <input type="text" name="CompanyName" style="height:30px;width:170px; border-radius: 5px; border: 1px solid black; padding: 5px;"required><br>
                
                
                <label for="CompanyLocation">Company Location:</label>
                <input type="text" name="CompanyLocation" style="height:30px;width:130px; border-radius: 5px; border: 1px solid black; padding: 5px;"required><br>
                <br>
                <br>
    
                
            
                <center><input type="submit" value="REGISTER" style="width:200px;border-radius: 5px; border: 2px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 20px;padding: 5px;"></center>
            </form>
        </div>
    </div>
 </div>

</body>
</html>

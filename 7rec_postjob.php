<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["type"] !== "Recruiter") {
    header("Location: 1login.php");
    exit();
}
$email = $_SESSION["email"];
$name = $_SESSION["name"];
$RID = $_SESSION["RID"];

    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "employ";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_SESSION["email"];
        $sql_select = "SELECT RID FROM recruitment WHERE REmail = '$email' LIMIT 1";
        $result_select = $conn->query($sql_select);

        if ($result_select) {
            if ($result_select->num_rows > 0) {
                $row = $result_select->fetch_assoc();
                $RID = $row['RID'];
                $JobRole = $_POST['jobrole'] ?? "";
                $JobType = $_POST['jobtype'] ?? "";
                $Qualification = $_POST['qualification'] ?? "";
                $MinExp = $_POST['minexp'] ?? "";
                $Salary = $_POST['salary'] ?? "";
                $sql_insert = "INSERT INTO job (RID, JobRole, JobType, Qualification, MinExp, Salary) 
                               VALUES ('$RID', '$JobRole', '$JobType', '$Qualification', '$MinExp', '$Salary')";
                if ($conn->query($sql_insert) === TRUE) {
                    header("Location: 8rec_postedjob.php");
                    exit();
                } else {
                    echo "Error inserting record: " . $conn->error;
                }
            } else {
                echo "No data found in the recruitment table for the current recruiter.";
            }
        } else {
            echo "Error retrieving data from the recruitment table: " . $conn->error;
        }
    }

    $conn->close();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Post Job</title>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
<div class="vertical-box">
      <img src="img/working.jpg"  width="100px"; height="100px";>
      <div class="wel">
        <h1>Welcome, <?php echo $name; ?></h1> 
      </div>
      <div class="logout">
    <button><a href="logout.php">Logout</a></button> 
</div>
    </div>
    <div class="horizontal-box">
    <div class="box1">
    <a href="7rec_postjob.php"><button>Post the job</button></a>
      <br>
      <a href="8rec_postedjob.php"><button>Posted job</button></a>
      <br>
      <a href="9rec_app.php"><button>Applications</button></a>
      <br>
      <a href="allposted.php"><button>View jobs</button></a>
    </div>
    <div class="box2">
      <div class="register">
        <center>Create a Job:</center>
        <form method="POST" action="">
            <label for="jobrole"> Job Role:</label>
            <input type="text" name="jobrole" placeholder=" Enter Job Role" style="height: 25px;  width:300px; border-radius: 5px; border: 2px solid black; " required>
            <br>
            <label for="jobtype">Job Type:</label>
                <select name="jobtype" style="height: 25px; width: 310px; border-radius: 5px; border: 2px solid black;" required>
                        <option value="Part Time">Part Time</option>
                        <option value="Full Time">Full Time</option>
                        <option value="Internship">Internship</option>
                </select>
            <br>
            <label for="qualification">Qualification:</label>
            <input type="text" name="qualification" placeholder="Enter job Qualification" style="height: 25px;  width:210px; border-radius: 5px; border: 2px solid black; " required>
            <br>
            <label for="minexp">Experience:</label>
            <input type="text" name="minexp" placeholder="Enter min experience(years)" style="height: 25px;  width:245px; border-radius: 5px; border: 2px solid black; " required>
            <br>
            <label for="salary">Salary:</label>
            <input type="text" name="salary" placeholder="Enter Expected Salary"  style="height: 25px;  width:300px; border-radius: 5px; border: 2px solid black;" required>
            <input type="submit" value="Submit" onclick="showAlert('Submitting...')" style="border-radius: 5px; border: 2px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 30px; padding: 10px;" >
          </form>
      </div>
    </div>
  </div>
</body>
</html>

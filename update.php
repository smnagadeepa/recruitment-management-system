<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["type"] !== "Recruiter") {
    header("Location: 1login.php");
    exit();
}
$email = $_SESSION["email"];
$name = $_SESSION["name"];
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "employ";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    if (isset($_GET['JID'])) {
        $JID = $_GET['JID'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jobRole = $_POST['jobRole'];
            $jobType = $_POST['jobType'];
            $qualification = $_POST['qualification'];
            $minExp = $_POST['minExp'];
            $salary = $_POST['salary'];

            $sql_update_job = "UPDATE job SET JobRole='$jobRole', JobType='$jobType', Qualification='$qualification', MinExp='$minExp', Salary='$salary' WHERE JID=$JID";

            if ($conn->query($sql_update_job) === TRUE) {
                $_SESSION['success_message'] = "Details successfully updated";
                echo "<script>alert('Details successfully updated');</script>";
                echo "<script>window.location = '8rec_postedjob.php';</script>";
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        $sql_select_job = "SELECT * FROM job WHERE JID = $JID";
        $result_job = $conn->query($sql_select_job);

        if ($result_job->num_rows == 1) {
            $row = $result_job->fetch_assoc();
            $jobRole = $row['JobRole'];
            $jobType = $row['JobType'];
            $qualification = $row['Qualification'];
            $minExp = $row['MinExp'];
            $salary = $row['Salary'];
        } else {
            echo "Job not found";
            exit();
        }
    } else {
        echo "Invalid Job ID";
        exit();
    }
} else {
    header("Location: 1login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Posted Job</title>
</head>
<body>

<div class="vertical-box">
    <img src="img/working.jpg" width="100px" height="100px">
    <div class="wel">
        <h1>Welcome ,<?php echo $name; ?></h1>
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

<div class="form-container">
    <center>
    <h1>Update Job</h1>
    <br>

    <form method="post" action="">
        <label for="jobRole">Job Role:</label>
        <input type="text" name="jobRole" value="<?php echo $jobRole; ?>" style="height:35px; width:200px ; border-radius: 5px; border: 1px solid black;" required>
        <br>
        <label for="jobType">Job Type:</label>
        <input type="text" name="jobType" value="<?php echo $jobType; ?>" style="height:35px; width:200px ; border-radius: 5px; border: 1px solid black;" required>
        <br>
        <label for="qualification">Qualification:</label>
        <input type="text" name="qualification" value="<?php echo $qualification; ?>" style="height:35px; width:200px ; border-radius: 5px; border: 1px solid black;" required>
        <br>
        <label for="minExp">Experience:</label>
        <input type="text" name="minExp" value="<?php echo $minExp; ?>"  style="height:35px; width:200px ; border-radius: 5px; border: 1px solid black;" required>
        <br>
        <label for="salary">Salary:</label>
        <input type="text" name="salary" value="<?php echo $salary; ?>" style="height:35px; width:200px ; border-radius: 5px; border: 1px solid black;" required>
        <br>
        <div class="up">
        <input type="submit" value="Update" style="padding:10px;margin-left: 50px;margin-top: 70px;border-radius: 10px;font-size: 30px;text-align: center;
    background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5);">
</div>
</div>
    </form>
</center>
</div>

</body>
</html>

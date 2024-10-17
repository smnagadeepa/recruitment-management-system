<?php
session_start();

if (!isset($_SESSION["name"])) {
    header("Location: 1login.php");
    exit();
}

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "employ";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["JID"]) && isset($_POST["RID"]) && isset($_POST["CID"])) {
        $JID = $_POST["JID"];
        $RID = $_POST["RID"];
        $CID = $_POST["CID"];

        $sql_check_application = "SELECT * FROM application WHERE JID = '$JID' AND CID = '$CID'";
        $result_check_application = $conn->query($sql_check_application);

        if ($result_check_application->num_rows > 0) {
            $message = "You have already applied for this job";
        } else {
            $sql_get_age = "SELECT TIMESTAMPDIFF(YEAR, CDOB, CURDATE()) AS age FROM client WHERE CID = '$CID'";
            $result_get_age = $conn->query($sql_get_age);

            if ($result_get_age->num_rows > 0) {
                $row = $result_get_age->fetch_assoc();
                $age = $row["age"];

                if ($age < 18) {
                    $message = "Error: You must be at least 18 years old to apply for any job.";
                } elseif ($age >= 18 && $age <= 23) {
                    $sql_check_internship = "SELECT JobType FROM job WHERE JID = '$JID'";
                    $result_check_internship = $conn->query($sql_check_internship);

                    if ($result_check_internship->num_rows > 0) {
                        $row = $result_check_internship->fetch_assoc();
                        $job_type = $row["JobType"];
                        if ($job_type == "Internship") {
                            $sql_insert_application = "INSERT INTO application (JID, RID, CID) VALUES ('$JID', '$RID', '$CID')";
                            if ($conn->query($sql_insert_application) === TRUE) {
                                $message = "Success: Your application for internship has been submitted";
                            } else {
                                $message = "Error applying for the internship: " . $conn->error;
                            }
                        } else {
                            $message = "Error: Clients aged 18-23 are only eligible for internships.";
                        }
                    }
                } else {
                    $sql_check_job_type = "SELECT JobType FROM job WHERE JID = '$JID'";
                    $result_check_job_type = $conn->query($sql_check_job_type);

                    if ($result_check_job_type->num_rows > 0) {
                        $row = $result_check_job_type->fetch_assoc();
                        $job_type = $row["JobType"];

                        if ($job_type == "Internship") {
                            $message = "Error: Clients above 23 years old are not eligible for internships.";
                        } elseif ($job_type == "Part Time" || $job_type == "Full Time") {
                            $sql_insert_application = "INSERT INTO application (JID, RID, CID) VALUES ('$JID', '$RID', '$CID')";
                            if ($conn->query($sql_insert_application) === TRUE) {
                                $message = "Success: Your application for job has been submitted";
                            } else {
                                $message = "Error applying for the job: " . $conn->error;
                            }
                        } else {
                            $message = "Error: Invalid job type";
                        }
                    } else {
                        $message = "Error: Unable to determine job type. Please contact support.";
                    }
                }
            } else {
                $message = "Error: Unable to determine your age. Please contact support.";
            }
        }
    } 
}

$search = isset($_POST['search']) ? $_POST['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';
$sql_select_jobs = "SELECT job.*, recruitment.CompanyName, recruitment.CompanyLocation 
                    FROM job 
                    INNER JOIN recruitment ON job.RID = recruitment.RID 
                    WHERE job.JID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR job.Qualification LIKE '%$search%' OR job.Salary LIKE '%$search%' OR recruitment.CompanyName LIKE '%$search%' OR recruitment.CompanyLocation LIKE '%$search%' OR job.MinExp LIKE '%$search%'";
$sql_select_jobs .= " ORDER BY JID ASC";
$result_jobs = $conn->query($sql_select_jobs);

$name = $_SESSION["name"];
$CID = $_SESSION["CID"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style1.css">
  <title>Available Job</title>
</head>
<body>
    <div class="vertical-box">
      <img src="img/working.jpg"  width="100px"; height="100px";>
      <div class="wel">
        <h1>Welcome, <?php echo $name; ?></h1> 
      </div>
      <div class="logout">
        <button><a href="1login.php">Logout</a></button> 
      </div> 
    </div>
    <div class="horizontal-box">
    <div class="box1">
    <a href="11cli_app.php"><button>Available jobs</button></a>
      <br>
      <a href="12cli_myapp.php"><button>My application</button></a>
      <br>
    </div>
    <div class="box2">
      <div class="ava">
        <br>
      <form method="post" action="">
                <label for="search">Search:</label>
                <input type="text" name="search" style="height:30px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;">
                <input type="submit" value="Search" style=" height:30px; border-radius: 5px; border: 1px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 20px;">
            </form>

            <br>
           <table border="1" style="width:100%">
           
    <tr>
        <th><a href="?sort=JID" style="text-decoration: none; color:black;">JID</a></th>
        <th><a href="?sort=JobRole" style="text-decoration: none; color:black;">Job Role</a></th>
        <th><a href="?sort=JobType"style="text-decoration: none; color:black;">Job Type</a></th>
        <th><a href="?sort=Qualification" style="text-decoration: none; color:black;">Qualification</a></th>
        <th><a href="?sort=MinExp" style="text-decoration: none; color:black;">Experience</a></th>
        <th><a href="?sort=Salary" style="text-decoration: none; color:black;">Salary</a></th>
        <th><a href="?sort=CompanyName" style="text-decoration: none; color:black;">Company Name</a></th>
        <th><a href="?sort=CompanyLocation" style="text-decoration: none; color:black;">Company Location</a></th>
        <th>Apply</th>
    </tr>
    <?php

    if ($result_jobs->num_rows > 0) {
        while ($row = $result_jobs->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['JID'] . "</td>";
            echo "<td>" . $row['JobRole'] . "</td>";
            echo "<td>" . $row['JobType'] . "</td>";
            echo "<td>" . $row['Qualification'] . "</td>";
            echo "<td>" . $row['MinExp'] . "</td>";
            echo "<td>" . $row['Salary'] . "</td>";
            echo "<td>" . $row['CompanyName'] . "</td>";
            echo "<td>" . $row['CompanyLocation'] . "</td>";
            echo "<td>
                      <form method='post' action=''>
                          <input type='hidden' name='JID' value='" . $row['JID'] . "'>
                          <input type='hidden' name='RID' value='" . $row['RID'] . "'>
                          <input type='hidden' name='CID' value='" . $CID . "'> 
                          <input type='submit' value='Apply'>
                      </form>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No posted jobs</td></tr>";
    }
    ?>
</table>
<?php if(isset($message)) : ?>
    <script>
        alert("<?php echo $message; ?>");
    </script>
<?php endif; ?>
</div>
</div>
</div>
</body>
</html>

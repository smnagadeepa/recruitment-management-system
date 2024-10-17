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

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
    

    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';

    $sql_select_jobs = "SELECT job.JID, job.JobRole, job.JobType, job.Qualification, job.MinExp, job.Salary, recruitment.CompanyName, recruitment.CompanyLocation
                        FROM job
                        INNER JOIN recruitment ON job.RID = recruitment.RID
                        WHERE job.JID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR job.Qualification LIKE '%$search%' OR job.MinExp LIKE '%$search%' OR  job.Salary LIKE '%$search%' OR recruitment.CompanyName  LIKE '%$search%' OR  recruitment.CompanyLocation LIKE '%$search%'";
                        $sql_select_jobs .= " ORDER BY job.JID ASC";

    $result_jobs = $conn->query($sql_select_jobs);
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
        <div class="posted-jobs">
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
                    <th><a href="?sort=JobType" style="text-decoration: none; color:black;">Job Type</a></th>
                    <th><a href="?sort=Qualification" style="text-decoration: none; color:black;">Qualification</a></th>
                    <th><a href="?sort=MinExp" style="text-decoration: none; color:black;">Experience</a></th>
                    <th><a href="?sort=Salary" style="text-decoration: none; color:black;">Salary</a></th>
                    <th><a href="?sort=CompanyName" style="text-decoration: none; color:black;">Company Name</a></th>
                    <th><a href="?sort=CompanyLocation" style="text-decoration: none; color:black;">Company Location</a></th>
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
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No posted jobs</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>

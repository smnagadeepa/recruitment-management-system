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
    $RID = $_SESSION["RID"];

    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';

    $sql_select_applied_jobs = "SELECT application.AID, job.JobRole, job.JobType, client.CNAME, client.CEMAIL, client.CDob, client.CLocation, client.CSkills, client.CQualification
                                FROM application
                                JOIN job ON application.JID = job.JID
                                JOIN client ON application.CID = client.CID
                                JOIN recruitment ON job.RID = recruitment.RID
                                WHERE recruitment.REmail = '$email'
                                AND (application.AID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR client.CNAME LIKE '%$search%' OR client.CEMAIL LIKE '%$search%' OR client.CDob LIKE '%$search%' OR client.CLocation LIKE '%$search%' OR client.CSkills LIKE '%$search%' OR client.CQualification LIKE '%$search%')";
    $sql_select_applied_jobs .=  " ORDER BY application.AID ASC";

    $result_applied_jobs = $conn->query($sql_select_applied_jobs);
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
    <title>Applications</title>
</head>
<body>
    <div class="vertical-box">
        <img src="img/working.jpg"  width="100px" height="100px">
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
            <div class="posted-jobs">
                <br>
                <form method="post" action="">
                    <label for="search">Search:</label>
                    <input type="text" name="search" style="height:30px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;">
                    <input type="submit" value="Search" style=" height:30px; border-radius: 5px; border: 1px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 20px;">
                </form>
                <br>
                <?php if ($result_applied_jobs !== false && $result_applied_jobs->num_rows > 0) { ?>
                    <table border="1" style="width:100%">
                        <tr>
                            <th><a href="?sort=AID" style="text-decoration: none; color:black;">AID</a></th>
                            <th><a href="?sort=JobRole" style="text-decoration: none; color:black;">Job Role</a></th>
                            <th><a href="?sort=JobType" style="text-decoration: none; color:black;">Job Type</a></th>
                            <th><a href="?sort=CNAME" style="text-decoration: none; color:black;">Client Name</a></th>
                            <th><a href="?sort=CEMAIL" style="text-decoration: none; color:black;">Client Email</a></th>
                            <th><a href="?sort=CDob" style="text-decoration: none; color:black;">Client DOB</a></th>
                            <th><a href="?sort=CLocation" style="text-decoration: none; color:black;">Client Location</a></th>
                            <th><a href="?sort=CSkills" style="text-decoration: none; color:black;">Client Skills</a></th>
                            <th><a href="?sort=CQualification" style="text-decoration: none; color:black;">Client Qualification</a></th>
                        </tr>
                        <?php while ($row = $result_applied_jobs->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['AID']; ?></td>
                                <td><?php echo $row['JobRole']; ?></td>
                                <td><?php echo $row['JobType']; ?></td>
                                <td><?php echo $row['CNAME']; ?></td>
                                <td><?php echo $row['CEMAIL']; ?></td>
                                <td><?php echo $row['CDob']; ?></td>
                                <td><?php echo $row['CLocation']; ?></td>
                                <td><?php echo $row['CSkills']; ?></td>
                                <td><?php echo $row['CQualification']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <p>No applied jobs.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>

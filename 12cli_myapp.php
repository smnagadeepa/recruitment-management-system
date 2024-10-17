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
}
$CID = $_SESSION["CID"];

$search = isset($_POST['search']) ? $_POST['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["AID"])) {
    $AID = $_POST["AID"];
    $sql_delete_application = "DELETE FROM application WHERE AID = '$AID'";
    if ($conn->query($sql_delete_application) === TRUE) {
        $_SESSION['delete_success'] = true; 
        header("Location: 12cli_myapp.php");
        exit();
    } else {
        echo "Error: " . $sql_delete_application . "<br>" . $conn->error;
    }
}

$sql_select_applied_jobs = "SELECT application.AID, job.JobRole, job.JobType, recruitment.CompanyName, recruitment.CompanyLocation, recruitment.RName
                            FROM application
                            JOIN job ON application.JID = job.JID
                            JOIN recruitment ON job.RID = recruitment.RID
                            WHERE application.CID = '$CID'
                            AND (application.AID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR recruitment.CompanyName LIKE '%$search%' OR recruitment.CompanyLocation LIKE '%$search%' OR recruitment.RName LIKE '%$search%')";
$sql_select_applied_jobs .= " ORDER BY application.AID ASC";
$result_applied_jobs = $conn->query($sql_select_applied_jobs);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>My applications</title>
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
                <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']) : ?>
                    <script>
                        alert('Application has been successfully deleted.');
                    </script>
                    <?php unset($_SESSION['delete_success']); ?> 
                <?php endif; ?>
                <table border="1" style="width:100%">
                    <tr>
                        <th>AID</th>
                        <th>Recruiter Name</th>
                        <th>Job Role</th>
                        <th>Job Type</th>
                        <th>Company Name</th>
                        <th>Company Location</th>
                        <th>Action</th>
                    </tr>
                    <?php
                     if ($result_applied_jobs->num_rows > 0) {
                         while ($row = $result_applied_jobs->fetch_assoc()) {
                             echo "<tr>";
                             echo "<td>" . $row['AID'] . "</td>";
                             echo "<td>" . $row['RName'] . "</td>";
                             echo "<td>" . $row['JobRole'] . "</td>";
                             echo "<td>" . $row['JobType'] . "</td>";
                             echo "<td>" . $row['CompanyName'] . "</td>";
                             echo "<td>" . $row['CompanyLocation'] . "</td>";
                             echo "<td>
                                       <form method='post' action='' onsubmit='return confirm(\"Are you sure you want to delete this application?\");'>
                                           <input type='hidden' name='AID' value='" . $row['AID'] . "'>
                                           <input type='submit' value='Delete'>
                                       </form>
                                   </td>";
                             echo "</tr>";
                         }
                     } else {
                         echo "<tr><td colspan='7'>No applied jobs.</td></tr>";
                     }
                     ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

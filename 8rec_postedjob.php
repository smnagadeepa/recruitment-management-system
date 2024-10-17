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

    if (isset($_GET['JID'])) {
        $JID = $_GET['JID'];

        $sql_delete_job = "DELETE FROM job WHERE JID = $JID";

        if ($conn->query($sql_delete_job) === TRUE) {
            $_SESSION['delete_success'] = true;
            header("Location: 8rec_postedjob.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    $sql_select_jobs = "SELECT * FROM job WHERE ( JID LIKE '%$search%' OR RID LIKE '%$search%' OR JobRole LIKE '%$search%' OR JobType LIKE '%$search%' OR Qualification LIKE '%$search%' OR Salary LIKE '%$search%' OR MinEXp LIKE '%$search%')
    AND RID = (SELECT RID FROM recruitment WHERE REmail = '$email')";
    $sql_select_jobs .= " ORDER BY JID ASC";

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
            <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']) : ?>
                <script>
                    alert('Record deleted successfully');
                </script>
                <?php unset($_SESSION['delete_success']); ?> 
            <?php endif; ?>
            <table border="1" style="width:100%">
                <tr>
                    <th>JID</a></th>
                    <th>RID</a></th>
                    <th><a href="?sort=JobRole"style="text-decoration: none; color:black;">Job Role</a></th>
                    <th><a href="?sort=JobType" style="text-decoration: none; color:black;">Job Type</a></th>
                    <th><a href="?sort=Qualification" style="text-decoration: none; color:black;">Qualification</a></th>
                    <th><a href="?sort=MinExp"style="text-decoration: none; color:black;">Experience</a></th>
                    <th><a href="?sort=Salary"style="text-decoration: none; color:black;">Salary</a></th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
                <?php
                if ($result_jobs->num_rows > 0) {
                    while ($row = $result_jobs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['JID'] . "</td>";
                        echo "<td>" . $row['RID'] . "</td>";
                        echo "<td>" . $row['JobRole'] . "</td>";
                        echo "<td>" . $row['JobType'] . "</td>";
                        echo "<td>" . $row['Qualification'] . "</td>";
                        echo "<td>" . $row['MinExp'] . "</td>";
                        echo "<td>" . $row['Salary'] . "</td>";
                        echo "<td><a href='update.php?JID=" . $row['JID'] . "'>Update</a></td>";
                        echo "<td><a href='?JID=" . $row['JID'] . "' onclick='return confirmDelete()'>Delete</a></td>"; // Added onclick event for confirmation
                        echo "</tr>";
        
                    }
                } else {
                    echo "<tr><td colspan='9'>No posted jobs</td></tr>";
                }
                ?>
            </table>
            
        </div>
    </div>
</div>

<script>

function confirmDelete() {
    return confirm("Are you sure you want to delete this job?");
}
</script>

</body>
</html>

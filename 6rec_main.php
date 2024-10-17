<?php
session_start();
if (isset($_SESSION["email"]) || $_SESSION["type"] !== "Recruiter") {
$email = $_SESSION["email"];
$name = $_SESSION["name"];
$RID = $_SESSION["RID"];
}else{
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
  <title>Recruiter main screen</title>
</head>

<body>
    <div class="vertical-box">
      <img src="img/working.jpg" width="100px" height="100px"> 
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
    <div class="text1">
      <br>
      <p>Hey Recuirter, Welcome to HIRE-ME!</p>
     <p> Start hiring clients by posting jobs.</p>
    </div>
  </div>
</body>
</html>

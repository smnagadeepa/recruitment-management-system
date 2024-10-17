<?php
session_start(); 
if (isset($_SESSION["name"])) {
    $name = $_SESSION["name"];
    $CID = $_SESSION["CID"];
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
  <title>Client main screen</title>
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
    <div class="text1">
      <br>
      <p>Hey Client, Welcome to HIRE-ME!</p>
     <p> Start applying for your dream job.</p>
    </div>
    </div>
  </div>

</body>
</html>

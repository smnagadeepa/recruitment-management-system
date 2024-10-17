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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password_input = $_POST["password"];
  
    $sql = "SELECT u.email, u.name, u.password, u.type, c.CID 
            FROM user u 
            LEFT JOIN client c ON u.email = c.CEmail 
            WHERE LOWER(u.email) = LOWER('$email')";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["type"] == "Client" && isset($row["password"]) && password_verify($password_input, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["type"] = $row["type"];
            $_SESSION["CID"] = $row["CID"];
            header("Location: http://localhost/dbms/10cli_main.php");
            exit();
        } elseif ($row["type"] == "Recruiter" && isset($row["password"]) && password_verify($password_input, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["type"] = $row["type"];
            $_SESSION["RID"] = $row["RID"];

            header("Location: http://localhost/dbms/6rec_main.php");
            exit();
        } else {
            $message = "Invalid password or user type";
        }
    } else {
        $message = "User not found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Log In</title>
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="login-box">
    <div class="login-inbox">
        <center>Account</center>
        <div class="login-text">
            <form method="post">
                <input type="text" name="email" placeholder=" Email Address"
                       style="height:50px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;"
                       required>
                <br>
                <br>
                <input type="password" name="password" placeholder=" Password "
                       style="height:50px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;"
                       required>
                <br>
                <br>
                <input type="submit" value="Login"
                       style=" width:200px;border-radius: 5px; border: 2px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 20px;padding: 5px; ">
                <br>
                <br>
                <div class="signup-link1">
                    <a href="2register.php" style="text-decoration: none;">Don't Have Account?Create A New</a>
                </div>
            </form>
            <script>
        
                var message = "<?php echo $message; ?>";
                if (message !== "") {
                    alert(message);
                }
            </script>
        </div>
    </div>
</div>
</body>
</html>

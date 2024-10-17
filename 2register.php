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
//check 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $type = $_POST["type"];
    $password = $_POST["password"];

    $check_email_sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        $message = "Error: Email already exists. Please use a different email.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, name, type, password) VALUES ('$email', '$name', '$type', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful!";
            //store
            if ($type == "Recruiter") {
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;
            $_SESSION["type"] = $type;
            $_SESSION["RID"] = $row["RID"];
                header("Location: 4rec_details.php");
                exit();
            } elseif ($type == "Client") {
                $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;
            $_SESSION["type"] = $type;
            $_SESSION["CID"] = $row["CID"];
                header("Location: 5cli_details.php");
                exit();
            }
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign Up | Log In</title>
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="register-box">
    <div class="register-inbox">
        <center>REGISTER</center>
        <div class="register-text">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email">Email:</label>
                <input type="email" name="email" style="height:30px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;" required><br/>
                <label for="name">Name:</label>
                <input type="text" name="name" style="height:30px;width:250px; border-radius: 5px; border: 1px solid black; padding: 5px;" required><br>

                <label>Type: </label>
                <select name="type"  style="height:30px;width:270px; border-radius: 5px; border: 1px solid black; padding: 5px;" required>
                    <option value="Recruiter" >Recruiter</option>
                    <option value="Client" >Client</option>
                </select><br>

                <label for="password">Password:</label>
                <input type="password" name="password" style="height:30px;width:200px; border-radius: 5px; border: 1px solid black; padding: 5px;" required><br/><br/>
                

                <center><input type="submit" value="REGISTER" style="width:200px;border-radius: 5px; border: 2px solid black; background: -webkit-linear-gradient(right, #FBEE97, #ADEDD5, #FBEE97, #ADEDD5); font-size: 20px;padding: 5px;"></center>
                <br>
                <div class="signup-link">
              <a href="1login.php" style="text-decoration: none;">Already have an account? Log-in</a>
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

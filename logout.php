<?php
session_start();

if(isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"]) && isset($_SESSION["RID"]) && isset($_SESSION["CID"])) {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
    $RID = $_SESSION["RID"];
    $RID = $_SESSION["CID"];
} else {
    header("Location: 1login.php");
    exit();
}
if(isset($_GET["logout"])) {
    session_unset();
    session_destroy();

    header("Location: 1login.php");
    exit();
}

?>

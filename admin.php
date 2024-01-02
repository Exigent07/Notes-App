<?php 
require_once('Helpers/encryption.php');
require_once('Helpers/connect.php');
require_once('Helpers/functions.php');
session_start();

userAgent();
$query = "SELECT ip FROM waf WHERE username = 'admin'";
$ip = query($conn, $query, NULL);

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== encrypt("admin")) {
    header("Location: login.php?unauth");   
    die(); 
} 
elseif ($_SERVER['REMOTE_ADDR'] !== $ip['ip']) {
    header("Location: login.php?unauth");   
    die(); 
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php?loggedout");
    die(); 
} elseif (isset($_POST['viewAll'])) {
    header("Location: viewAll.php?viewall");
    die(); 
} elseif (isset($_POST['modifyUsers'])) {
    header("Location: modify.php");
    die();
} elseif (isset($_POST['viewProfile'])) {
    header("Location: allProfile.php?viewProfile");
    die();
}
include("Helpers/header.php");
?>
<body>
    <div class="main">
        <p style="color: black;" class="head">Admin Panel</p>
        <form action="admin.php" method="post" class="form_css">
            <button type="submit" name="modifyUsers" value="modifyUsers" class="btn para" style="width: 120px;">Modify Users</button>
            <button type="submit" name="viewAll" value="vieaAll" class="btn para" style="width: 140px;">View all Notes</button>
            <button type="submit" name="viewProfile" value="viewProfile" class="btn para" style="width: 140px;">View all Profiles</button>
        </form>
        <form action="admin.php" method="post" class="form_css">
            <button type="submit" name = "logout" class="btn para" value="logout" style="color: black; width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
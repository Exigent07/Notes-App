<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

userAgent();
session_start();

$username = $_SESSION['uid'] ? (decrypt($_SESSION['uid'])) : NULL;
$query = "SELECT ip FROM waf WHERE username = ?";
$ip = query($conn, $query, $username);

if (!isset($username) || $username === "" || $ip['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    header("Location: login.php?unauth");
    die();
} 
if (isset($_POST['logout'])) {
    logout($conn, $username);
    session_destroy();
    header("Location: login.php?loggedout");
    die();
}  
elseif (isset($_POST['goBack'])) {
    header("Location: bios.php");
    die(); 
} 
elseif (!isset($_GET['view'])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die();
}
include("Helpers/header.php");
?>
<body>
    <?php include("nav.php"); ?>    
    <div class="main">
        <p class="head" style="color: black;">Your Text Files</p>
        <form action="viewNotes.php" method="post" class="form_css">
        <?php 
            $path = "uploads/";
            $getNotes = TRUE; $found = 0; $value = 1;

            while ($getNotes !== NULL) {
                if (!find($conn, $username, "table")) {
                    break;
                }
                $query = "SELECT note FROM `$username` WHERE id = ?";
                $getNotes = query($conn, $query, $value);

                if ($getNotes !== NULL) {
                    $result = $getNotes['note'];
                    $content = file_get_contents($result);
                    $lines = explode("\n", $content);
                    if (count($lines) > 12) {
                        $content = implode("\n", array_slice($lines, 0, 12)) . "\n" . "..........";
                    }
                    $sanitized = nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));

                    $found++; $value++;
                    echo '<form action="viewNotes.php" method="post" class="form_css">
                    <h3>' . basename($result) . '</h3>
                    <p class="showNote">
                        <input name="pathValue" type="hidden" value=' . "'" . encrypt($result) . "'" . '>' . $sanitized . 
                    '</p>
                    <button value="viewIt" type="submit" class="btn">View Note</button></form>';
                } else {
                    break;
                }
            }

            if ($found === 0) {
                echo '<p style="color: black;" class = "error">Nothing to Show!</p>';
            }
        $conn->close();
        ?>
        </form>
        <form action="view_images.php" method="post" class="form_css">
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
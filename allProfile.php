<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

userAgent();
$query = "SELECT ip FROM waf WHERE username = 'admin'";
$ip = query($conn, $query, NULL);

session_start();
$username = $_SESSION['admin'];
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== encrypt("admin")) {
    header("Location: login.php?unauth=");   
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
}  
elseif (isset($_POST['goBack'])) {
    header("Location: admin.php");
    die(); 
} 
elseif (!isset($_GET['viewProfile'])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die();
}
include("Helpers/header.php");

?>
<body>
    <div class="main">
        <h1 style="color: black;">Profiles</h1>
        <form action="allProfile.php" method="post" class="form_css">
        <?php 
            $path = "uploads/";

            $querry = $conn->query( "SELECT username, profile FROM profiles");
            $getImage = mysqli_fetch_all($querry);
            $defaultPath = "profile/default.png";

            for ($user = 0; $user < count($getImage); $user++) {
                $name = $getImage[$user][0];
                if ($name == "admin") {
                    continue;
                }
                if ($getImage[$user][1] !== NULL) {
                    $file = $getImage[$user][1];
                    echo "<div class='viewAll'>
                    <h3 class='head'>#" . $getImage[$user][0] . "</h3>";
                        echo "
                        <img class='img' src=" . "'" . $file . "'" . "></img></div>";
                } else {
                        echo '<h3 class="head">#' . $getImage[$user][0] . '</h3><img class="img" src=' . "'" . $defaultPath . "'" . '></img>';
                    }
                }
    ?>
        </form>
        <form action="allProfile.php" method="post" class="form_css">
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
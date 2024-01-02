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
}  
elseif (isset($_POST['goBack'])) {
    header("Location: admin.php");
    die();
} 
elseif (!isset($_GET['viewAll'])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die();
}
require_once("Helpers/header.php");
?>
<body>
    <div class="main">
        <h1 style="color: black;">All Notes</h1>
        <form action="viewAll.php" method="post" class="form_css">
        <?php 
            $path = "uploads/";
            $query = TRUE; $found = 0; $i = 1;
            $usernames = array(); $notes = array();
            
            echo "  <div class='viewAll'>";
            while ($query !== NULL) {
                $query = query($conn, "SELECT username FROM users WHERE id = ?", $i);
                if ($query) {
                    $name = $query['username'];
                    if ($name === "admin") {
                        $i++;
                        continue;
                    }
                    echo "<h3 class='para' style='text-decoration: underline;'>" . $name . "</h3>";
                    $j = 1;
                    $notes = array();
                    $note = TRUE; 
                    while ($note !== NULL) {
                        $find = find($conn, $name, "table");

                        if (!$find) {
                            break;
                        } else {
                        $note = query($conn, "SELECT note FROM `$name` WHERE id = ?", $j);
                            if ($note) {
                                $notes[] = $result = $note['note'];
                                $content = file_get_contents($result);
                                $lines = explode("\n", $content);
                                $filelabel = sanitize(basename($result));
                                if (count($lines) > 12) {
                                    $content = implode("\n", array_slice($lines, 0, 12)) . "\n" . "..........";
                                }
                                $sanitized = nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
                                echo "
                                <p>#" . $filelabel . "</p>
                                <p class='showNote'>" . $sanitized . "</p>";
                                $found++;
                                $j++;
                            } else {
                                break;
                            }
                        }
                    }
                    $usernames[$name] = $notes;
                    if (count($usernames[$name]) === 0) {
                        echo '<p style="color: black;" class = "error para">Nothing to Show!</p>';
                    }
                    $i++;
                } 
                else {
                    break;
                }
            }
            echo "</div>";

            if ($found === 0) {
                echo '<p style="color: black;" class = "error para">Nothing to Show!</p>';
            }
        $conn->close();
    ?>
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
        </form>
        <form action="view_images.php" method="post" class="form_css">
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
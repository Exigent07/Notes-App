<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

session_start();
$username = decrypt($_SESSION['uid']);

if (!isset($username) || $username == "") {
    header("Location: login.php?unauth"); 
    die();   
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php?loggedout");
    die(); 
}  elseif (isset($_POST['goBack'])) {
    header("Location: bios.php");
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
             if (isset($_GET['view'])) {
                $path = "uploads/";
                $getNotes = TRUE; $found = 0; $value = 1;

                while ($getNotes !== NULL) {
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
                            <input name="pathValue" type="hidden" value=' . "'" . $result . "'" . '>' . $sanitized . 
                        '</p>
                        <button vlaue="viewIt" type="submit" class="btn">View Note</button></form>';
                    } else {
                        break;
                    }
                }

                if ($found === 0) {
                    echo '<p style="color: black;" class = "error">Nothing to Show!</p>';
                }
            }
        ?>
        </form>
        <form action="view_images.php" method="post" class="form_css">
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');
    
userAgent();
session_start();
$username = isset($_SESSION['uid']) ? (decrypt($_SESSION['uid'])) : NULL;
$query = "SELECT ip FROM waf WHERE username = ?";
$ip = query($conn, $query, $username);

if (!isset($username) || $username === "" || $ip['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    header("Location: login.php?unauth");
    die();
} 
elseif (isset($_POST['logout'])) {
    logout($conn, $username);
    session_destroy();
    header("Location: login.php?loggedout");
    die();
} 
elseif (isset($_POST['viewImage'])) {
    header("Location: view_images.php?view");
    die();
}
$times = decrypt($_COOKIE[$username]);

if (isset($_POST['submitFile'])) {
    $path = "uploads/";
    $check = "/[!@#$%^&*()+={}\[\]:;<>,?\/~`'\"-]/";
    
    $fileName = htmlentities($_FILES["file"]["name"], ENT_QUOTES, 'UTF-8');
    $fileRename = htmlentities($_POST['fileName'], ENT_QUOTES, 'UTF-8');
    $redos_rename = redos($fileRename);
    $redos_name = redos($fileName);

    if ($redos_name || $redos_rename) {
        echo 'Upload failed';
        http_response_code(400);
        die();
    }
    else {    
        $match = preg_match($check, $fileRename);

        if ($match || pathinfo($fileRename, PATHINFO_EXTENSION) !== "txt") {
            echo 'Upload failed';
            http_response_code(400);
            die();
        } else {
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $renamePathFile = $path . $username . "/" . $fileRename;

            if ($fileType == 'txt') {
                $image = $_FILES['file']['tmp_name'];
                $content = file_get_contents($image);
                $options = array("notes", $username);
                $exists = FALSE;
                createTable($conn, $options);
                $sql = "SELECT * FROM `$username` WHERE id = ?";
                $checkFile = True; $i = 1;
                
                while ($checkFile !== NULL) {
                    $checkFile = query($conn, $sql, $i);
                
                    if ($checkFile !== NULL) {
                        $checkName = $checkFile['note'];
                
                        if ($checkName === $renamePathFile) {
                            $exists = TRUE;
                            break;
                        }
                        $i++;
                    } else {
                        break;
                    }
                }

                if ($exists === FALSE) {
                    if (!is_dir($path . $username)) {
                        mkdir($path . $username);
                    }
                    $inserted = insert($conn, sanitize($username), $renamePathFile);
                    if ($inserted) {
                        move_uploaded_file($image, $renamePathFile);
                        echo 'Note Uploaded';
                        http_response_code(200);
                        die();
                    } 
                    else {
                        echo 'Upload failed';
                        http_response_code(400);
                        die();
                    }
                } else {
                    echo 'Filename exists';
                    http_response_code(400);
                    die();
                }
            }
            else {
                echo 'Not a txt file!';
                http_response_code(400);
                die();
            }
        }
    }
} 
require_once('Helpers/header.php');
?>

<body>
<?php 
require_once("nav.php"); 
?>
    <div class="main">
        <p class="head" style="color: black;">Welcome to Note Saver!</p>
        <form id="bi0sForm" class="form_css" enctype="multipart/form-data">
<?php 
    echo '<p style="color: black;" class = "para upload" id="error">' . $username . '</p>';
    if (decrypt($_COOKIE[$username]) == "1") {
        echo '<p style="color: black;" class = "para"> This is your first login today.</p>';
    } else {
        echo '<p style="color: black;" class = "para">You logged in ' . $times . ' times today.</p>';
    }

    $query          = "SELECT profile FROM profiles WHERE username = ?";
    $profilePath    = query($conn, $query, sanitize($username));
    $defaultPath    = "profile/default.png";
    $profile        = $profilePath['profile'];
    $conn->close();    

    if ($profile != NULL) {
        echo "<img src= '$profile' class='profile'></img>";
    } else {
        echo "<img src= '$defaultPath' class='profile'></img>";
    } 
?>
            <input type="file" id="file" name="file" accept=".txt" required class="fileInput para">
            <input type="text" name="fileName" id="name" required class="inp para" placeholder="Filename.txt" autocomplete="off">
            <button type="submit" name="submitFile" value="upload" class="btn para" style="width: 120px;">Upload</button>
        </form>
        <form action="bios.php" method="post" class="form_css">
            <button type="submit" name = "viewImage" class="btn para" value="viewImage" style="color: black; width: 120px;">View Notes</button>
            <button type="submit" name = "logout" class="btn para" value="logout" style="color: black; width: 120px;">Logout</button>
        </form>
    </div>
<script src="main.js"></script>
</body>
</html> 
<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

userAgent();
$query = "SELECT ip FROM waf WHERE username = 'admin'";
$ip = query($conn, $query, NULL);

session_start();
$username = $_SESSION['admin'];
$path = "uploads/";
if (!isset($_SESSION['admin']) || decrypt($_SESSION['admin']) !== "admin") {
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
elseif (count($_GET) !== 0) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    die();
}
require_once("Helpers/header.php");
?>
<body>
    <div class="main">
        <p style="color: black;" class="head">Admin Panel</p>
        <form action="modify.php" method="post" class="form_css">
        <?php 
            if (isset($_POST['deleteUserBtn'])) {
                $gotUsername = sanitize($_POST['usernameDelete']);
                if ($gotUsername === "admin") {
                    echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                } 
                else {
                    $sqlQuery = "DELETE FROM users WHERE username = '$gotUsername'";
                    $result = delete_or_update($conn, $sqlQuery);
                    
                    if ($result) {
                        echo '<p style="color: black;" class = "upload">User Deleted</p>';
                    } 
                    else {
                        echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                    }
                }

            } 
            elseif (isset($_POST['deleteChangeBtn'])) {
                $gotUsername = sanitize($_POST['usernameChange']);
                $changeName = sanitize($_POST['changeName']);

                $sqlQuery = "UPDATE users SET username = '$changeName' WHERE username = '$gotUsername'";
                $result = delete_or_update($conn, $sqlQuery);

                if ($result) {
                    echo '<p style="color: black;" class = "upload">Username Changed</p>';
                } 
                else {
                    echo '<p style="color: black;" class = "error">Unable to Change Username</p>';
                }
            } elseif (isset($_POST['deleteimageBtn'])) {
                $gotUsername = sanitize($_POST['imageDelete']);
                $noteName = sanitize($_POST['noteName']);
                $sqlQuery = "SELECT * FROM `$gotUsername` WHERE id = ?";
                $result = TRUE; $i = 1; $matchedID = NULL;
                $isTable = find($conn, $gotUsername, "table");

                while ($result !== NULL && $isTable) {
                    $result = query($conn, $sqlQuery, $i);

                      if (basename($result['note'] === $noteName)) {
                        $matchedID = $i;
                        break;
                    } 
                    elseif ($result === NULL) {
                        break;
                    }
                }

                if ($matchedID !== NULL && $isTable) {
                    $sqlQuery = "DELETE FROM `$gotUsername` WHERE id = '$matchedID'";
                    $result = delete_or_update($conn, $sqlQuery);

                    if ($result) {
                        echo '<p style="color: black;" class = "upload">Image Deleted</p>';
                    }
                    else {
                        echo '<p style="color: black;" class = "error">Unable to Delete Note</p>';
                    }
                } 
                else {
                    echo '<p style="color: black;" class = "error para">No file found</p>';
                }
            } 
            elseif (isset($_POST['listAllBtn'])) {
                $i = 1; $result = TRUE; $users = 0;
                $sqlQuery = "SELECT * FROM users WHERE id = ?";
                echo '<div method="post" action="modify.php" class="form_css">';
                echo    '<h2 style="color: black;">Users</h2>';

                while ($result !== NULL) {
                    $result = query($conn, $sqlQuery, $i);
                    
                    if ($result !== NULL) {
                        $users++;
                        echo "<p class='para' style='color: black; font-size: 18px;'>" . $i . ". " . $result["username"] . "</p>";
                        $i++;
                    }
                    else {
                        break;
                    }
                }

                if ($users === 0) {
                    echo '<p style="color: black;" class = "error para">No users found</p>';
                }

                echo '<button type="submit" name="clear" style="width: 120px;" class="btn para">Clear</button>';
                echo '</div>';
            }
        $conn->close();
    ?>  
            <div class="form_css">
                <p style="color: black; font-size: 25px" class="head">Delete User</p>
                <input type="text" name="usernameDelete" autocomplete="off" class="inp para" placeholder="Username">
                <button type="submit" name="deleteUserBtn" style="width: 120px;" class="btn para">Delete</button>
            </div>
            <div class="form_css">
                <p class="head" style="color: black; font-size: 25px">Modify Username</p>
                <input type="text" name="usernameChange" autocomplete="off" class="inp para" placeholder="Username">
                <input type="text" name="changeName" autocomplete="off" class="inp" placeholder="Username">
                <button type="submit" name="deleteChangeBtn" style="width: 120px;" class="btn para">Modify</button>
            </div>
            <div class="form_css">
                <p class="head" style="color: black; font-size: 25px">Delete Notes</p>
                <input type="text" name="imageDelete" autocomplete="off" class="inp para" placeholder="Username">
                <input type="text" name="noteName" autocomplete="off" class="inp para" placeholder="Note">
                <button type="submit" name="deleteimageBtn" style="width: 120px;" class="btn para">Delete</button>
            </div>
            <div class="form_css" style="box-shadow: none;">
                <p class="head" style="color: black; font-size: 25px">List All Users</p>
                <button type="submit" name="listAllBtn" style="width: 120px;" class="btn para">List</button>
            </div>
        </form>
        <form action="modify.php" method="post" class="form_css" style="margin-bottom: 25px">
            <button type="submit" name="goBack" value="goBack" class="btn para" style="width: 120px; color: black;">Go back</button>
            <button type="submit" name = "logout" class="btn para" value="logout" style="color: black; width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
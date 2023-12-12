<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

session_start();
$username = $_SESSION['admin'];
$path = "uploads/";
if (!isset($_SESSION['admin']) || !decrypt($_SESSION['admin']) == "admin") {
    header("Location: login.php?unauth");   
    die(); 
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php?loggedout");
    die(); 
}  elseif (isset($_POST['goBack'])) {
    header("Location: admin.php");
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
                $gotUsername = $_POST['usernameDelete'];
                if ($gotUsername === "admin") {
                    echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                } 
                else {
                    $sqlQuery = "DELETE FROM users WHERE username = ?";
                    $result = delete_or_update($conn, $sqlQuery, $gotUsername);
                    
                    if ($result) {
                        echo '<p style="color: black;" class = "upload">User Deleted</p>';
                    } 
                    else {
                        echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                    }
                }

            } 
            elseif (isset($_POST['deleteChangeBtn'])) {
                $gotUsername = $_POST['usernameChange'];
                $changeName = $_POST['changeName'];

                $sqlQuery = "UPDATE users SET username = ? WHERE username = ?";
                $result = delete_or_update($conn, $sqlQuery, array($changeName, $gotUsername));

                if ($result) {
                    echo '<p style="color: black;" class = "upload">Username Changed</p>';
                } 
                else {
                    echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                }
            } elseif (isset($_POST['deleteimageBtn'])) {
                $gotUsername = $_POST['imageDelete'];
                $noteName = $_POST['noteName'];
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
                    $sqlQuery = "DELETE FROM `$gotUsername` WHERE id = ?";
                    $result = delete_or_update($conn, $sqlQuery, $matchedID);

                    if ($result) {
                        echo '<p style="color: black;" class = "upload">Image Deleted</p>';
                    }
                    else {
                        echo '<p style="color: black;" class = "error">Unable to Delete Image</p>';
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
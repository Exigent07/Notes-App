<?php 
    session_start();
    $username = $_SESSION['admin'];
    $path = "uploads/";
    require_once('connect.php');
    if (!isset($_SESSION['admin']) || !$_SESSION['admin'] == md5("admin")) {
        header("Location: login.php?unauth");   
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php?loggedout");
    }  elseif (isset($_POST['goBack'])) {
        header("Location: admin.php");
    }
    include("header.php");

?>
<body>
    <div class="main">
        <p style="color: black;" class="head">Admin Panel</p>
        <form action="modify.php" method="post" class="form_css">
        <?php 
            if (isset($_POST['deleteUserBtn'])) {
                $gotUsername = $_POST['usernameDelete'];
                if ($gotUsername == "admin") {
                    echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                } else {
                    $sqlQuery = "DELETE FROM users WHERE username = '$gotUsername'";
                    $result = mysqli_query($conn, $sqlQuery);
    
                    if ($result) {
                        echo '<p style="color: black;" class = "upload">User Deleted</p>';
                    } else {
                        echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                    }
                }

            } elseif (isset($_POST['deleteChangeBtn'])) {
                $gotUsername = $_POST['usernameChange'];
                $changeName = $_POST['changeName'];

                $sqlQuery = "UPDATE users SET username = '$changeName' WHERE username = '$gotUsername'";
                $result = mysqli_query($conn, $sqlQuery);

                if ($result) {
                    echo '<p style="color: black;" class = "upload">Username Changed</p>';
                } else {
                    echo '<p style="color: black;" class = "error">Unable to Delete User</p>';
                }
            } elseif (isset($_POST['deleteimageBtn'])) {
                $gotUsername = $_POST['imageDelete'];
                $noteName = $_POST['noteName'];

                $sqlQuery = "SELECT filePath FROM users WHERE username = '{$gotUsername}'";

                if (mysqli_fetch_row(mysqli_query($conn, $sqlQuery))[0] !== NULL) {
                    $result = mysqli_fetch_row(mysqli_query($conn, $sqlQuery));
                    $file_arr = explode(",", $result[0]);
                    $filePath;

                    for ($i = 0; $i < count($file_arr); $i++) {
                        if (strpos($file_arr[$i], $noteName)) {
                            $filePath = $file_arr[$i];
                            break;
                        } else {
                            $filePath = NULL;
                        }
                    }

                    if ($filePath != NULL) {
                        unlink($filePath);
                        unset($file_arr[$i]);
                        $filePath = join(",", $file_arr);
                        $sqlQuery = "UPDATE users SET filePath = '$filePath' WHERE username = '$gotUsername'";
                        $result = mysqli_query($conn, $sqlQuery);

                        if ($result) {
                            echo '<p style="color: black;" class = "upload">Image Deleted</p>';
                        } else {
                            echo '<p style="color: black;" class = "error">Unable to Delete Image</p>';
                        }
                    } else {
                    echo '<p style="color: black;" class = "error">No file found</p>';
                    }
                } else {
                    echo '<p style="color: black;" class = "upload">User has no image</p>';
                }
            } elseif (isset($_POST['listAllBtn'])) {
                $sqlQuery = "SELECT username FROM users";
                $result = mysqli_query($conn, $sqlQuery);
                $allNames = mysqli_fetch_all($result);
                if ($result) {
                echo '<div method="post" action="modify.php" class="form_css">';
                echo    '<h2 style="color: black;">Users</h2>';
                for ($value = 0; $value < count($allNames); $value++) {
                    $para = $allNames[$value][0];
                    echo "<p style='color: #FF6100; font-size: 18px;'>" . $value + 1 . ". " . $para . "</p>";
                }
                echo    '<button type="submit" name="clear" style="width: 120px;" class="btn">Clear</button>';
                echo '</div>';
                } else {
                    echo '<p style="color: black;" class = "error">Unable to List Users</p>';
                }
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
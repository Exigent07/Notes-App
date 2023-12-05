<?php 
// Checking if the request is a post request and does there is a value for the request body.
    if (isset($_SESSION['uid'])) {
        header("Location: bios.php");   
        die();
    } elseif (isset($_SESSION['admin'])) {
        header("Location: admin.php");   
        die();
    }
    if (isset($_POST["register"])) {
        require_once('connect.php');
        $file = basename($_FILES["file"]["name"]);
        $image = $_FILES['file']['tmp_name'];
        $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/"; 
        $ext = array("png", "jpeg", "jpg");
        $fileSize = $_FILES['file']['size'];

        if (!preg_match($regex, $_POST['password'])) {
            header("Location: register.php?notValid=");
            die();
        } elseif (preg_match("/\s/", $user)) {
            header("Location: register.php?space");
            die();
        } elseif (!in_array(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION), $ext) && !isset($file) ) {
            header("Location: register.php?notAllowed");
            die();
        } elseif ($fileSize > 500000) { 
            header("Location: register.php?large");
            die();
        } else{
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }   

        // Adds slash infront of predefined chars.
        $content = addslashes($file);
        mkdir("profile/" . $user);
        $imagePath = "profile/" . $_POST['username'] . "/" . $file;
        move_uploaded_file($image, $imagePath);
        $sql = "SELECT username FROM users WHERE username = '{$user}'";
        // Checking does the query was successfull
        if (mysqli_query($conn, $sql)) {
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if (!isset($row)) {
                $create_user = $conn->prepare("INSERT INTO users(username, password, profilePath) VALUES(?, ?, ?)");
                $create_user->bind_param("sss", $username,  $password_hashed, $Path);
                // Setting Parameters
                $username = $user;
                $password_hashed = $password;
                $Path = $imagePath;
                // Executing it
                $create_user->execute();
                $create_user->close();
                $conn->close();
                // Redirecting to Login page
                header("Location: login.php?registered");
            } else {
                rmdir("profile/" . $_POST['username']);
                header("Location: register.php?exists");
                die();
            }
        } else {
            echo '<p style="color: orange;">Error: Error Occurred</p>';
            die();
        }
    }
    include("header.php") 
?>
<body>
    <?php include("nav.php"); ?>
    <div class="main">
        <p style="color: black;" class="head">Note Saver Register</p>
        <form action="register.php" method="post" class="form_css" enctype="multipart/form-data">
        <?php
            
            if (isset($_GET['exists'])) {
                echo '<p style="color: black;" class = "error para">Username already exists</p>';
            } elseif (isset($_POST['reg'])) {
                if ($_POST['reg'] === "register") {
                echo '<p style="color: black;" class = "upload para">Register Here!</p>';
                } else {
                    echo '<p style="color: black;" class = "error para">Not authorized!</p>';
                }
            } elseif (isset($_GET['notValid'])) {
                echo '<p style="color: black;" class = "error para">Password Not Strong!</p>';
            } elseif (isset($_GET['notAllowed'])) {
                echo '<p style="color: black;" class = "error para">Not a valid Image file</p>';
            } elseif (isset($_GET['large'])) {
                echo '<p style="color: black;" class = "error para">File size exceeded (500kB)</p>';   
            } elseif (isset($_GET['space'])) {
                echo '<p style="color: black;" class = "error para">Username cannot have space</p>';   
            }
        ?>
            <div class="nameDiv">
                <input placeholder="Username" class="inp para" id="username" name="username" type="text" autocomplete="off" required>
            </div>
            <div class="passDiv">
                <input placeholder="Password" minlength="8" class="inp para" type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <img src="profile/default.png" alt="profile" class="profile">
            <input type="file" name="file" class="fileInput para" accept="image/png, image/jpg, image/jpeg">
            <button type="submit" name="register" class="btn para" style="width: 120px;">Register</button>
        </form>
        <form action="login.php" method="post">
            <p class="register para">Already have an account? <button name="log" class="regLink para" value="login" style="color: white; border: none; background-color: #F96167; cursor: pointer;"> Login here </button></p>
        </form>
    </div>
</body>
</html>
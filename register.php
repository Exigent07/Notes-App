<?php 
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

userAgent();
if (isset($_SESSION['uid'])) {
    $user = decrypt($_SESSION['uid']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO waf(username, ip) VALUES('$user', '$ip')";
    header("Location: bios.php");   
    die();
} 
elseif (isset($_SESSION['admin'])) {
    header("Location: admin.php");   
    die();
}

if (isset($_POST["register"])) {
    $file       = basename($_FILES["file"]["name"]);
    $image      = $_FILES['file']['tmp_name'];
    $user       = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $regex      = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/"; 
    $char       = "/[!@#$%^&*()_+={}\[\]:;<>,.?\/~`'\"-]/";
    $ext        = array("png");
    $fileSize   = $_FILES['file']['size'];
    $name       = time() . ".png";
    $pass       = $_POST['password'];

    $redos_user = redos($user);
    $redos_pass = redos($pass);

    if ($redos_pass || $redos_user) {
        setcookie("error", encrypt("Too long!"));
        header("Location: register.php?error");
        die();
    }

    unset($_COOKIE['error']);

    if (!preg_match($regex, $pass)) {
        setcookie("error", encrypt("Password Not Strong!"));
        header("Location: register.php?error");
        die();
    } 
    elseif (preg_match("/\s/", $user) || preg_match($char, $user)) {
        setcookie("error", encrypt("Username cannot have spaces and characters"));
        header("Location: register.php?error");
        die();
    } 
    elseif (!isset($file) && !in_array(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION), $ext)) {
        setcookie("error", encrypt("Not a valid Image file"));
        header("Location: register.php?error");
        die();
    } 
    elseif ($fileSize > 500000) { 
        setcookie("error", encrypt("File size exceeded (500kB)"));
        header("Location: register.php?error");
        die();
    } 
    else{
        $password = encrypt($_POST['password']);
    }   

    $sql = "SELECT username FROM users WHERE username = ?";
    $row = query($conn, $sql, $user);

    if (!$row) {
        if (!isset($row[$user])) {
            $values             = array($user, $password);
            $isInsertedUser     = insert($conn, "users", $values);
            $Path               = $file ? "profile/" . $_POST['username'] . "/" . $name : NULL;
            $values[1]          = $Path;
            $isInsertedProfile  = insert($conn, "profiles", $values);

            if ($isInsertedUser && $isInsertedProfile) {
                mkdir("profile/" . $user);
                move_uploaded_file($image, $Path);
                header("Location: login.php?registered");
                die();
            }
            else {
                setcookie("error", "Registration Failed");
                header("Location: register.php?error");
                die();
            }
        } 
    }             
    elseif ($row) {
        setcookie("error", encrypt("Username already exists"));
        header("Location: register.php?error");
        die();
    } 
    else {
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        die();
    }
}
$conn->close();
require_once('Helpers/header.php');
?>
<body>
    <?php include("nav.php"); ?>
    <div class="main">
        <p style="color: black;" class="head">Note Saver Register</p>
        <form action="register.php" method="post" class="form_css" enctype="multipart/form-data">
        <?php
            
            if (isset($_GET['error'])) {
                $error = isset($_COOKIE['error']) ? decrypt($_COOKIE['error']) : "Register Here";
                echo '<p style="width: 400px" class = "error para">' . $error . '</p>';
            } elseif (isset($_POST['reg'])) {
                if ($_POST['reg'] === "register") {
                echo '<p style="color: black;" class = "upload para">Register Here!</p>';
                } else {
                    echo '<p style="color: black;" class = "error para">Not authorized!</p>';
                }
            }
        ?>
            <div class="nameDiv">
                <input placeholder="Username" class="inp para" id="username" name="username" type="text" autocomplete="off" required>
            </div>
            <div class="passDiv">
                <input placeholder="Password" minlength="8" class="inp para" type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <img src="profile/default.png" alt="profile" class="profile">
            <input type="file" name="file" class="fileInput para" accept="image/png">
            <button type="submit" name="register" class="btn para" style="width: 120px;">Register</button>
        </form>
        <form action="login.php" method="post">
            <p class="register para">Already have an account? <button name="log" class="regLink para" value="login" style="color: white; border: none; background-color: #F96167; cursor: pointer;"> Login here </button></p>
        </form>
    </div>
</body>
</html>
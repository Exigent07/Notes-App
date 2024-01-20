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
    $regex      = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/"; 
    $char       = "/[!@#$%^&*()_+={}\[\]:;<>,.?\/~`'\"-]/";
    $ext        = array("png");
    $fileSize   = $_FILES['file']['size'];
    $name       = time() . ".png";
    $pass       = $_POST['password'];

    $redos_user = redos($user);
    $redos_pass = redos($pass);

    if ($redos_pass || $redos_user) {
        http_response_code(403);
        die();
    }

    unset($_COOKIE['error']);

    if (!preg_match($regex, $pass)) {
        http_response_code(406);
        die();
    } 
    elseif (preg_match("/\s/", $user) || preg_match($char, $user)) {
        http_response_code(403);
        die();
    } 
    elseif (!isset($file) && !in_array(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION), $ext)) {
        http_response_code(415);
        die();
    } 
    elseif ($fileSize > 500000) { 
        http_response_code(413);
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
                http_response_code(200);
                die();
            }
            else {
                http_response_code(403);
                die();
            }
        } 
    }             
    elseif ($row) {
        http_response_code(401);
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
        <form id="registerForm" class="form_css" enctype="multipart/form-data">
            <p style="color: black; display: none;" id="error" class = "error para"></p>
            <div class="nameDiv">
                <input placeholder="Username" class="inp para" id="username" name="username" type="text" autocomplete="off" required>
            </div>
            <div class="passDiv">
                <input placeholder="Password" minlength="8" class="inp para" type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <img src="profile/default.png" id="preview" alt="profile" class="profile">
            <input type="file" name="file" id='file' class="fileInput para" accept="image/png">
            <button type="submit" name="register" class="btn para" style="width: 120px;">Register</button>
        </form>
        <div>
            <p class="register para">Already have an account? <button onclick="loginPage()" name="log" class="regLink para" value="login" style="color: white; border: none; background-color: #F96167; cursor: pointer;"> Login here </button></p>
        </div>
    </div>
<script src="main.js"></script>
</body>
</html>
<?php
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');

userAgent();
session_start();
if (isset($_SESSION['uid'])) {
    header("Location: bios.php");  
    die(); 
} 
elseif (isset($_SESSION['admin']) && $_SESSION['admin'] == decrypt('admin')) {
    header("Location: admin.php");   
    die();
} 
elseif (isset($_POST["login"])) {
    $user       = $_POST['username'];
    $pass       = $_POST['password'];
    $sql        = "SELECT password FROM users WHERE username = ?";
    $row        = query($conn, $sql, $user);
    $redos_user = redos($user);
    $redos_pass = redos($pass);

    if ($redos_pass || $redos_user) {
        http_response_code(403);
        die();
    }
    $user   = sanitize($_POST['username']);
    if ($row) {
        $verified = verify($row['password'], $pass);
        
        if ($verified) {
            if ($user === "admin")  {
                $_SESSION['admin'] = encrypt('admin');
                http_response_code(301);
                die();
            } else {
                $_SESSION['uid'] = encrypt($user);
                increase_cookie($user);
                $ip = $_SERVER['REMOTE_ADDR'];
                // $query = "INSERT INTO waf(username, ip) VALUES('$user', '$ip')";
                insert($conn, "waf", array($user, $ip, 0));
                http_response_code(200);
            }
        } else {
            http_response_code(401);
            die();
        }
    } else {
        isBlocked($conn);
        http_response_code(403);
        die();
    }
}
require_once('Helpers/header.php');
?>
<body>
    <?php 
    include("nav.php"); 
    $conn->close();
    ?>    
    <div class="main">
        <p style="color: black;" class="head">Note Saver Login</p>
        <p style="color: black;" class="para">Save Your Notes Here!</p>
        <form id="loginForm" class="form_css">
            <p style="color: black; display:none" id="error" class = "error para"></p>
            <div class="nameDiv">
                <input placeholder="Username" class="inp para" id="username" name="username" type="text" autocomplete="off" required>
            </div>
            <div class="passDiv">
                <input placeholder="Password" class="inp para" type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <button type="submit" name = "login" class="btn para" style="width: 120px;">Login</button>
        </form>
        <div>
            <p class="register para">Don't have an account? <button onclick="registerPage()" name="reg" class="regLink para" value="register" style="color: white; border: none; background-color: #F96167; cursor: pointer;"> Register here </button></p>
        </div>
    </div>
<script src="main.js"></script>
</body>
</html>
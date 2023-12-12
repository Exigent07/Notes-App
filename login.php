<?php
require_once('Helpers/connect.php');
require_once('Helpers/encryption.php');
require_once('Helpers/functions.php');


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
    $user   = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $pass   = $_POST['password'];
    $sql    = "SELECT password FROM users WHERE username = ?";
    $row    = query($conn, $sql, $user);
    
    if ($row) {
        $verified = verify($row['password'], $pass);
        
        if ($verified) {
            if ($user === "admin")  {
                $_SESSION['admin'] = encrypt('admin');
                header("Location: admin.php");
            } else {
                $_SESSION['uid'] = encrypt($user);
                increase_cookie($user);
                echo "Success";
                header("Location: bios.php");
                die();
            }
        } else {
            echo "invalid";
            header("Location: login.php?invalid");
            die();
        }
    } else {
        echo '<p style="color: orange;">Error Occured</p>';
    }
}
require_once('Helpers/header.php');
?>
<body>
    <?php include("nav.php"); ?>    
    <div class="main">
        <p style="color: black;" class="head">Note Saver Login</p>
        <p style="color: black;" class="para">Save Your Notes Here!</p>
        <form action="login.php" method="post" class="form_css">
            <?php 
                if (isset($_GET['loggedout'])) {
                    echo '<p style="color: black;" class = "upload para">Logged out successfully</p>';
                } elseif (isset($_POST['log'])) {
                    if ($_POST['log'] === "login") {
                    echo '<p style="color: black;" class = "upload para">Login Here!</p>';
                    } else {
                        echo '<p style="color: black;" class = "error">Not authorized!</p>';
                    }
                } elseif (isset($_GET['registered'])) {
                    echo '<p style="color: black;" class = "upload para">Registration successfully</p>';
                } elseif (isset($_GET['unauth'])) {
                    echo '<p style="color: black;" class = "error para">Unauthorized</p>';
                } elseif (isset($_GET['invalid'])) {
                        echo '<p style="color: black;" class = "error para">Invalid username or password</p>';
                }
            ?>
            <div class="nameDiv">
                <input placeholder="Username" class="inp para" id="username" name="username" type="text" autocomplete="off" required>
            </div>
            <div class="passDiv">
                <input placeholder="Password" class="inp para" type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <button type="submit" name = "login" class="btn para" style="width: 120px;">Login</button>
        </form>
        <form action="register.php" method="post">
            <p class="register para">Don't have an account? <button name="reg" class="regLink para" value="register" style="color: white; border: none; background-color: #F96167; cursor: pointer;"> Register here </button></p>
        </form>
    </div>
</body>
</html>
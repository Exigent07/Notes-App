<?php 
    session_start();
    include("encryption.php");
    if (isset($_SESSION['uid'])) {
        header("Location: bios.php");   
    } elseif (isset($_SESSION['admin'])) {
        header("Location: admin.php");   
        die();
    } elseif (isset($_POST["login"])) {
        require_once('connect.php');

        $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $pass = $_POST['password'];

        $sql = "SELECT password FROM users WHERE username = '{$user}'";
            
        if (mysqli_query($conn, $sql)) {
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if ($row !== NULL && password_verify($pass, $row[0])) {
                if ($user === "admin") {
                    $_SESSION['admin'] = md5($user);
                    header("Location: admin.php");
                } else {
                    $_SESSION['uid'] = encrypt($user);
                    $username = $user;
                    if (isset($_COOKIE[$username])){
                        $current = decrypt($_COOKIE[$username]);
                        $value_cookie = ((int)$current) + 1;
                        unset($_COOKIE[$username]);
                        setrawcookie($username, encrypt(strval($value_cookie)));
                        echo decrypt($_COOKIE[$username]);
                    } else {
                        $value_cookie = 1;
                        setrawcookie($username, encrypt(strval($value_cookie)));
                    }
                    $_SESSION['logged'] = 'true';
                    header("Location: bios.php");
                }
            } 
        } else {
            echo '<p style="color: orange;">Error Occured</p>';
        }
    }
    include("header.php");
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
                
                // elseif (isset($pass) && !isset($user)) {
                //     echo '<p style="color: black;" class = "error">Username field cant be empty</p>';
                // } elseif (!isset($pass) && isset($user)) {
                //     echo '<p style="color: black;" class = "error">Password field cant be empty</p>';
                // } elseif (!isset($pass) && !isset($user)) {
                //     echo '<p style="color: black;" class = "error">Fields cant be empty</p>';
                // } 
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
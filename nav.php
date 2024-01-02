<nav class="nav">
    <div class="site">
        <img src="files/notes.png" class="logo" alt="notes">
        <a href="login.php" class="siteName">Note Saver</a>
    </div>
    <?php 
        $url = $_SERVER['PHP_SELF'];
        if ($url == "/bi0s/login.php" || $url == "/bi0s/register.php") {
            echo ' <div class="choose"> 
                        <a class="select" href="login.php">Log in</a>
                        <a class="select" href="register.php">Register</a>
                    </div>';
        } else {
            echo '
                <form method="post" class="choose"> 
                    <a class="select" href="bios.php">Upload</a>
                    <a class="select" href="view_images.php?view">View</a>
                    <button type="submit" name = "logout" class="select" value="logout" style="cursor: pointer;">Logout</button>
                </form>';
        }

    ?>
</nav>

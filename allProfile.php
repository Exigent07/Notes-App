<?php 
    session_start();
    $username = $_SESSION['admin'];
    if (!isset($_SESSION['admin']) || !$_SESSION['admin'] == md5("admin")) {
        header("Location: login.php?unauth=");   
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
        <h1 style="color: black;">Profiles</h1>
        <form action="allProfile.php" method="post" class="form_css">
        <?php 
            if (isset($_GET['viewProfile'])) {
                $path = "uploads/";

                require_once('connect.php');
                 
                # echo $fileType;
                $querry = mysqli_query($conn, "SELECT username, profilePath FROM users");
                $getImage = mysqli_fetch_all($querry);
                $defaultPath = "profile/default.png";

                // echo count(($getImage));
                
                // echo "<img style='width: 250px;' src='$result'/>";

                for ($user = 0; $user < count($getImage); $user++) {
                    $name = $getImage[$user][0];
                    if ($name == "admin") {
                        continue;
                    }
                    if ($getImage[$user][1] !== NULL) {
                        $file = $getImage[$user][1];
                        echo "<div class='viewAll'>
                        <h3>#" . $getImage[$user][0] . "</h3>";
                            echo "
                            <img class='img' src=" . "'" . $file . "'" . "></img>";
                            
                            // echo '<h3>' . $filelabel . '</h3><p class="showNote">' .  . '</p>';
                            echo "</div>";
                    } else {
                            echo '<h3>#' . $getImage[$user][0] . '</h3><img class="img" src=' . "'" . $defaultPath . "'" . '></img>';
                        } 
                    }

                }
    ?>
        </form>
        <form action="allProfile.php" method="post" class="form_css">
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
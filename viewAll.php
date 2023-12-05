<?php 
    session_start();
    $username = $_SESSION['admin'];
    if (!isset($username) && $username == md5("admin")) {
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
        <h1 style="color: black;">All Notes</h1>
        <form action="viewAll.php" method="post" class="form_css">
        <?php 
                if (isset($_GET['viewall'])) {
                $path = "uploads/";

                require_once('connect.php');
                 
                # echo $fileType;nl2br(htmlspecialchars(file_get_contents($getImage[0][1])))
                $querry = mysqli_query($conn, "SELECT username, filePath FROM users");
                $getImage = mysqli_fetch_all($querry);

                // echo count(($getImage));
                
                // echo "<img style='width: 250px;' src='$result'/>";

                for ($user = 0; $user < count($getImage); $user++) {
                    $name = $getImage[$user][0];
                    if ($getImage[$user][1] !== NULL) {
                        $file = $getImage[$user][1];
                        $filelabel = basename($file);
                        if (strpos($file, ",")) {
                            $path_arr = explode(",", $file);
                            echo "<div class='viewAll'>
                            <h3 style='text-decoration: underline;'>" . $name . "</h3>";
                            for ($txt = 0; $txt < count($path_arr); $txt++){
                                $filelabel = basename($path_arr[$txt]);
                                $fileContent = nl2br(file_get_contents($path_arr[$txt]));

                                echo "
                                <p>#" . $filelabel . "</p>
                                <p class='showNote'>" . filter_var($fileContent, FILTER_SANITIZE_STRING) . "</p>";
                                
                                // echo '<h3>' . $filelabel . '</h3><p class="showNote">' .  . '</p>';
                            }
                            echo "</div>";
                        } else {
                            if ($getImage[0][1] != NULL) {
                                $filelabel = basename($getImage[0][1]);
                                $fileContent = nl2br(file_get_contents($getImage[0][1]));

                                echo '<h3>' . $filelabel . '</h3><p class="showNote">' . filter_var($fileContent, FILTER_SANITIZE_STRING). '</p>';
                            } else {
                                echo '<p style="color: black;" class = "error">Nothing to Show!</p>';
                            }
                        }
                    } else {
                        $file = NULL;
                    }

                }
            }
    ?>
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
        </form>
        <form action="view_images.php" method="post" class="form_css">
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
<?php 
    session_start();
    include("encryption.php");
    $username = decrypt($_SESSION['uid']);

    if (!isset($username) || $username == "") {
        header("Location: login.php?unauth");   
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php?loggedout");
    }  elseif (isset($_POST['goBack'])) {
        header("Location: bios.php");
    }
    include("header.php");
?>
<body>
    <?php include("nav.php"); ?>    
    <div class="main">
        <p class="head" style="color: black;">Your Text Files</p>
        <form action="viewNotes.php" method="post" class="form_css">
        <?php 
             if (isset($_GET['view'])) {
                $path = "uploads/";

                require_once('connect.php');
                 
                # echo $fileType;
                $querry = mysqli_query($conn, "SELECT filePath FROM users WHERE username = '{$username}'");
                $getImage = mysqli_fetch_row($querry);

                $result = $getImage[0];

                if ($result != NULL) {
                    if (strpos($result, ",")) {
                        $path_arr = explode (",", $result);
                        for ($txt = 0; $txt < count($path_arr); $txt++){
                            $filelabel = basename($path_arr[$txt]);
                            echo '<form action="viewNotes.php" method="post" class="form_css"><h3>' . $filelabel . '</h3><p class="showNote"><input name="pathValue" type="hidden" value=' . "'" . $path_arr[$txt] . "'" . '>' . filter_var(nl2br(file_get_contents($path_arr[$txt])), FILTER_SANITIZE_STRING) . '</p><button vlaue="viewIt" type="submit" class="btn">View Note</button></form>';
                        }
                    } else {
                        $filelabel = basename($result);
                        echo '<form action="viewNotes.php" method="post" class="form_css" class="viewAll"><h3>' . $filelabel . '</h3><p class="showNote"><input type="hidden" name="pathValue"  value=' . "'" . $result . "'" . '>' . filter_var(nl2br(file_get_contents($result)), FILTER_SANITIZE_STRING) . '</p><button vlaue="viewIt" class="btn">View Note</button></form>';
                    }
                } else  {
                    echo '<p style="color: black;" class = "error">Nothing to Show!</p>';
                }
            }
        ?>
        </form>
        <form action="view_images.php" method="post" class="form_css">
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
            <button type="submit" name = "logout" class="btn" value="logout" style="width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
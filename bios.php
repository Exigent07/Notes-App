<?php 
    session_start();
    include("encryption.php");
    $username = decrypt($_SESSION['uid']);
    if (!isset($username) || $username == "") {
        header("Location: login.php?unauth");   
    } 
    elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php?loggedout");
    } elseif (isset($_POST['viewImage'])) {
        header("Location: view_images.php?view");
    }
    $times = decrypt($_COOKIE[$username]);
    include("header.php");
    require_once('connect.php');
?>
<body>
    <?php include("nav.php"); ?>
    <div class="main">
        <p class="head" style="color: black;">Welcome to Note Saver!</p>
        <form action="bios.php" method="post" class="form_css" enctype="multipart/form-data">
        <?php 
            echo '<p style="color: black;" class = "upload para">' . $username . '</p>';
            if (decrypt($_COOKIE[$username]) == "1") {
                echo '<p style="color: black;" class = "para"> This is your first login today.</p>';
            } else {
                echo '<p style="color: black;" class = "para">You logged in ' . $times . ' times today.</p>';
            }
            $profilePath = mysqli_fetch_row(mysqli_query($conn, "SELECT profilePath FROM users WHERE username = '{$username}'"));
            $defaultPath = "profile/default.png";
            if ($profilePath[0] != NULL) {
                echo "<img src= '$profilePath[0]' class='profile'></img>";
            } else {
                echo "<img src= '$defaultPath' class='profile'></img>";
            }
            if (isset($_POST['submitFile'])) {
                $path = "uploads/";
                $check = "/:\/\//";
                $fileName = filter_var(basename($_FILES["file"]["name"]), FILTER_SANITIZE_STRING);
                $fileRename = filter_var($_POST['fileName'], FILTER_SANITIZE_STRING);
                $match = preg_match($check, $fileRename);

                if ($match) {
                    echo '<p style="color: black;" class = "error para">Upload failed</p>';
                } else {
                    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uploadPath = $path . $username . "/" . $fileName;
                    $addTo = $username; 
                    $renamePathFile = $path . $username . "/" . $fileRename;
                    $pathFile = $path . $username . "/" . $fileName;
    
                    # echo $fileType;
                    if ($fileType == 'txt') {
                        $image = $_FILES['file']['tmp_name'];
                        $content = addslashes(file_get_contents($image));
                        $checkFile = mysqli_fetch_row(mysqli_query($conn, "SELECT filePath FROM users WHERE username = '{$addTo}'"));
                        if (!$checkFile[0] == NULL) {
                            if (!is_dir($path . $username)) {
                                mkdir($path . $username);
                                $pathFile = $renamePathFile;
                            } else {
                                if ($checkFile[0]) {
                                    $pathFile = $checkFile[0] . "," . $path . $username . "/" . $fileRename;
                                }
                            }                        
                            $sql = "UPDATE users SET filePath = '$pathFile' WHERE username = '{$addTo}'";
                            $insert = mysqli_query($conn, $sql);
                            if ($insert) {
                                move_uploaded_file($image, $uploadPath);
                                rename($uploadPath, $renamePathFile);
                                echo '<p style="color: black;" class = "upload para">Note uploaded</p>';
                            } else {
                                echo '<p style="color: black;" class = "error para">Upload failed</p>';
                            }
                        } else {
                            if (!is_dir($path . $username)) {
                                mkdir($path . $username);
                                $pathFile = $renamePathFile;
                            }
                            $sql = "UPDATE users SET filePath = '$renamePathFile' WHERE username = '{$addTo}'";
                            $insert = mysqli_query($conn, $sql);
                            if ($insert) {
                                move_uploaded_file($image, $pathFile);
                                rename($pathFile, $renamePathFile);
                                echo '<p style="color: black;" class = "upload para">Note uploaded</p>';
                            } else {
                                echo '<p style="color: black;" class = "error para">Upload failed</p>';
                            }
                        }
                    } else {
                        echo '<p style="color: black;" class = "upload">Not a txt file!</p>';
                    }
                    // $querry = mysqli_query($conn, "SELECT filePath FROM users WHERE username = '{$addTo}'");
                    // $getImage = mysqli_fetch_row($querry);
                    
                    // $result = $getImage[0];
                    // echo '<p class="showNote">' . nl2br(file_get_contents($result)) . '</p>';
                }
            }
                
?>
            <input type="file" name="file" required class="fileInput para">
            <input type="text" name="fileName" required class="inp para" placeholder="Filename.txt" autocomplete="off">
            <button type="submit" name="submitFile" value="upload" class="btn para" style="width: 120px;">Upload</button>
        </form>
        <form action="bios.php" method="post" class="form_css">
            <button type="submit" name = "viewImage" class="btn para" value="viewImage" style="color: black; width: 120px;">View Notes</button>
            <button type="submit" name = "logout" class="btn para" value="logout" style="color: black; width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html> 
<?php
    session_start();
    include("encryption.php");
    $username = decrypt($_SESSION['uid']);
    if (!isset($username)) {
        header("Location: login.php?unauth");   
    }

    if (isset($_POST['goBack'])) {
        header("Location: view_images.php?view");
    }
    include("header.php");
?>
<body>
<?php include("nav.php"); ?>    
<form action="viewNotes.php" method="post" class="noteBody">
    <?php 
        $path = $_POST['pathValue'];
        $filelabel = basename($path);
        $ext = strpos($filelabel, '.txt');
        $withoutExt = $ext ? strtoupper(substr($filelabel, 0, $ext)) : strtoupper($filelabel);
        echo "<h2 class='heading'>" . $withoutExt . "</h2>";
        echo "<div class='viewNotes'><p>" . filter_var(nl2br(file_get_contents($path)), FILTER_SANITIZE_STRING) . "</p></div>";
        # echo $fileType;

        // echo count(($getImage));
        
        // echo "<img style='width: 250px;' src='$result'/>";
    ?>
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
        </form>
</body>
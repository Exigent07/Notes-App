<?php
    session_start();
    include("Helpers/encryption.php");
    $username = decrypt($_SESSION['uid']);
    if (!isset($username) || $username == "") {
        header("Location: login.php?unauth");   
        die(); 
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php?loggedout");
        die(); 
    }  elseif (isset($_POST['goBack'])) {
        header("Location: view_images.php?view");
        die(); 
    }
    include("Helpers/header.php");
?>
<body>
<?php include("nav.php"); ?>    
<form action="viewNotes.php" method="post" class="noteBody">
    <?php 
        $path = $_POST['pathValue'];
        $filelabel = basename($path, '.txt');
        echo "<h2 class='heading'>" . strtoupper($filelabel) . "</h2>";
        echo "<div class='viewNotes'><p>" . nl2br(htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8')) . "</p></div>";
    ?>
            <button type="submit" name="goBack" value="goBack" class="btn" style="width: 120px;">Go back</button>
        </form>
</body>
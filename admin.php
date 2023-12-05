<?php 
    session_start();
    // echo isset($_SESSION['admin']) . "<br>";
    // echo $_SESSION['admin'] . "<br>";
    // echo md5('admin');

    if (!isset($_SESSION['admin']) || !$_SESSION['admin'] == md5("admin")) {
        header("Location: login.php?unauth");   
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php?loggedout");
    } elseif (isset($_POST['viewAll'])) {
        header("Location: viewAll.php?viewall");
    } elseif (isset($_POST['modifyUsers'])) {
        header("Location: modify.php?modify");
    } elseif (isset($_POST['viewProfile'])) {
        header("Location: allProfile.php?viewProfile");
    }
    include("header.php");
?>
<body>
    <div class="main">
        <p style="color: black;" class="head">Admin Panel</p>
        <form action="admin.php" method="post" class="form_css">
            <button type="submit" name="modifyUsers" value="modifyUsers" class="btn para" style="width: 120px;">Modify Users</button>
            <button type="submit" name="viewAll" value="vieaAll" class="btn para" style="width: 140px;">View all Notes</button>
            <button type="submit" name="viewProfile" value="viewProfile" class="btn para" style="width: 140px;">View all Profiles</button>
        </form>
        <form action="admin.php" method="post" class="form_css">
            <button type="submit" name = "logout" class="btn para" value="logout" style="color: black; width: 120px;">Logout</button>
        </form>
    </div>
</body>
</html>
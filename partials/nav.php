<!DOCTYPE html>
<html>
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<head>
<link rel = "stylesheet" type="text/css" href="theme.css" media="all"/>
</head>

<body>
    <div class="navbar">

        <a class="navItem" href="home.php">Home</a>
        <?php if (!is_logged_in()): ?>
            <a class="navItem" href="login.php">Login</a>
            <a class="navItem" href="register.php">Register</a>
        <?php endif; ?>
        <?php if (is_logged_in()): ?>
            <a class="navItem" href="profile.php">Profile</a>
            <a class="navItem" href="logout.php">aLogout</a>
        <?php endif; ?>

    </div>
</body>

</html>
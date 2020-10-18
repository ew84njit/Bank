<!DOCTYPE html>
<html>
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<head>
<link rel = "stylesheet" type="text/css" href = "theme.css" media="all"/>
</head>

<body>
<div class="navbar">
    <ul>
        <li><a class="navItem" href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a class="navItem" href="login.php">Login</a></li>
            <li><a class="navItem" href="register.php">Register</a></li>
        <?php endif; ?>
        <?php if (is_logged_in()): ?>
            <li><a class="navItem" href="profile.php">Profile</a></li>
            <li><a class="navItem" href="logout.php">aLogout</a></li>
        <?php endif; ?>
    </ul>
</div>
</body>

</html>